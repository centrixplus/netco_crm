<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Location;
use App\Models\Sale;
use App\Models\Saleline;
use App\Models\Product;
use App\Models\Status;
use App\Models\User;
use App\Models\Orderstatuslog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth'); 
    }

    public function sales(Request $request)
    {
        $title = "Sales Report";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $customers = Customer::where('delete_status','0')->orderBy('name','asc')->get();
        $indexes = Sale::where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate]);
        $users = User::orderBy('name','asc')->get();
        $statuses=Status::where('delete_status','0')->orderBy('order','asc')->get();
        $customer_id='';
        if(!empty($request->customer_id)){
            $customer_id=$request->customer_id;
            $indexes=$indexes->where('customer_id',$customer_id);
        }

        $status='';
        if(!empty($request->order_status)){
            $status=$request->order_status;
            $indexes=$indexes->where('status',$status);
        }

        $priority='';
        if(!empty($request->order_priority)){
            $priority=$request->order_priority;
            $indexes=$indexes->where('priority',$priority);
        }
        $user_id='';
        if(!empty($request->user_id)){
            $user_id=$request->user_id;
            $indexes=$indexes->where('created_by',$user_id);
        }
        $indexes=$indexes->orderBy('id','Desc')->get();
        return view('report.sales',compact('title','indexes','fromdate','todate','customers','customer_id','status','priority','users','user_id','statuses'));  
    }

    public function productsales(Request $request)
    {
        $title = "Product Wise Sales Report";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $products = Product::where('delete_status','0')->orderBy('name','asc')->get();
        $indexes = Saleline::where('delete_status','0')->whereBetween('created_at',[$fromdate, $todate1]);
        $product_id='';
        if(!empty($request->product_id)){
            $product_id=$request->product_id;
            $indexes=$indexes->where('product_id',$product_id);
        }
        $indexes=$indexes->orderBy('id','Desc')->get();
        
        return view('report.salesline',compact('title','indexes','fromdate','todate','products','product_id')); 
    }

    public function los(Request $request)
    {
        $title = "Loss of Sales Report";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $products = Product::where('delete_status','0')->orderBy('name','asc')->get();
        $indexes = Saleline::where('delete_status','0')->where('reason','!=','0')->whereBetween('created_at',[$fromdate, $todate1]);
        $product_id='';
        if(!empty($request->product_id)){
            $product_id=$request->product_id;
            $indexes=$indexes->where('product_id',$product_id);
        }

        $reason='';
        if(!empty($request->reason)){
            $reason=$request->reason;
            $indexes=$indexes->where('reason',$reason);
        }

        $indexes=$indexes->orderBy('id','Desc')->get();
        
        return view('report.los',compact('title','indexes','fromdate','todate','products','product_id','reason')); 
    }

    public function location(Request $request)
    {
        $title = "Location wise Sales Report";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $indexes = Location::where('delete_status','0')->orderBy('name','asc')->get();
        
        return view('report.location',compact('title','indexes','fromdate','todate')); 
    }

    public function status(Request $request)
    {
        $title = "Order Status Log Report";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $indexes = Orderstatuslog::where('delete_status','0')->whereBetween('created_at',[$fromdate, $todate1]);
        $statuses=Status::where('delete_status','0')->orderBy('order','asc')->get();

        $status='';
        if(!empty($request->order_status)){
            $status=$request->order_status;
            $indexes=$indexes->where('status',$status);
        }

        $indexes=$indexes->orderBy('id','Desc')->get();
        return view('report.status',compact('title','indexes','fromdate','todate','status','statuses'));  
    }

    

}
