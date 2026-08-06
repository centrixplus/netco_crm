<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\ContainerLine;
use App\Models\ContainerReceiptLog;
use App\Models\ContainerExtraLine;
use App\Models\ContainerUploadLog;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Auth;
use DB;

class ContainerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $title = "Container Dashboard";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if(!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $vendors = Vendor::where('delete_status','0')->orderBy('name','asc')->get();
        $vendor_id = $request->vendor_id ?: '';
        $mode      = $request->mode_of_shipment ?: '';

        // --- Filtered containers -------------------------------------------------
        $cq = Container::with('vendor')->where('delete_status','0')
            ->whereBetween('received_date',[$fromdate, $todate]);
        if(!empty($vendor_id)){ $cq->where('vendor_id',$vendor_id); }
        if(!empty($mode)){ $cq->where('mode_of_shipment',$mode); }
        $containers = $cq->orderBy('id','Desc')->get();
        $ids = $containers->pluck('id')->all();

        // --- Per-container line stats (capped completion = Method 3) -------------
        $lineStats = collect();
        if(!empty($ids)){
            $lineStats = ContainerLine::whereIn('container_id',$ids)->where('delete_status','0')
                ->selectRaw('container_id,
                             SUM(quantity) as ordered,
                             SUM(LEAST(received_qty, quantity)) as capped,
                             SUM(received_qty) as received,
                             COUNT(*) as line_count,
                             SUM(CASE WHEN received_qty > quantity THEN 1 ELSE 0 END) as over_lines')
                ->groupBy('container_id')->get()->keyBy('container_id');
        }

        // --- Roll up KPIs / buckets / non-completed list ------------------------
        $totalContainers = $containers->count();
        $statusCounts = ['New'=>0,'In Progress'=>0,'Completed'=>0];
        $totalLines=0; $orderedQty=0; $receivedQty=0; $cappedQty=0; $overLines=0;
        $buckets = ['0'=>0,'1-49'=>0,'50-99'=>0,'100'=>0];
        $completedCount=0; $nonCompletedCount=0; $nonCompleted=[];
        $byMode = ['AIR'=>0,'LAND'=>0,'SEA'=>0];

        foreach($containers as $c){
            $st = $lineStats->get($c->id);
            $ordered  = $st ? (float)$st->ordered  : 0;
            $capped   = $st ? (float)$st->capped   : 0;
            $received = $st ? (float)$st->received : 0;
            $lines    = $st ? (int)$st->line_count : 0;
            $over     = $st ? (int)$st->over_lines : 0;

            $totalLines += $lines; $orderedQty += $ordered; $receivedQty += $received;
            $cappedQty += $capped; $overLines += $over;

            $s = $c->status ?: 'New';
            if(!isset($statusCounts[$s])){ $statusCounts[$s] = 0; }
            $statusCounts[$s]++;

            if($c->mode_of_shipment && isset($byMode[$c->mode_of_shipment])){ $byMode[$c->mode_of_shipment]++; }

            $pct = $ordered > 0 ? round($capped / $ordered * 100, 2) : 0;
            if($pct >= 100){
                $completedCount++; $buckets['100']++;
            }else{
                $nonCompletedCount++;
                if($pct == 0){ $buckets['0']++; }
                elseif($pct < 50){ $buckets['1-49']++; }
                else { $buckets['50-99']++; }
                $nonCompleted[] = [
                    'id'          => $c->id,
                    'container_no'=> $c->container_no,
                    'vendor'      => optional($c->vendor)->name,
                    'status'      => $s,
                    'ordered'     => (int)$ordered,
                    'received'    => (int)$received,
                    'pending'     => (int)($ordered - $capped),
                    'pct'         => $pct,
                ];
            }
        }
        usort($nonCompleted, function($a,$b){ return $a['pct'] <=> $b['pct']; });

        $overallPct = $orderedQty > 0 ? round($cappedQty / $orderedQty * 100, 2) : 0;
        $pendingQty = (int)($orderedQty - $cappedQty);
        $orderedQty = (int)$orderedQty;
        $receivedQty = (int)$receivedQty;

        // --- Extra parts --------------------------------------------------------
        $extraCount = 0; $extraQty = 0;
        if(!empty($ids)){
            $ex = ContainerExtraLine::whereIn('container_id',$ids)->selectRaw('COUNT(*) c, SUM(received_qty) q')->first();
            $extraCount = $ex ? (int)$ex->c : 0;
            $extraQty   = $ex ? (int)$ex->q : 0;
        }

        // --- Receiving trend (per day) -----------------------------------------
        $trendLabels = []; $trendData = [];
        if(!empty($ids)){
            $trend = ContainerReceiptLog::whereIn('container_id',$ids)
                ->selectRaw('DATE(created_at) as d, SUM(received_qty) as q')
                ->groupBy(DB::raw('DATE(created_at)'))->orderBy('d','asc')->get();
            foreach($trend as $t){
                $trendLabels[] = date('d-m-Y', strtotime($t->d));
                $trendData[]   = (int)$t->q;
            }
        }

        // --- Top vendors / brands by ordered qty --------------------------------
        $vendorLabels=[]; $vendorData=[]; $brandLabels=[]; $brandData=[];
        if(!empty($ids)){
            $tv = ContainerLine::whereIn('container_lines.container_id',$ids)->where('container_lines.delete_status','0')
                ->join('containers','containers.id','=','container_lines.container_id')
                ->leftJoin('vendors','vendors.id','=','containers.vendor_id')
                ->selectRaw('vendors.name as name, SUM(container_lines.quantity) as q')
                ->groupBy('vendors.name')->orderBy('q','desc')->limit(10)->get();
            foreach($tv as $v){ $vendorLabels[] = $v->name ?: '-'; $vendorData[] = (int)$v->q; }

            $tb = ContainerLine::whereIn('container_id',$ids)->where('delete_status','0')
                ->selectRaw('brand, SUM(quantity) as q')
                ->groupBy('brand')->orderBy('q','desc')->limit(10)->get();
            foreach($tb as $b){ $brandLabels[] = $b->brand ?: '(blank)'; $brandData[] = (int)$b->q; }
        }

        // --- Recent activity ----------------------------------------------------
        $recentReceipts = collect(); $recentUploads = collect();
        if(!empty($ids)){
            $recentReceipts = ContainerReceiptLog::with(['container','creator'])->whereIn('container_id',$ids)
                ->orderBy('id','Desc')->limit(10)->get();
            $recentUploads = ContainerUploadLog::with(['container','creator'])->whereIn('container_id',$ids)
                ->orderBy('id','Desc')->limit(10)->get();
        }

        return view('container.dashboard', compact(
            'title','fromdate','todate','vendors','vendor_id','mode',
            'totalContainers','statusCounts','totalLines','orderedQty','receivedQty','overallPct','pendingQty',
            'completedCount','nonCompletedCount','buckets','nonCompleted','byMode','extraCount','extraQty','overLines',
            'trendLabels','trendData','vendorLabels','vendorData','brandLabels','brandData',
            'recentReceipts','recentUploads'
        ));
    }
}
