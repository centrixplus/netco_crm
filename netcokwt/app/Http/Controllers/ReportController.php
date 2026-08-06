<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Location;
use App\Models\Sale;
use App\Models\Saleline;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Status;
use App\Models\User;
use App\Models\Uploadlog;
use App\Models\Orderstatuslog;
use App\Models\Invoice;
use App\Models\Invoiceline;
use App\Models\Invoicelog;
use App\Models\Invoicestatus;
use App\Models\Enquiry;
use App\Models\Enquiryline;
use App\Models\Enquirycart;
use App\Models\Pricelog;
use App\Models\Productstocklog;
use App\Models\Producthistory;
use App\Models\Purchaserequestline;
use App\Models\Purchaserequest;
use App\Models\Purchaserequestlog;
use App\Models\Requesttype;
use App\Models\Prstatus;
use App\Models\Quote;
use App\Models\Quoteline;
use App\Models\Currency;

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
        $brands=Brand::where('delete_status','0')->orderBy('name','Asc')->get();
        $indexes = Saleline::where('delete_status','0')->whereBetween('created_at',[$fromdate, $todate1]);
        $product_id='';
        $brand_id='';
        if(!empty($request->product_id) && !empty($request->brand_id)){
            $brand=Brand::find($request->brand_id);
            $brand_id=$request->brand_id;
            $product_id=$request->product_id;
            $productname=$brand->name.$request->product_id;
            $product=Product::where('brand_id',$request->brand_id)->where('name',$productname)->first();
            if($product){
                $indexes=$indexes->where('product_id',$product_id);
            }
            
        }
        $indexes=$indexes->orderBy('id','Desc')->get();
        
        return view('report.salesline',compact('title','indexes','fromdate','todate','product_id','brands','brand_id')); 
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
        $indexes = Saleline::where('delete_status','0')->where('reason','!=','0')->whereBetween('created_at',[$fromdate, $todate1]);
        $brands=Brand::where('delete_status','0')->orderBy('name','Asc')->get();
        $product_id='';
        $brand_id='';
        if(!empty($request->product_id) && !empty($request->brand_id)){
            $brand=Brand::find($request->brand_id);
            $brand_id=$request->brand_id;
            $product_id=$request->product_id;
            $productname=$brand->name.$request->product_id;
            $product=Product::where('brand_id',$request->brand_id)->where('name',$productname)->first();
            if($product){
                $indexes=$indexes->where('product_id',$product_id);
            }
            
        }

        $reason='';
        if(!empty($request->reason)){
            $reason=$request->reason;
            $indexes=$indexes->where('reason',$reason);
        }

        $indexes=$indexes->orderBy('id','Desc')->get();
        
        return view('report.los',compact('title','indexes','fromdate','todate','product_id','reason','brands','brand_id')); 
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

    public function brand(Request $request)
    {
        $title = "Brand wise Sales Report";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $indexes = Brand::where('delete_status','0')->orderBy('name','asc')->get();
        
        return view('report.brand',compact('title','indexes','fromdate','todate')); 
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

    public function uploadlogs(Request $request)
    {
       $title = "Upload Log Report";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $brands=Brand::where('delete_status','0')->orderBy('name','Asc')->get();
        $indexes = Uploadlog::whereBetween('created_at',[$fromdate, $todate1]);

        $brand_id='';
        if(!empty($request->brand_id)){
            $brand_id=$request->brand_id;
            $indexes=$indexes->where('brand_id',$brand_id);
        }

        $indexes=$indexes->orderBy('id','Desc')->get();
        return view('report.uploadlog',compact('title','indexes','fromdate','todate','brand_id','brands'));  
    }

    public function pilosreport(Request $request)
    {
        $title = "Proforma Invoice Loss of Sales";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $indexes = Invoiceline::where('invoice_id','!=','0')->where('reason','!=','Success')->whereBetween('created_at',[$fromdate, $todate1]);
        $brands = Brand::where('delete_status','0')->get();
        
        $product_id='';
        if(!empty($request->product_id)){
            $product_id=$request->product_id;
            $product=Product::where('name',$product_id)->first();
            if($product){
                 $indexes=$indexes->where('product_id',$product->id);
            }
        }

        $brand_id='';
        if(!empty($request->brand_id)){
            $brand_id=$request->brand_id;
            $indexes=$indexes->whereHas('product', function($q) use ($request)
            {
                $q->where('brand_id',$request->brand_id);

            });
        }

        $indexes=$indexes->orderBy('id','Desc')->get();
        return view('report.pilosreport',compact('title','indexes','fromdate','todate','product_id','brands','brand_id')); 
    }

    public function getproductwindowinfo($id){

         $product = Product::with([
             'brand', 'uom', 'group', 'category', 'subcategory',
             'size', 'color', 'packagetype', 'productstock',
             'dealerpricelogs.createdBy'
         ])->where('id',$id)->first();

         $last5PIs=Invoiceline::with(['invoice','customer'])->where('product_id',$id)->where('invoice_id','>',0)->orderBy('id','desc')->limit(5)->get();

         $lastSales=Saleline::with(['sale.customer','sale.orderstatus'])->where('product_id',$id)->where('slae_id','>',0)->orderBy('id','desc')->get();
         $lastSalesDraft=Saleline::where('product_id',$id)->where('slae_id','=',0)->orderBy('id','desc')->get();
         $invoiceLosPrice = Invoiceline::where('invoice_id','!=','0')->where('reason','Price')->where('product_id',$id)->sum('quantity_los');
         $invoiceLosStock = Invoiceline::where('invoice_id','!=','0')->where('reason','Stock')->where('product_id',$id)->sum('quantity_los');

         $last5enqs=Enquiryline::with(['enquiry.customer'])->where('product_id',$id)->where('enquiry_id','>',0)->orderBy('id','desc')->limit(5)->get();

         $last5stock=Pricelog::where('product_id',$id)->orderBy('id','desc')->limit(5)->get();

         $last5price=Productstocklog::where('product_id',$id)->orderBy('id','desc')->limit(5)->get();

         $pricehistories=Producthistory::where('product_id',$id)->orderBy('id','desc')->get();

         $dealerpricelogs=$product->dealerpricelogs;

         $enquirySumQty = Enquiryline::where('product_id',$id)->where('enquiry_id','!=','0')->sum('quantity');
         $enquirySumPrice = Enquiryline::where('product_id',$id)->where('enquiry_id','!=','0')->sum('price');
         $invoiceSumQty = Invoiceline::where('product_id',$id)->where('invoice_id','!=','0')->sum('quantity_so');
         $invoiceSumAmount = Invoiceline::where('product_id',$id)->where('invoice_id','!=','0')->sum('amount');
         $salesSumQty = Saleline::where('product_id',$id)->where('slae_id','!=','0')->sum('quantity');
         $salesSumCost = Saleline::where('product_id',$id)->where('slae_id','!=','0')->sum('cost_price');

         $rproduct = null;
         if($product->id != $product->reference_id){
             $rproduct = Product::find($product->reference_id);
         }

         $productinfo = view('report.productwindow',compact('product','last5PIs','lastSales','last5enqs','last5stock','last5price','pricehistories','dealerpricelogs','lastSalesDraft','invoiceLosPrice','invoiceLosStock','enquirySumQty','enquirySumPrice','invoiceSumQty','invoiceSumAmount','salesSumQty','salesSumCost','rproduct'))->render();

         echo json_encode(array("partname"=>$product->name,"partinfoview"=>$productinfo));
    }

    public function pisalereport(Request $request)
    {
        $title = "Proforma Invoice Sales Report";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $indexes = Invoiceline::where('invoice_id','!=','0')->whereBetween('created_at',[$fromdate, $todate1]);
        
        $brands = Brand::where('delete_status','0')->get();

        $product_id='';
        if(!empty($request->product_id)){
            $product_id=$request->product_id;
            $product=Product::where('name',$product_id)->first();
            if($product){
                 $indexes=$indexes->where('product_id',$product->id);
            }
        }

        $brand_id='';
        if(!empty($request->brand_id)){
            $brand_id=$request->brand_id;
            $indexes=$indexes->whereHas('product', function($q) use ($request)
            {
                $q->where('brand_id',$request->brand_id);

            });
        }

        $indexes=$indexes->orderBy('id','Desc')->get();
        return view('report.pisalereport',compact('title','indexes','fromdate','todate','product_id','brands','brand_id')); 
    }

    public function viewuploadlog($id='')
    {
        $title = "View Not Uploaded List";
        $log = Uploadlog::find($id);
        $logs=explode(",", $log->message);
        return view('report.viewuploadlog',compact('title','logs')); 
    }

    public function draftinvoice(Request $request)
    {
        $title = "Proforma Invoice Draft Report";
        $indexes = Invoiceline::where('invoice_id','0');
        
        $brands = Brand::where('delete_status','0')->get();

        $product_id='';
        if(!empty($request->product_id)){
            $product_id=$request->product_id;
            $product=Product::where('name',$product_id)->first();
            if($product){
                 $indexes=$indexes->where('product_id',$product->id);
            }
        }

        $brand_id='';
        if(!empty($request->brand_id)){
            $brand_id=$request->brand_id;
            $indexes=$indexes->whereHas('product', function($q) use ($request)
            {
                $q->where('brand_id',$request->brand_id);

            });
        }

        $indexes=$indexes->orderBy('id','Asc')->get();
        return view('report.draftinvoice',compact('title','indexes','product_id','brands','brand_id')); 
    }

    public function productrequest(Request $request)
    {
        $title = "Product Wise Purchase Request Report";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $brands=Brand::where('delete_status','0')->orderBy('name','Asc')->get();
        $indexes = Purchaserequestline::where('purchase_request_id','!=',0)->whereBetween('created_at',[$fromdate, $todate1]);
        $product_id='';
        $brand_id='';

        if(!empty($request->brand_id)){
            $brand_id=$request->brand_id;
            $indexes=$indexes->whereHas('product', function($q) use ($request)
            {
                $q->where('brand_id',$request->brand_id);

            });
        }

        if(!empty($request->product_id) && !empty($request->brand_id)){
            $brand=Brand::find($request->brand_id);
            $brand_id=$request->brand_id;
            $product_id=$request->product_id;
            $productname=$brand->name.$request->product_id;
            $product=Product::where('brand_id',$request->brand_id)->where('name',$productname)->first();
            if($product){
                $indexes=$indexes->where('product_id',$product_id);
            }
            
        }



        $created_by='';
        if(!empty($request->created_by)){
            $created_by=$request->created_by;
            $indexes=$indexes->where('created_by',$created_by);
        }

        $request_type_id='';
        if(!empty($request->requestype_id)){
            $request_type_id=$request->requestype_id;
            $indexes=$indexes->whereHas('purchaserequest', function($q) use ($request)
            {
                $q->where('request_type',$request->requestype_id);

            });
        }

        $status_id='';
        if(!empty($request->status_id)){
            $status_id=$request->status_id;
            $indexes=$indexes->whereHas('purchaserequest', function($q) use ($request)
            {
                $q->where('prstatus_id',$request->status_id);

            });
        }

        $priority='';
        if(!empty($request->priority)){
            $priority=$request->priority;
            $indexes=$indexes->whereHas('purchaserequest', function($q) use ($request)
            {
                $q->where('priority',$request->priority);

            });
        }

        $users=User::get();
        $requesttypes = Requesttype::orderBy('name','asc')->get();
        $statuses=Prstatus::orderBy('id','asc')->get();
        $indexes=$indexes->orderBy('id','Desc')->get();
        
        return view('report.productrequest',compact('title','indexes','fromdate','todate','product_id','brands','brand_id','created_by','users','requesttypes','request_type_id','statuses','status_id','priority')); 
    }

    public function prdraftreport(Request $request)
    {
        $title = "Product Draft Wise Purchase Request Report";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $brands=Brand::where('delete_status','0')->orderBy('name','Asc')->get();
        $indexes = Purchaserequestline::where('purchase_request_id',0)->whereBetween('created_at',[$fromdate, $todate1]);
        $product_id='';
        $brand_id='';

        if(!empty($request->brand_id)){
            $brand_id=$request->brand_id;
            $indexes=$indexes->whereHas('product', function($q) use ($request)
            {
                $q->where('brand_id',$request->brand_id);

            });
        }
        
        if(!empty($request->product_id) && !empty($request->brand_id)){
            $brand=Brand::find($request->brand_id);
            $brand_id=$request->brand_id;
            $product_id=$request->product_id;
            $productname=$brand->name.$request->product_id;
            $product=Product::where('brand_id',$request->brand_id)->where('name',$productname)->first();
            if($product){
                $indexes=$indexes->where('product_id',$product_id);
            }
            
        }

        $created_by='';
        if(!empty($request->created_by)){
            $created_by=$request->created_by;
            $indexes=$indexes->where('created_by',$created_by);
        }

        $users=User::get();
        $indexes=$indexes->orderBy('id','Desc')->get();
        
        return view('report.prdraftrequest',compact('title','indexes','fromdate','todate','product_id','brands','brand_id','created_by','users')); 
    }

    public function quotereport(Request $request)
    {
        $title = "Quote";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $customers = Customer::where('delete_status','0')->orderBy('name','asc')->get();
        $users = User::orderBy('name','asc')->get();
        $brands=Brand::where('delete_status','0')->orderBy('name','asc')->get();
        $indexes = Quoteline::whereBetween('created_at',[$fromdate, $todate1]);
        $currencies = Currency::get();
        $customer_id='';
        if(!empty($request->customer_id)){
            $customer_id=$request->customer_id;
            $indexes=$indexes->whereHas('quote', function($q) use ($request)
            {
                $q->where('customer_id',$request->customer_id);

            });
        }
        $brand_id='';
        if(!empty($request->brand_id)){
            $brand_id=$request->brand_id;
            $indexes=$indexes->whereHas('quote', function($q) use ($request)
            {
                $q->where('brand_id',$request->brand_id);

            });
        }
        $user_id='';
        if(!empty($request->user_id)){
            $user_id=$request->user_id;
            $indexes=$indexes->whereHas('quote', function($q) use ($request)
            {
                $q->where('created_by',$request->user_id);

            });
        }
        $currency_id='';
        if(!empty($request->currency)){
            $currency_id=$request->currency;
            $indexes=$indexes->whereHas('quote', function($q) use ($request)
            {
                $q->where('currency_id',$request->currency);

            });
        }

        $product_val='';
        if(!empty($request->product)){
            $product_val=$request->product;
            $indexes=$indexes->where('product',$product_val);
        }
        $indexes=$indexes->where('delete_status','0')->orderBy('id','Desc')->get();

        //print_r($indexes); exit();

        return view('report.quote',compact('title','indexes','fromdate','todate','customers','customer_id','brand_id','users','user_id','brands','currencies','currency_id','product_val'));  
    }


    

}
