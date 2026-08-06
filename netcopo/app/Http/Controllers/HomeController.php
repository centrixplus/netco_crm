<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\Saleline;
use App\Models\Product;
use App\Models\User;
use App\Models\Status;
use App\Models\Brand;
use App\Models\Location;
use App\Models\Po;
use App\Models\Poline;
use App\Models\Vendor;
use App\Models\Currency;
use App\Models\Postatus;
use App\Models\Grn;
use App\Models\Grnline;
use App\Models\Pogrnlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

        
        return redirect('/dashboard/po');
        if(Auth::user()->role=='warehouse'){
                return redirect('/dashboard/warehouse');
        }
        // else if(Auth::user()->role=='warehouse'){
        //         return redirect('/dashboard/warehouse');
        // }
        else if(Auth::user()->role=='TL-01' || Auth::user()->role=='SM-01' || Auth::user()->role=='SC-01' || Auth::user()->role=='KO-01'|| Auth::user()->role=='KO-02'){
                return redirect('/receiptdashboard');
        }
        else if(Auth::user()->role=='PR'){
                return redirect('/purchaserequest');
        }
        $title='Dashboard';
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-t');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $customer=Customer::where('delete_status','0')->whereBetween('created_at',[$fromdate, $todate1])->count();
        $sales_count=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->get();

        $new=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status','New')->get();
        $processing=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status','Processing')->get();
        $delivered=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status','Delivered')->get();
        $los = Saleline::where('delete_status','0')->where('reason','PDC')->whereBetween('created_at',[$fromdate, $todate1])->get();
        $netco_los = Saleline::where('delete_status','0')->where('reason','NETCO')->whereBetween('created_at',[$fromdate, $todate1])->get();
        $users=User::get();
        $statuses=Status::where('delete_status','0')->orderBy('order','asc')->get();
        $brands = Brand::where('delete_status','0')->orderBy('name','asc')->get();
        $user_sales = view('sale.usersale',compact('users','fromdate','todate'))->render();
        $status_sales = view('sale.statussale',compact('statuses','fromdate','todate'))->render();
        $brand_sales = view('report.brand',compact('brands','fromdate','todate'))->render();
        
         return view('welcome',compact('title','fromdate','todate1','todate','customer','sales_count','new','processing','delivered','los','netco_los','user_sales','status_sales','brand_sales'));  
    }

    public function dashboardpo(Request $request)
    {
        $title='Dashboard';

        $fromdate = date('Y-m-01');
        $todate = date('Y-m-t');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));

        $vendor=Vendor::where('delete_status','0')->whereBetween('created_at',[$fromdate, $todate1])->count();

        $po=Po::where('delete_status','0')->whereBetween('po_date',[$fromdate, $todate])->get();

        $grn=Grn::where('delete_status','0')->whereBetween('grn_date',[$fromdate, $todate])->get();

        $poReport = Po::with('polines', 'vendor')
                    ->where('delete_status', 0)
                    ->whereHas('polines', function($q){
                        $q->where('balance_quantity', '>', 0); // only POs with balance
                    })
                    ->get()
                    ->map(function($po){
                        $balanceQty = $po->polines->sum('balance_quantity'); // total balance qty for this PO
                        $ageDays = Carbon::now()->diffInDays(Carbon::parse($po->po_date));

                        return [
                            'po_no' => $po->po_no,
                            'po_date' => $po->po_date,
                            'vendor' => $po->vendor->name ?? '-',
                            'balance_quantity' => $balanceQty,
                            'age_days' => $ageDays,
                        ];
                    });

        $poReport = $poReport->map(function($po){
                        $bucket = match(true){
                            $po['age_days'] <= 30 => '0-30',
                            $po['age_days'] <= 60 => '31-60',
                            $po['age_days'] <= 90 => '61-90',
                            default => '90+',
                        };
                        return array_merge($po, ['age_bucket' => $bucket]);
                    });

        return view('po_dashboard',compact('title','fromdate','todate1','todate','vendor','po','grn','poReport'));  
    }

    public function dashboard(Request $request)
    {
        $title='Dashboard';
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $customer=Customer::where('delete_status','0')->whereBetween('created_at',[$fromdate, $todate1])->count();

        $sales_count=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->count();
        $new_count=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status','New')->count();
        $processing_count=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status','Processing')->count();
        $delivered_count=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status','Delivered')->count();

        $sales_amount=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->sum('grand_total');
        $new_amount=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status','New')->sum('grand_total');
        $processing_amount=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status','Processing')->sum('grand_total');
        $delivered_amount=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status','Delivered')->sum('grand_total');

        $los_amount = Saleline::where('delete_status','0')->where('reason','!=','0')->where('reason','PDC')->whereBetween('created_at',[$fromdate, $todate1])->sum('l_price');
        $netco_los_amount = Saleline::where('delete_status','0')->where('reason','!=','0')->where('reason','NETCO')->whereBetween('created_at',[$fromdate, $todate1])->sum('l_price');
        $users=User::get();
        $brands = Brand::where('delete_status','0')->orderBy('name','asc')->get();
        $user_sales = view('sale.usersale',compact('users','fromdate','todate'))->render();
        $statuses=Status::where('delete_status','0')->orderBy('order','asc')->get();
        $status_sales = view('sale.statussale',compact('statuses','fromdate','todate'))->render();
        $brand_sales = view('report.brand',compact('brands','fromdate','todate'))->render();
        $sales_amount=number_format($sales_amount, 3, '.', '' );
        $los_amount=number_format($los_amount, 3, '.', '' );
        $netco_los_amount=number_format($netco_los_amount, 3, '.', '' );
        $delivered_amount=number_format($delivered_amount, 3, '.', '' );
        $processing_amount=number_format($processing_amount, 3, '.', '' );
        $new_amount=number_format($new_amount, 3, '.', '' );

         echo json_encode(array("new_count"=>$new_count,"sales_count"=>$sales_count,"processing_count"=>$processing_count,"delivered_count"=>$delivered_count,"sales_amount"=>$sales_amount,"new_amount"=>$new_amount,"processing_amount"=>$processing_amount,"delivered_amount"=>$delivered_amount,"los_amount"=>$los_amount,"netco_los_amount"=>$netco_los_amount,"user_sales"=>$user_sales,"status_sales"=>$status_sales,"brand_sales"=>$brand_sales));
    
    }

    public function warehouse(Request $request)
    {
        $title='Dashboard';
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-t');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $processing=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status_id','2')->get();
        $invoiced=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status_id','10')->get();
        $delivered=Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->where('status_id','3')->get();
        return view('warehouse.warehouse',compact('title','processing','invoiced','delivered','fromdate','todate'));  

    }

    public function warehouseOrders($customer_id='0', $location_id='0')
    {
        $title='Orders';
        $orders = Sale::where('delete_status','0');
        
        if($customer_id!=0){
            $orders=$orders->where('customer_id',$customer_id);
        }

        if($location_id!=0){
          
            $orders=$orders->whereHas('customer', function($q) use ($location_id)
            {
                $q->where('location_id',$location_id);

            });
        }

        $orders=$orders->get();
        return view('warehouse.orders',compact('title','orders'));  

    }

   

    public function dashboardpoNew(Request $request)
    {
        $title = "Dashboard";

        $fromdate = date("Y-m-01");
        $todate = date("Y-m-t");
        if (!empty($request->fromdate) && !empty($request->todate)) {
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        // Handle GET parameters for quick filters
        if ($request->has("from") && $request->has("to")) {
            $fromdate = $request->from;
            $todate = $request->to;
        }
        $todate1 = date("Y-m-d", strtotime("+1 day", strtotime($todate)));

        $vendor = Vendor::where("delete_status", "0")->whereBetween("created_at", [$fromdate, $todate1])->count();

        $po = Po::with(["polines", "vendor"])->where("delete_status", "0")->whereBetween("po_date", [$fromdate, $todate])->get();

        $grn = Grn::where("delete_status", "0")->whereBetween("grn_date", [$fromdate, $todate])->get();

        $poReport = Po::with("polines", "vendor")
            ->where("delete_status", 0)
            ->whereHas("polines", function ($q) {
                $q->where("balance_quantity", ">", 0);
            })
            ->get()
            ->map(function ($po) {
                $balanceQty = $po->polines->sum("balance_quantity");
                $ageDays = Carbon::now()->diffInDays(Carbon::parse($po->po_date));

                return [
                    "po_no" => $po->po_no,
                    "po_date" => $po->po_date,
                    "vendor" => $po->vendor->name ?? "-",
                    "balance_quantity" => $balanceQty,
                    "age_days" => $ageDays,
                ];
            });

        $poReport = $poReport->map(function ($po) {
            $bucket = match (true) {
                $po["age_days"] <= 30 => "0-30",
                $po["age_days"] <= 60 => "31-60",
                $po["age_days"] <= 90 => "61-90",
                default => "90+",
            };
            return array_merge($po, ["age_bucket" => $bucket]);
        });

        return view("po_dashboard_new", compact("title", "fromdate", "todate1", "todate", "vendor", "po", "grn", "poReport"));
    }


    public function getPoDetails($po_no)
    {
        $po = \App\Models\Po::with(["polines.product", "vendor"])
            ->where("po_no", $po_no)
            ->where("delete_status", 0)
            ->first();

        if (!$po) {
            return response()->json(["error" => "PO not found"], 404);
        }

        $lineItems = $po->polines->map(function($line) {
            return [
                "product" => $line->product->name ?? "Unknown",
                "description" => $line->product->description ?? "-",
                "quantity" => $line->quantity,
                "received" => $line->quantity - $line->balance_quantity,
                "balance" => $line->balance_quantity,
                "unit_price" => $line->unit_price ?? 0,
                "total" => ($line->quantity * ($line->unit_price ?? 0))
            ];
        });

        return response()->json([
            "po_no" => $po->po_no,
            "po_date" => $po->po_date,
            "vendor" => $po->vendor->name ?? "-",
            "vendor_code" => $po->vendor->vendor_code ?? "-",
            "status" => is_object($po->status) ? ($po->status->name ?? "Open") : ($po->status ?? "Open"),
            "total_amount" => $po->total_amount ?? 0,
            "currency" => $po->currency ?? "AED",
            "delivery_date" => $po->delivery_date ?? "-",
            "remarks" => $po->remarks ?? "-",
            "line_items" => $lineItems
        ]);
    }


    public function getVendorOrders(Request $request, $vendor_id)
    {
        $query = \App\Models\Po::with("polines")
            ->where("vendor_id", $vendor_id)
            ->where("delete_status", 0);

        // Apply date filter if provided
        if ($request->has("from") && $request->has("to")) {
            $query->whereBetween("po_date", [$request->from, $request->to]);
        }

        $orders = $query->orderBy("po_date", "desc")
            ->get()
            ->map(function($po) {
                $totalQty = $po->polines->sum("quantity");
                $balanceQty = $po->polines->sum("balance_quantity");
                return [
                    "po_no" => $po->po_no,
                    "po_date" => date("d M Y", strtotime($po->po_date)),
                    "item_count" => $po->polines->count(),
                    "total_qty" => $totalQty,
                    "balance_qty" => $balanceQty
                ];
            });

        return response()->json([
            "vendor_id" => $vendor_id,
            "orders" => $orders
        ]);
    }

}
