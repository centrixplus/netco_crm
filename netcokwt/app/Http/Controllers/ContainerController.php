<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\ContainerLine;
use App\Models\ContainerReceiptLog;
use App\Models\ContainerExtraLine;
use App\Models\ContainerUploadLog;
use App\Models\ContainerUploadUnmatched;
use App\Models\ContainerStatusLog;
use App\Models\Vendor;
use App\Models\Tag;
use App\Models\Uploadlog;
use Illuminate\Http\Request;
use Auth;
use Excel;
use DB;

class ContainerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* ---------------------------------------------------------------
     |  Container list / create / store
     * --------------------------------------------------------------- */

    public function index(Request $request)
    {
        $title = "Containers";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if(!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $vendors = Vendor::where('delete_status','0')->orderBy('name','asc')->get();
        $tags = Tag::where('delete_status','0')->orderBy('name','asc')->get();

        $indexes = Container::with('vendor')->where('delete_status','0')
                    ->whereBetween('received_date',[$fromdate, $todate])
                    ->withCount('lines');
        $vendor_id = '';
        if(!empty($request->vendor_id)){
            $vendor_id = $request->vendor_id;
            $indexes = $indexes->where('vendor_id',$vendor_id);
        }
        $mode = '';
        if(!empty($request->mode_of_shipment)){
            $mode = $request->mode_of_shipment;
            $indexes = $indexes->where('mode_of_shipment',$mode);
        }
        $tag_id = '';
        if(!empty($request->tag_id)){
            $tag_id = $request->tag_id;
            $indexes = $indexes->whereRaw('FIND_IN_SET(?, tags)', [$tag_id]);
        }
        $indexes = $indexes->orderBy('id','Desc')->get();

        // Completion % per container (Method 3 - capped: SUM(LEAST(received,ordered)) / SUM(ordered)).
        $ids = $indexes->pluck('id')->all();
        $stats = ContainerLine::whereIn('container_id',$ids)->where('delete_status','0')
            ->selectRaw('container_id, SUM(LEAST(received_qty, quantity)) as capped, SUM(quantity) as ordered')
            ->groupBy('container_id')->get()->keyBy('container_id');

        return view('container.index',
            compact('title','indexes','fromdate','todate','vendors','vendor_id','tags','tag_id','mode','stats'));
    }

    public function create()
    {
        $title = "Create Container";
        $vendors = Vendor::where('delete_status','0')->orderBy('name','asc')->get();
        $tags = Tag::where('delete_status','0')->orderBy('name','asc')->get();
        return view('container.create',compact('title','vendors','tags'));
    }

    // Modal vendor picker (same UX as the quote "Select Customer").
    public function vendorSearch()
    {
        $vendors = Vendor::where('delete_status','0')->orderBy('name','asc')->get();
        $html = view('container.vendorsearch',compact('vendors'))->render();
        echo json_encode(array("html"=>$html));
    }

    // Blank template (with one sample row) for the lines upload.
    public function lineFormat()
    {
        $headers = ['CtnNo','PartNo','Description','QUANTITY','BRAND','LOCATION','CUS ID','note 1','note 2','note'];
        $sample  = [['442500','04371-60070','UNIVERSAL JOINT','50','TOYOTA','A-01','','','','']];
        return $this->streamCsv('container_lines_format.csv',$headers,$sample);
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id'    => 'required',
            'container_no' => 'required',
            'import_file'  => 'required|file',
        ]);

        // --- Read & validate the file BEFORE writing anything ---------
        $path = $request->file('import_file');
        $sheet = Excel::toArray([], $path);
        $rows  = isset($sheet[0]) ? $sheet[0] : [];

        if(count($rows) < 2){
            return back()->withInput()->with('errors_list',['File is empty or has no data rows.']);
        }

        $map = $this->buildColumnMap($rows[0]);
        if($map === false){
            return back()->withInput()->with('errors_list',
                ['Header must contain the columns: CtnNo, PartNo, QUANTITY.']);
        }

        $errors = $this->validateLines($rows, $map);
        if(!empty($errors)){
            // Stop the whole upload and show the problem rows.
            return back()->withInput()->with('errors_list',$errors);
        }

        // --- All good: create header + lines (bulk, fast) -------------
        @ini_set('max_execution_time', 300);
        @ini_set('memory_limit', '512M');

        $uid  = Auth::user()->id;
        $now  = date('Y-m-d H:i:s');
        $tags = $request->tags ? implode(',', (array)$request->tags) : null;

        $count = 0; $qtysum = 0;

        DB::transaction(function() use ($request,$rows,$map,$tags,$uid,$now,&$count,&$qtysum){
            $container = new Container;
            $container->vendor_id        = $request->vendor_id;
            $container->vendor_doc_no    = $request->vendor_doc_no;
            $container->container_no     = $request->container_no;
            $container->mode_of_shipment = $request->mode_of_shipment;
            $container->shipment_doc_no  = $request->shipment_doc_no;
            $container->received_date    = $request->received_date ?: date('Y-m-d');
            $container->note             = $request->note;
            $container->po_no            = $request->po_no;
            $container->sa_no            = $request->sa_no;
            $container->remarks          = $request->remarks;
            $container->note1            = $request->note1;
            $container->note2            = $request->note2;
            $container->note3            = $request->note3;
            $container->tags             = $tags;
            $container->status           = 'New';
            $container->created_by       = $uid;
            $container->updated_by       = $uid;
            $container->save();

            // Initial status log entry.
            $slog = new ContainerStatusLog;
            $slog->container_id = $container->id;
            $slog->from_status  = '';
            $slog->to_status    = 'New';
            $slog->note         = 'Container created';
            $slog->created_by   = $uid;
            $slog->save();

            // Build all line rows in memory, then bulk-insert in chunks.
            $lineRows = [];
            foreach($rows as $i => $value){
                if($i == 0){ continue; } // header
                if($this->rowIsEmpty($value)){ continue; }
                $qtysum += (int)$value[$map['quantity']];
                $count++;
                $lineRows[] = [
                    'container_id' => $container->id,
                    'box_no'       => $this->cleanTrim($value[$map['ctnno']]),
                    'part_no'      => $this->cleanTrim($value[$map['partno']]),
                    'description'  => isset($map['description']) ? $value[$map['description']] : null,
                    'quantity'     => $value[$map['quantity']],
                    'brand'        => isset($map['brand'])    ? $value[$map['brand']]    : null,
                    'location'     => isset($map['location']) ? $value[$map['location']] : null,
                    'cus_id'       => isset($map['cusid'])    ? $value[$map['cusid']]    : null,
                    'note1'        => isset($map['note1'])    ? $value[$map['note1']]    : null,
                    'note2'        => isset($map['note2'])    ? $value[$map['note2']]    : null,
                    'note3'        => isset($map['note'])     ? $value[$map['note']]     : null,
                    'created_by'   => $uid,
                    'updated_by'   => $uid,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
            }
            foreach(array_chunk($lineRows,1000) as $c){ DB::table('container_lines')->insert($c); }

            $log = new Uploadlog;
            $log->name         = 'Container Upload';
            $log->filecount    = $count;
            $log->successcount = $count;
            $log->failcount    = 0;
            $log->message      = 'Container #'.$container->id.' | Lines: '.$count.' | Qty: '.$qtysum;
            $log->created_by   = $uid;
            $log->save();
        });

        return redirect('/container')->with('alert',
            '<strong>'.$count.'</strong> lines uploaded successfully. Total Quantity: <strong>'.$qtysum.'</strong>');
    }

    public function destroy($id)
    {
        $data = Container::find($id);
        $data->delete_status = 1;
        $data->updated_by = Auth::user()->id;
        $data->save();
        return redirect('/container');
    }

    /* ---------------------------------------------------------------
     |  Status workflow (New -> In Progress -> Completed)
     * --------------------------------------------------------------- */

    public function statusUpdate(Request $request)
    {
        $request->validate([
            'container_id' => 'required',
            'status'       => 'required',
        ]);
        $statuses = $this->containerStatuses();
        $container = Container::findOrFail($request->container_id);
        $new = $request->status;

        if(!in_array($new, $statuses)){
            return back()->with('error','Invalid status.');
        }
        $curIdx = array_search($container->status, $statuses);
        if($curIdx === false){ $curIdx = 0; }
        $newIdx = array_search($new, $statuses);
        if($newIdx <= $curIdx){
            return back()->with('error','Status can only move forward.');
        }

        $old = $container->status;
        $container->status     = $new;
        $container->updated_by = Auth::user()->id;
        $container->save();

        $log = new ContainerStatusLog;
        $log->container_id = $container->id;
        $log->from_status  = $old;
        $log->to_status    = $new;
        $log->note         = $request->note;
        $log->created_by   = Auth::user()->id;
        $log->save();

        return back()->with('alert','Status updated to '.$new.'.');
    }

    /* ---------------------------------------------------------------
     |  Edit container header + lines
     |  (line editing is locked once any receiving entry exists)
     * --------------------------------------------------------------- */

    public function edit(Request $request, $id)
    {
        $container = Container::with('vendor')->findOrFail($id);
        $vendors = Vendor::where('delete_status','0')->orderBy('name','asc')->get();
        $tags = Tag::where('delete_status','0')->orderBy('name','asc')->get();

        $box_no  = $request->box_no ?: '';
        $part_no = $request->part_no ?: '';
        $linesQ = ContainerLine::where('container_id',$id)->where('delete_status','0');
        if(!empty($box_no)){ $linesQ->where('box_no','like','%'.$box_no.'%'); }
        if(!empty($part_no)){ $linesQ->where('part_no','like','%'.$part_no.'%'); }
        $lines = $linesQ->orderBy('box_no','asc')->orderBy('part_no','asc')
            ->paginate(100)->appends($request->query());

        $started = $this->hasReceipts($id);
        // Admins can always edit lines; everyone else is locked once receiving starts.
        $locked = $started && Auth::user()->role != 'admin';
        $selectedTags = $container->tags ? explode(',', $container->tags) : [];
        $title = "Edit Container";
        return view('container.edit',
            compact('title','container','vendors','tags','lines','locked','started','selectedTags','box_no','part_no'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'           => 'required',
            'vendor_id'    => 'required',
            'container_no' => 'required',
        ]);

        $container = Container::findOrFail($request->id);
        $container->vendor_id        = $request->vendor_id;
        $container->vendor_doc_no    = $request->vendor_doc_no;
        $container->container_no     = $request->container_no;
        $container->mode_of_shipment = $request->mode_of_shipment;
        $container->shipment_doc_no  = $request->shipment_doc_no;
        $container->received_date    = $request->received_date ?: $container->received_date;
        $container->note             = $request->note;
        $container->po_no            = $request->po_no;
        $container->sa_no            = $request->sa_no;
        $container->remarks          = $request->remarks;
        $container->note1            = $request->note1;
        $container->note2            = $request->note2;
        $container->note3            = $request->note3;
        $container->tags             = $request->tags ? implode(',', (array)$request->tags) : null;
        $container->updated_by       = Auth::user()->id;
        $container->save();

        return redirect('/container')->with('alert','Container updated successfully.');
    }

    public function lineUpdate(Request $request)
    {
        $line = ContainerLine::findOrFail($request->id);
        if($this->hasReceipts($line->container_id) && Auth::user()->role != 'admin'){
            if($request->ajax() || $request->wantsJson()){
                return response()->json(['status'=>'error','message'=>'Lines are locked because receiving has already started.'],422);
            }
            return back()->with('error','Lines are locked because receiving has already started for this container.');
        }
        $request->validate([
            'part_no'  => 'required',
            'quantity' => 'required|integer|min:1',
        ]);
        $line->box_no      = $request->box_no;
        $line->part_no     = $request->part_no;
        $line->description = $request->description;
        $line->quantity    = $request->quantity;
        $line->brand       = $request->brand;
        $line->location    = $request->location;
        $line->updated_by  = Auth::user()->id;
        $line->save();

        if($request->ajax() || $request->wantsJson()){
            return response()->json([
                'status'       => 'success',
                'message'      => 'Line updated.',
                'received_qty' => $line->received_qty,
                'balance'      => $line->quantity - $line->received_qty,
                'over'         => $line->received_qty > $line->quantity,
            ]);
        }
        return back()->with('alert','Line updated.');
    }

    public function lineDestroy(Request $request, $id)
    {
        $line = ContainerLine::findOrFail($id);
        if($this->hasReceipts($line->container_id) && Auth::user()->role != 'admin'){
            if($request->ajax() || $request->wantsJson()){
                return response()->json(['status'=>'error','message'=>'Lines are locked because receiving has already started.'],422);
            }
            return back()->with('error','Lines are locked because receiving has already started for this container.');
        }
        $line->delete_status = 1;
        $line->updated_by = Auth::user()->id;
        $line->save();

        if($request->ajax() || $request->wantsJson()){
            return response()->json(['status'=>'success','message'=>'Line deleted.']);
        }
        return back()->with('alert','Line deleted.');
    }

    /* ---------------------------------------------------------------
     |  Cartons (CtnNo / Box) drill-down
     * --------------------------------------------------------------- */

    public function cartons(Request $request, $id)
    {
        $container = Container::with('vendor')->findOrFail($id);
        $title = "Container Boxes";
        $box_no  = $request->box_no ?: '';
        $part_no = $request->part_no ?: '';

        $boxesQ = ContainerLine::where('container_id',$id)->where('delete_status','0');
        if(!empty($box_no)){
            $boxesQ->where('box_no','like','%'.$box_no.'%');
        }
        if(!empty($part_no)){
            // Only boxes (CtnNo) that contain the searched part number.
            $matchBoxes = ContainerLine::where('container_id',$id)->where('delete_status','0')
                ->where('part_no','like','%'.$part_no.'%')->distinct()->pluck('box_no')->all();
            $boxesQ->whereIn('box_no', $matchBoxes ?: ['__none__']);
        }
        $boxes = $boxesQ->selectRaw('box_no, COUNT(*) as line_count, SUM(quantity) as total_qty, SUM(received_qty) as total_received, SUM(LEAST(received_qty, quantity)) as capped_received')
            ->groupBy('box_no')->orderBy('box_no','asc')->get();

        $uploadlogs = ContainerUploadLog::with('creator')->where('container_id',$id)
            ->orderBy('id','Desc')->get();
        $statuses = $this->containerStatuses();
        $statuslogs = ContainerStatusLog::with('creator')->where('container_id',$id)
            ->orderBy('id','Desc')->get();
        return view('container.cartons',compact('title','container','boxes','uploadlogs','statuses','statuslogs','box_no','part_no'));
    }

    public function carton($id, $box)
    {
        $box = urldecode($box);
        $container = Container::with('vendor')->findOrFail($id);
        $title = "Box ".$box;
        $lines = ContainerLine::with(['receiptlogs.creator','receiptlogs.updater'])->where('container_id',$id)
            ->where('box_no',$box)->where('delete_status','0')->orderBy('part_no','asc')->get();
        $extras = ContainerExtraLine::where('container_id',$id)->where('box_no',$box)
            ->orderBy('id','Desc')->get();
        return view('container.lines',compact('title','container','lines','extras','box'));
    }

    /* ---------------------------------------------------------------
     |  Received quantity  (single entry + bulk upload)
     * --------------------------------------------------------------- */

    public function receiptStore(Request $request)
    {
        $request->validate([
            'container_line_id' => 'required',
            'received_qty'      => 'required|integer|min:1',
        ]);

        $line = ContainerLine::findOrFail($request->container_line_id);

        $log = new ContainerReceiptLog;
        $log->container_id      = $line->container_id;
        $log->container_line_id = $line->id;
        $log->box_no            = $line->box_no;
        $log->part_no           = $line->part_no;
        $log->received_qty      = $request->received_qty;
        $log->source            = 'entry';
        $log->note              = $request->note;
        $log->remarks           = $request->remarks;
        $log->created_by        = Auth::user()->id;
        $log->save();

        $this->recalcLineReceived($line);

        if($request->ajax() || $request->wantsJson()){
            return response()->json([
                'status'       => 'success',
                'received_qty' => $line->received_qty,
                'quantity'     => $line->quantity,
                'balance'      => $line->quantity - $line->received_qty,
                'over'         => $line->received_qty > $line->quantity,
                'entry'        => [
                    'id'              => $log->id,
                    'received_qty'    => $log->received_qty,
                    'source'          => $log->source,
                    'note'            => $log->note,
                    'remarks'         => $log->remarks,
                    'created_by_name' => optional(Auth::user())->name,
                    'created_at'      => date('d-m-Y H:i:s', strtotime($log->created_at)),
                ],
            ]);
        }

        return redirect('/container/'.$line->container_id.'/carton/'.urlencode($line->box_no))
            ->with('alert','Received quantity added.');
    }

    // Download a template pre-filled with current lines + a blank "Received Qty" column.
    public function receiptFormat($id)
    {
        $lines = ContainerLine::where('container_id',$id)->where('delete_status','0')
            ->orderBy('box_no','asc')->orderBy('part_no','asc')->get();
        $headers = ['CtnNo','PartNo','Description','QUANTITY','Already Received','Received Qty','Note','Remarks'];
        return $this->streamCsv('received_format_container_'.$id.'.csv',$headers,
            $lines->map(function($l){
                // "Already Received" is a read-only reference; fill Received Qty / Note / Remarks.
                return [$l->box_no,$l->part_no,$l->description,$l->quantity,$l->received_qty,'','',''];
            })->toArray());
    }

    public function receiptUpload(Request $request)
    {
        $request->validate(['container_id'=>'required','import_file'=>'required|file']);
        @ini_set('max_execution_time', 300);
        @ini_set('memory_limit', '512M');

        $containerId = $request->container_id;
        $uid = Auth::user()->id;

        $sheet = Excel::toArray([], $request->file('import_file'));
        $rows  = isset($sheet[0]) ? $sheet[0] : [];

        // Create the upload log first so detail rows can reference it.
        $uploadLog = new ContainerUploadLog;
        $uploadLog->container_id = $containerId;
        $uploadLog->type         = 'received';
        $uploadLog->file_name    = $request->file('import_file')->getClientOriginalName();
        $uploadLog->created_by   = $uid;
        $uploadLog->save();
        $uploadLogId = $uploadLog->id;

        // Preload all lines ONCE, keyed by box|part (avoids a query per row).
        $map = ContainerLine::where('container_id',$containerId)->where('delete_status','0')
            ->get(['id','box_no','part_no'])
            ->keyBy(function($l){ return $l->box_no.'|'.$l->part_no; });

        $now = date('Y-m-d H:i:s');
        $receiptRows = []; $extraRows = []; $unmatchedRows = []; $lineAdds = [];
        $ok = 0; $extra = 0; $skipped = 0;

        foreach($rows as $i => $value){
            if($i == 0){ continue; } // header: CtnNo, PartNo, Description, QUANTITY, Already Received, Received Qty, Note, Remarks
            if($this->rowIsEmpty($value)){ continue; }
            $rowNo = $i + 1;
            $box     = isset($value[0]) ? $this->cleanTrim($value[0]) : '';
            $part    = isset($value[1]) ? $this->cleanTrim($value[1]) : '';
            $recd    = isset($value[5]) ? $value[5] : null;              // Received Qty (col 5; col 4 = Already Received, ignored)
            $note    = isset($value[6]) ? $value[6] : null;
            $remarks = isset($value[7]) ? $value[7] : null;

            // Blank received qty = unfilled template row -> ignore silently.
            if($recd === null || $recd === ''){ continue; }

            $reason = '';
            if($part === ''){ $reason = 'Part No missing'; }
            elseif(!is_numeric($recd) || $recd <= 0 || floor($recd) != $recd){ $reason = 'Invalid quantity (whole number > 0)'; }

            if($reason !== ''){
                $unmatchedRows[] = ['upload_log_id'=>$uploadLogId,'container_id'=>$containerId,'type'=>'received','row_no'=>$rowNo,'box_no'=>$box,'part_no'=>$part,'qty'=>$recd,'reason'=>$reason];
                $skipped++;
                continue;
            }

            $recd = (int)$recd;
            $line = $map->get($box.'|'.$part);

            if($line){
                $receiptRows[] = ['upload_log_id'=>$uploadLogId,'container_id'=>$containerId,'container_line_id'=>$line->id,'box_no'=>$box,'part_no'=>$part,'received_qty'=>$recd,'source'=>'upload','note'=>$note,'remarks'=>$remarks,'created_by'=>$uid,'created_at'=>$now,'updated_at'=>$now];
                if(!isset($lineAdds[$line->id])){ $lineAdds[$line->id] = 0; }
                $lineAdds[$line->id] += $recd;
                $ok++;
            }else{
                // Part not in the list -> keep it separately AND record as unmatched.
                $extraRows[] = ['container_id'=>$containerId,'box_no'=>$box,'part_no'=>$part,'description'=>isset($value[2])?$value[2]:null,'received_qty'=>$recd,'note'=>$note,'source'=>'received-upload','created_by'=>$uid,'created_at'=>$now,'updated_at'=>$now];
                $unmatchedRows[] = ['upload_log_id'=>$uploadLogId,'container_id'=>$containerId,'type'=>'received','row_no'=>$rowNo,'box_no'=>$box,'part_no'=>$part,'qty'=>$recd,'reason'=>'Not in container list (kept in Extra Parts)'];
                $extra++;
            }
        }

        DB::transaction(function() use ($receiptRows,$extraRows,$unmatchedRows,$lineAdds,$uid){
            foreach(array_chunk($receiptRows,1000) as $c){ DB::table('container_receipt_logs')->insert($c); }
            foreach(array_chunk($extraRows,1000) as $c){ DB::table('container_extra_lines')->insert($c); }
            foreach(array_chunk($unmatchedRows,1000) as $c){ DB::table('container_upload_unmatched')->insert($c); }

            if(!empty($lineAdds)){
                $ids = array_keys($lineAdds);
                $case = 'CASE id';
                foreach($lineAdds as $id => $add){ $case .= ' WHEN '.(int)$id.' THEN received_qty + '.(int)$add; }
                $case .= ' END';
                DB::table('container_lines')->whereIn('id',$ids)
                    ->update(['received_qty'=>DB::raw($case), 'updated_by'=>$uid]);
            }
        });

        $uploadLog->matched    = $ok;
        $uploadLog->extra      = $extra;
        $uploadLog->unmatched  = $skipped;
        $uploadLog->total_rows = $ok + $extra + $skipped;
        $uploadLog->message    = "Matched: $ok, Extra (not in list): $extra, Skipped: $skipped";
        $uploadLog->save();

        return back()->with('alert',
            "Received upload done. Matched: $ok, Extra (not in list): $extra, Skipped: $skipped.");
    }

    // Edit a single received-entry log; the line total/balance is recalculated.
    public function receiptLogUpdate(Request $request)
    {
        $request->validate([
            'id'           => 'required',
            'received_qty' => 'required|integer|min:1',
        ]);
        $log = ContainerReceiptLog::findOrFail($request->id);
        $log->received_qty = $request->received_qty;
        $log->note         = $request->note;
        $log->remarks      = $request->remarks;
        $log->updated_by   = Auth::user()->id;
        $log->save();

        $line = ContainerLine::findOrFail($log->container_line_id);
        $this->recalcLineReceived($line);

        if($request->ajax() || $request->wantsJson()){
            return response()->json([
                'status'       => 'success',
                'received_qty' => $line->received_qty,
                'quantity'     => $line->quantity,
                'balance'      => $line->quantity - $line->received_qty,
                'over'         => $line->received_qty > $line->quantity,
                'log'          => [
                    'id'              => $log->id,
                    'received_qty'    => $log->received_qty,
                    'note'            => $log->note,
                    'remarks'         => $log->remarks,
                    'updated_by_name' => optional(Auth::user())->name,
                    'updated_at'      => date('d-m-Y H:i:s', strtotime($log->updated_at)),
                ],
            ]);
        }
        return back()->with('alert','Received entry updated.');
    }

    // Delete a received-entry log; the line total/balance is recalculated.
    public function receiptLogDestroy(Request $request, $id)
    {
        $log = ContainerReceiptLog::findOrFail($id);
        $lineId = $log->container_line_id;
        $log->delete();

        $line = ContainerLine::findOrFail($lineId);
        $this->recalcLineReceived($line);

        if($request->ajax() || $request->wantsJson()){
            return response()->json([
                'status'       => 'success',
                'received_qty' => $line->received_qty,
                'quantity'     => $line->quantity,
                'balance'      => $line->quantity - $line->received_qty,
                'over'         => $line->received_qty > $line->quantity,
            ]);
        }
        return back()->with('alert','Received entry deleted.');
    }

    /* ---------------------------------------------------------------
     |  CRM quantity import
     * --------------------------------------------------------------- */

    public function crmFormat($id)
    {
        $lines = ContainerLine::where('container_id',$id)->where('delete_status','0')
            ->orderBy('box_no','asc')->orderBy('part_no','asc')->get();
        $headers = ['CtnNo','PartNo','Description','QUANTITY','CRM Qty'];
        return $this->streamCsv('crm_format_container_'.$id.'.csv',$headers,
            $lines->map(function($l){
                return [$l->box_no,$l->part_no,$l->description,$l->quantity,$l->crm_qty];
            })->toArray());
    }

    public function crmUpload(Request $request)
    {
        $request->validate(['container_id'=>'required','import_file'=>'required|file']);
        @ini_set('max_execution_time', 300);
        @ini_set('memory_limit', '512M');

        $containerId = $request->container_id;
        $uid = Auth::user()->id;

        $sheet = Excel::toArray([], $request->file('import_file'));
        $rows  = isset($sheet[0]) ? $sheet[0] : [];

        $uploadLog = new ContainerUploadLog;
        $uploadLog->container_id = $containerId;
        $uploadLog->type         = 'crm';
        $uploadLog->file_name    = $request->file('import_file')->getClientOriginalName();
        $uploadLog->created_by   = $uid;
        $uploadLog->save();
        $uploadLogId = $uploadLog->id;

        // Preload all lines ONCE, keyed by box|part.
        $map = ContainerLine::where('container_id',$containerId)->where('delete_status','0')
            ->get(['id','box_no','part_no'])
            ->keyBy(function($l){ return $l->box_no.'|'.$l->part_no; });

        $unmatchedRows = []; $crmUpdates = [];
        $ok = 0; $unmatched = 0;

        foreach($rows as $i => $value){
            if($i == 0){ continue; } // header: CtnNo, PartNo, Description, QUANTITY, CRM Qty
            if($this->rowIsEmpty($value)){ continue; }
            $rowNo = $i + 1;
            $box  = isset($value[0]) ? $this->cleanTrim($value[0]) : '';
            $part = isset($value[1]) ? $this->cleanTrim($value[1]) : '';
            $crm  = isset($value[4]) ? $value[4] : null;

            // Blank CRM qty = unfilled template row -> ignore silently.
            if($crm === null || $crm === ''){ continue; }

            $reason = '';
            if($part === ''){ $reason = 'Part No missing'; }
            elseif(!is_numeric($crm) || floor($crm) != $crm){ $reason = 'Invalid CRM quantity (whole number)'; }

            if($reason !== ''){
                $unmatchedRows[] = ['upload_log_id'=>$uploadLogId,'container_id'=>$containerId,'type'=>'crm','row_no'=>$rowNo,'box_no'=>$box,'part_no'=>$part,'qty'=>$crm,'reason'=>$reason];
                $unmatched++;
                continue;
            }

            $crm = (int)$crm;
            $line = $map->get($box.'|'.$part);

            if($line){
                $crmUpdates[$line->id] = $crm;
                $ok++;
            }else{
                $unmatchedRows[] = ['upload_log_id'=>$uploadLogId,'container_id'=>$containerId,'type'=>'crm','row_no'=>$rowNo,'box_no'=>$box,'part_no'=>$part,'qty'=>$crm,'reason'=>'No matching line (Box + Part not found)'];
                $unmatched++;
            }
        }

        DB::transaction(function() use ($unmatchedRows,$crmUpdates,$uid){
            foreach(array_chunk($unmatchedRows,1000) as $c){ DB::table('container_upload_unmatched')->insert($c); }
            if(!empty($crmUpdates)){
                $ids = array_keys($crmUpdates);
                $case = 'CASE id';
                foreach($crmUpdates as $id => $v){ $case .= ' WHEN '.(int)$id.' THEN '.(int)$v; }
                $case .= ' END';
                DB::table('container_lines')->whereIn('id',$ids)
                    ->update(['crm_qty'=>DB::raw($case), 'updated_by'=>$uid]);
            }
        });

        $uploadLog->matched    = $ok;
        $uploadLog->extra      = 0;
        $uploadLog->unmatched  = $unmatched;
        $uploadLog->total_rows = $ok + $unmatched;
        $uploadLog->message    = "Updated: $ok, Unmatched: $unmatched";
        $uploadLog->save();

        return back()->with('alert',
            "CRM upload done. Updated: $ok, Unmatched: $unmatched.");
    }

    // Detail view of one upload: matched (received only) + unmatched with reasons.
    public function uploadLogView($id)
    {
        $log = ContainerUploadLog::with('creator')->findOrFail($id);
        $container = Container::with('vendor')->find($log->container_id);
        $matched = $log->type == 'received'
            ? ContainerReceiptLog::with('creator')->where('upload_log_id',$id)->orderBy('id','asc')->get()
            : collect();
        $unmatched = ContainerUploadUnmatched::where('upload_log_id',$id)->orderBy('row_no','asc')->get();
        $title = "Upload Detail";
        return view('container.uploadlog',compact('title','log','container','matched','unmatched'));
    }

    /* ---------------------------------------------------------------
     |  Extra parts (received but not in the list) - manual add
     * --------------------------------------------------------------- */

    public function extraStore(Request $request)
    {
        $request->validate([
            'container_id' => 'required',
            'part_no'      => 'required',
            'received_qty' => 'required|integer|min:1',
        ]);

        $ex = new ContainerExtraLine;
        $ex->container_id = $request->container_id;
        $ex->box_no       = $request->box_no;
        $ex->part_no      = $request->part_no;
        $ex->description  = $request->description;
        $ex->received_qty = $request->received_qty;
        $ex->brand        = $request->brand;
        $ex->location     = $request->location;
        $ex->note         = $request->note;
        $ex->source       = 'entry';
        $ex->created_by   = Auth::user()->id;
        $ex->save();

        $back = '/container/'.$request->container_id.'/carton/'.urlencode($request->box_no);
        return redirect($back)->with('alert','Extra part added.');
    }

    /* ---------------------------------------------------------------
     |  Reports
     * --------------------------------------------------------------- */

    // Container-wise summary: one row per container with line totals.
    public function containerReport(Request $request)
    {
        $title = "Container Wise Report";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if(!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $vendors = Vendor::where('delete_status','0')->orderBy('name','asc')->get();
        $vendor_id    = $request->vendor_id ?: '';
        $mode         = $request->mode_of_shipment ?: '';
        $container_no = $request->container_no ?: '';

        $indexes = Container::with('vendor')->where('delete_status','0')
            ->whereBetween('received_date',[$fromdate, $todate])
            ->withCount('lines')
            ->withSum('lines','quantity')
            ->withSum('lines','received_qty')
            ->withSum('lines','crm_qty');
        if(!empty($vendor_id)){ $indexes->where('vendor_id',$vendor_id); }
        if(!empty($mode)){ $indexes->where('mode_of_shipment',$mode); }
        if(!empty($container_no)){ $indexes->where('container_no','like','%'.$container_no.'%'); }
        $indexes = $indexes->orderBy('id','Desc')->get();

        return view('container.report_container',
            compact('title','indexes','fromdate','todate','vendors','vendor_id','mode','container_no'));
    }

    // Box-wise (line level) report with multiple filters.
    public function boxReport(Request $request)
    {
        $title = "Box Wise Report";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if(!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $vendors = Vendor::where('delete_status','0')->orderBy('name','asc')->get();
        $containers = Container::where('delete_status','0')->orderBy('id','Desc')->get();
        $vendor_id    = $request->vendor_id ?: '';
        $container_id = $request->container_id ?: '';
        $box_no       = $request->box_no ?: '';
        $part_no      = $request->part_no ?: '';
        $brand        = $request->brand ?: '';

        $indexes = ContainerLine::with(['container.vendor'])->where('delete_status','0')
            ->whereHas('container', function($q) use($fromdate,$todate,$vendor_id){
                $q->where('delete_status','0')->whereBetween('received_date',[$fromdate, $todate]);
                if(!empty($vendor_id)){ $q->where('vendor_id',$vendor_id); }
            });
        if(!empty($container_id)){ $indexes->where('container_id',$container_id); }
        if(!empty($box_no)){ $indexes->where('box_no','like','%'.$box_no.'%'); }
        if(!empty($part_no)){ $indexes->where('part_no','like','%'.$part_no.'%'); }
        if(!empty($brand)){ $indexes->where('brand','like','%'.$brand.'%'); }
        $indexes = $indexes->orderBy('container_id','Desc')->orderBy('box_no','asc')->orderBy('part_no','asc')->get();

        return view('container.report_box',
            compact('title','indexes','fromdate','todate','vendors','vendor_id','containers','container_id','box_no','part_no','brand'));
    }

    // Received entries report (each single/upload received entry with note + remarks).
    public function receivedEntriesReport(Request $request)
    {
        $title = "Received Entries Report";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if(!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $vendors = Vendor::where('delete_status','0')->orderBy('name','asc')->get();
        $containers = Container::where('delete_status','0')->orderBy('id','Desc')->get();
        $vendor_id    = $request->vendor_id ?: '';
        $container_id = $request->container_id ?: '';
        $box_no       = $request->box_no ?: '';
        $part_no      = $request->part_no ?: '';

        $indexes = ContainerReceiptLog::with(['container.vendor','creator'])
            ->whereBetween('created_at',[$fromdate, $todate1]);
        if(!empty($vendor_id)){
            $indexes->whereHas('container', function($q) use($vendor_id){ $q->where('vendor_id',$vendor_id); });
        }
        if(!empty($container_id)){ $indexes->where('container_id',$container_id); }
        if(!empty($box_no)){ $indexes->where('box_no','like','%'.$box_no.'%'); }
        if(!empty($part_no)){ $indexes->where('part_no','like','%'.$part_no.'%'); }
        $indexes = $indexes->orderBy('id','Desc')->get();

        return view('container.report_received',
            compact('title','indexes','fromdate','todate','vendors','vendor_id','containers','container_id','box_no','part_no'));
    }

    // Extra parts report (received but not in the container list).
    public function extraReport(Request $request)
    {
        $title = "Extra Parts Report";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if(!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $vendors = Vendor::where('delete_status','0')->orderBy('name','asc')->get();
        $containers = Container::where('delete_status','0')->orderBy('id','Desc')->get();
        $vendor_id    = $request->vendor_id ?: '';
        $container_id = $request->container_id ?: '';
        $box_no       = $request->box_no ?: '';
        $part_no      = $request->part_no ?: '';
        $brand        = $request->brand ?: '';

        $indexes = ContainerExtraLine::with(['container.vendor','creator'])
            ->whereBetween('created_at',[$fromdate, $todate1]);
        if(!empty($vendor_id)){
            $indexes->whereHas('container', function($q) use($vendor_id){ $q->where('vendor_id',$vendor_id); });
        }
        if(!empty($container_id)){ $indexes->where('container_id',$container_id); }
        if(!empty($box_no)){ $indexes->where('box_no','like','%'.$box_no.'%'); }
        if(!empty($part_no)){ $indexes->where('part_no','like','%'.$part_no.'%'); }
        if(!empty($brand)){ $indexes->where('brand','like','%'.$brand.'%'); }
        $indexes = $indexes->orderBy('id','Desc')->get();

        return view('container.report_extra',
            compact('title','indexes','fromdate','todate','vendors','vendor_id','containers','container_id','box_no','part_no','brand'));
    }

    // Line-level report (Container + Box + Line) with server-side pagination.
    public function containerBoxLineReport(Request $request)
    {
        $title = "Container Box Lines Report";
        $f = $this->boxLineFilters($request);
        $vendors = Vendor::where('delete_status','0')->orderBy('name','asc')->get();
        $containers = Container::where('delete_status','0')->orderBy('id','Desc')->get();

        $indexes = $this->boxLineQuery($f)
            ->orderBy('container_id','Desc')->orderBy('box_no','asc')->orderBy('part_no','asc')
            ->paginate(50)->appends($request->query());

        return view('container.report_box_line', array_merge($f, [
            'title' => $title, 'indexes' => $indexes,
            'vendors' => $vendors, 'containers' => $containers,
        ]));
    }

    // Full filtered export (not just the current page).
    public function containerBoxLineExport(Request $request)
    {
        $f = $this->boxLineFilters($request);
        $lines = $this->boxLineQuery($f)
            ->orderBy('container_id','Desc')->orderBy('box_no','asc')->orderBy('part_no','asc')->get();

        $headers = ['Container No','Vendor','Received Date','Mode','Box (CtnNo)','Part No','Description','Brand','Location','Qty','CRM Qty','Received','Balance','Note','Remarks'];
        $rows = $lines->map(function($l){
            return [
                optional($l->container)->container_no,
                optional(optional($l->container)->vendor)->name,
                optional($l->container)->received_date ? date('d-m-Y', strtotime($l->container->received_date)) : '',
                optional($l->container)->mode_of_shipment,
                $l->box_no, $l->part_no, $l->description, $l->brand, $l->location,
                $l->quantity, $l->crm_qty, $l->received_qty, $l->quantity - $l->received_qty,
                $l->receiptlogs->pluck('note')->filter()->implode('; '),
                $l->receiptlogs->pluck('remarks')->filter()->implode('; '),
            ];
        })->toArray();

        return $this->streamCsv('container_box_lines_report.csv',$headers,$rows);
    }

    // Parses the shared filter set for the box-line report.
    private function boxLineFilters(Request $request)
    {
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if(!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        return [
            'fromdate'     => $fromdate,
            'todate'       => $todate,
            'vendor_id'    => $request->vendor_id ?: '',
            'container_id' => $request->container_id ?: '',
            'mode'         => $request->mode_of_shipment ?: '',
            'box_no'       => $request->box_no ?: '',
            'part_no'      => $request->part_no ?: '',
        ];
    }

    // Builds the filtered ContainerLine query used by both report + export.
    private function boxLineQuery($f)
    {
        $q = ContainerLine::with(['container.vendor','receiptlogs'])->where('delete_status','0')
            ->whereHas('container', function($c) use($f){
                $c->where('delete_status','0')->whereBetween('received_date',[$f['fromdate'], $f['todate']]);
                if(!empty($f['vendor_id'])){ $c->where('vendor_id',$f['vendor_id']); }
                if(!empty($f['mode'])){ $c->where('mode_of_shipment',$f['mode']); }
            });
        if(!empty($f['container_id'])){ $q->where('container_id',$f['container_id']); }
        if(!empty($f['box_no'])){ $q->where('box_no','like','%'.$f['box_no'].'%'); }
        if(!empty($f['part_no'])){ $q->where('part_no','like','%'.$f['part_no'].'%'); }
        return $q;
    }

    /* ---------------------------------------------------------------
     |  Helpers
     * --------------------------------------------------------------- */

    // Record one unmatched / skipped upload row with its reason.
    private function logUnmatched($uploadLogId,$containerId,$type,$rowNo,$box,$part,$qty,$reason)
    {
        $u = new ContainerUploadUnmatched;
        $u->upload_log_id = $uploadLogId;
        $u->container_id  = $containerId;
        $u->type          = $type;
        $u->row_no        = $rowNo;
        $u->box_no        = $box;
        $u->part_no       = $part;
        $u->qty           = $qty;
        $u->reason        = $reason;
        $u->save();
    }

    // Recalculate a line's received qty from the sum of its receipt logs.
    private function recalcLineReceived(ContainerLine $line)
    {
        $line->received_qty = ContainerReceiptLog::where('container_line_id',$line->id)->sum('received_qty');
        $line->updated_by   = Auth::user()->id;
        $line->save();
        return $line;
    }

    // The container status sequence (forward-only).
    private function containerStatuses()
    {
        return ['New','In Progress','Completed'];
    }

    // True once any received entry exists -> lines must not change anymore.
    private function hasReceipts($containerId)
    {
        return ContainerReceiptLog::where('container_id',$containerId)->exists();
    }

    // Maps required/optional column names to their numeric index in the sheet.
    private function buildColumnMap($header)
    {
        $norm = [];
        foreach($header as $idx => $name){
            $key = strtolower(preg_replace('/[^a-z0-9]/i','', (string)$name));
            $norm[$key] = $idx;
        }
        $map = [];
        $required = ['ctnno','partno','quantity'];
        foreach($required as $r){
            if(!isset($norm[$r])){ return false; }
            $map[$r] = $norm[$r];
        }
        foreach(['description','brand','location','cusid','note1','note2','note'] as $o){
            if(isset($norm[$o])){ $map[$o] = $norm[$o]; }
        }
        return $map;
    }

    // Returns an array of human-readable problems; empty array = valid.
    private function validateLines($rows, $map)
    {
        $errors = [];
        foreach($rows as $i => $value){
            if($i == 0){ continue; }         // header
            if($this->rowIsEmpty($value)){ continue; }
            $rowNo = $i + 1;                   // 1-based, matches the spreadsheet row

            $ctn = isset($value[$map['ctnno']])    ? $this->cleanTrim($value[$map['ctnno']])    : '';
            $part= isset($value[$map['partno']])   ? $this->cleanTrim($value[$map['partno']])   : '';
            $qty = isset($value[$map['quantity']]) ? $value[$map['quantity']]       : '';

            if($ctn === ''){  $errors[] = "Row $rowNo: CtnNo is required."; }
            if($part === ''){ $errors[] = "Row $rowNo: PartNo is required."; }
            if($qty === '' || $qty === null){
                $errors[] = "Row $rowNo: QUANTITY is required.";
            }elseif(!is_numeric($qty)){
                $errors[] = "Row $rowNo: QUANTITY must be a number.";
            }elseif($qty <= 0){
                $errors[] = "Row $rowNo: QUANTITY must be greater than 0.";
            }elseif(floor($qty) != $qty){
                $errors[] = "Row $rowNo: QUANTITY must be a whole number (no decimals).";
            }
        }
        return $errors;
    }

    // Trim leading/trailing whitespace INCLUDING non-breaking spaces (common in Excel).
    private function cleanTrim($v)
    {
        return preg_replace('/^[\s\x{00A0}]+|[\s\x{00A0}]+$/u', '', (string)$v);
    }

    private function rowIsEmpty($value)
    {
        foreach((array)$value as $cell){
            if(trim((string)$cell) !== ''){ return false; }
        }
        return true;
    }

    private function streamCsv($filename, $headers, $rows)
    {
        $callback = function() use ($headers,$rows){
            $f = fopen('php://output','w');
            fputcsv($f,$headers);
            foreach($rows as $r){ fputcsv($f,$r); }
            fclose($f);
        };
        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
