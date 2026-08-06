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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;

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

    public function dashboard($fromdate,$todate)
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

   
}
