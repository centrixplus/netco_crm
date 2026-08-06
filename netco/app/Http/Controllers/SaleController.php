<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Orderstatuslog;
use App\Models\Productstock;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Models\Saleline;
use App\Models\Status;
use App\Models\Brand;
use App\Models\Location;
use App\Models\Salesnumber;
use App\Models\Warehousepersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;

class SaleController extends Controller
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

    public function index(Request $request)
    {
        $title = "Order";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $customers = Customer::where('delete_status','0')->orderBy('name','asc')->get();
        $users = User::orderBy('name','asc')->get();
        $statuses=Status::where('delete_status','0')->orderBy('order','asc')->get();
        $locations=Location::where('delete_status','0')->orderBy('name','asc')->get();
        $indexes = Sale::whereBetween('sale_date',[$fromdate, $todate]);
        $customer_id='';
        if(!empty($request->customer_id)){
            $customer_id=$request->customer_id;
            $indexes=$indexes->where('customer_id',$customer_id);
        }
        $status_id='';
        if(!empty($request->status_id)){
            $status_id=$request->status_id;
            $indexes=$indexes->where('status',$status_id);
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
        $del_sales='';
        if($request->deleted_sales=='on'){
            $del_sales=$request->deleted_sales;
            $indexes=$indexes->where('delete_status','1');
        }
        else{
            $indexes=$indexes->where('delete_status','0');
        }

        $location_id='';
        if(!empty($request->location_id)){
            $location_id=$request->location_id;
            $indexes=$indexes->whereHas('customer', function($q) use ($request)
            {
                $q->where('location_id',$request->location_id);

            });
        }
        $filter='';
        if(!empty($request->filter)){
            $filter=$request->filter;
            $lists=$$filter;
            $indexes=$indexes->orderBy('id','Desc')->get();
            $saletable = view('sale.salesfiltertable',compact('lists','fromdate','todate'))->render();
        }   
        else{
            $indexes=$indexes->orderBy('id','Desc')->get();
            $saletable = view('sale.salestable',compact('indexes'))->render();
        }
        return view('sale.index',compact('title','indexes','fromdate','todate','customers','customer_id','saletable','priority','status_id','users','user_id','statuses','del_sales','locations','location_id','filter'));  
    }

    public function create()
    {
        $title = "Orders";
        $products = Product::where('delete_status','0')->orderBy('name','asc')->get();
        $customers = Customer::where('delete_status','0')->orderBy('name','asc')->get();
        $statuses=Status::where('delete_status','0')->orderBy('order','asc')->get();
        $brands=Brand::where('delete_status','0')->orderBy('id','asc')->get();
        $count=1;
        $x='1';
        $list = view('sale.list',compact('products','x'))->render();
        return view('sale.create',compact('title','products','customers','list','statuses','brands','count'));  
    }

    public function newsale()
    {
        $title = "Orders";
        $products = Product::where('delete_status','0')->orderBy('name','asc')->get();
        $customers = Customer::where('delete_status','0')->orderBy('name','asc')->get();
        $statuses=Status::where('delete_status','0')->orderBy('order','asc')->get();
        $x='1';
        $list = view('sale.listnew',compact('products','x'))->render();
        return view('sale.new',compact('title','products','customers','list','statuses'));  
    }

    public function appendsalerow($sale_line_count='')
    {
        $x=$sale_line_count+1;
        $products = Product::where('delete_status','0')->orderBy('name','asc')->get();
        $list = view('sale.list',compact('products','x'))->render();
        echo json_encode(array("salerow"=>$list,"sale_line_count"=>$x));
    }
    public function appendsalerownew($sale_line_count='')
    {
        $x=$sale_line_count+1;
        $products = Product::where('delete_status','0')->orderBy('name','asc')->get();
        $list = view('sale.listnew',compact('products','x'))->render();
        echo json_encode(array("salerow"=>$list,"sale_line_count"=>$x));
    }
    

    public function getproductinfo($product_id='')
    {
       $stock=Productstock::where('product_id',$product_id)->first();
       $product=Product::where('id',$product_id)->first();
       $qty=0;
       $pdc=0;
       if($stock){
            $qty=$stock->qty+$stock->t_qty;
            $pdc=$stock->pdc;
       }
       echo json_encode(array("qty"=>$qty,"pdc"=>$pdc,"rate"=>$product->price));
    }

    public function store(Request $request)
    {       
        if($request->grand_total<=0){
            $msg='<span style="padding:3px;" class="bg-danger">Sale Order Not Created</span>';
            return redirect()->back()->with('alert', $msg);
        }
        else{
            $customer_id=$request->customer_id;
            $grand_total=number_format($request->grand_total, 2, '.', '' );
            $slae_id=Sale::insertGetId(['customer_id'=>$customer_id,'sale_date'=>$request->sale_date,'priority'=>$request->priority,'grand_total'=>$grand_total,'no_of_qty'=>$request->no_of_qty,'note'=>$request->comment,'created_by'=>Auth::user()->id,'created_at'=>date('Y-m-d H:i:s')]);

            for ($i=1; $i <= $request->sale_line_count; $i++) { 
                $product_id='product'.$i;
                $quantity='qty'.$i;
                $rate='rate'.$i;
                $target_rate='target_rate'.$i;
                $price='price'.$i;
                if($request->$product_id!='' && $request->$quantity!='0' && $request->$rate!=''){
                    $product=Product::where('id',$request->$product_id)->first();
                    $productstock=Productstock::where('product_id',$request->$product_id)->first();
                    $c_qty=$productstock->qty+$productstock->t_qty ;
                    if($c_qty >= $request->$quantity){
                        $l_qty=0;
                        $l_price=0;
                        $reason='0';
                    }
                    else{
                        $req_qty=$request->$quantity;
                        $l_qty=$req_qty-$c_qty;
                        $l_price=$l_qty*$request->$rate;
                        if($productstock->pdc=='0'){
                            $reason='PDC';
                        }
                        else{
                            $reason='NETCO';

                        }
                    }
                    $cost_price=$product->cost*$request->$quantity;
                    $data = new Saleline; 
                    $data->slae_id = $slae_id;
                    $data->product_id = $request->$product_id;
                    $data->quantity = $request->$quantity;
                    $data->rate = $request->$rate;
                    $data->target_rate = $request->$target_rate;
                    $data->cost = $product->cost;
                    $data->cost_price = $cost_price;
                    $data->price = $request->$price;
                    $data->reason = $reason;
                    $data->l_qty = $l_qty;
                    $data->l_price = $l_price;
                    $data->created_by=Auth::user()->id;
                    $data->save();

                    //Stock::store($request->$product_id,$request->$quantity,$request->sale_date,'Sales',$slae_id);
                }
            }

            $data = new Orderstatuslog; 
            $data->sale_id = $slae_id;
            $data->document_no = 0;
            $data->status = 'New';
            $data->created_by=Auth::user()->id;
            $data->save();

            //$scount =Sale::whereYear('sale_date', date('Y'))->count();
            $scount=DB::table('sales')->whereYear('sale_date', date('Y'))->count();
            $code='NDSO-'.date('Y').'-'.str_pad($scount, 4, '0', STR_PAD_LEFT);

            $data = new Salesnumber; 
            $data->sale_id = $slae_id;
            $data->name =$code;
            $data->saleno =$code;
            $data->save();

            $msg='<span style="padding:3px;" class="bg-success">Sale Order Created Successfully and Order No is: '.Sale::saleno($slae_id).'</span>';
            return redirect('/showsales/'.$slae_id)->with('alert', $msg);
        }
            
    }

    public function sale($id='',$process="")
    {
      $title='Order';
      $sale = Sale::where('id',$id)->first();
      $sale_lines = Saleline::where('slae_id',$id)->get();
      $setting=Setting::where('id','1')->first();
      return view('sale.view',compact('title','sale','sale_lines','process','setting'));  
    }

    public function destroy(Sale $sale,$id)
    {
        $data = Sale::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();

        Saleline::where('slae_id',$id)->update(['delete_status'=>'1','updated_by'=>Auth::user()->id]); 

        Orderstatuslog::where('sale_id',$id)->update(['delete_status'=>'1','updated_by'=>Auth::user()->id]); 

       // Stock::where('note',$id)->update(['delete_status'=>'1']);

        return redirect()->back();
    }
    
    public function assignperson(Request $request)
    {   
        //print_r($request->all()); exit();
        $data = Sale::find($request->sale_assign_id);
        $data->warehousepersion_id =$request->warehousepersion_id;
        $data->assigned_by=Auth::user()->id;
        $data->assigned_at=date('Y-m-d H:i:s');
        $data->save();



        return redirect()->back();
    }
    public function updatestatus(Request $request)
    {

        $sts=Status::where('name',$request->order_status)->first();

        $data = Sale::find($request->sale_id);
        $data->status =$request->order_status;
        $data->status_id =$sts->id;
        $data->invoice_no =$request->invoice_no;
        $data->reason =$request->reason;
        $data->awb_no =$request->awb_no;
        $data->box =$request->box;
        $data->save();

        $data = new Orderstatuslog; 
        $data->sale_id = $request->sale_id;
        $data->document_no = $request->invoice_no;
        $data->status =$request->order_status;
        $data->status_id =$sts->id;
        $data->reason =$request->reason;
        $data->awb_no =$request->awb_no;
        $data->box =$request->box;
        $data->created_by=Auth::user()->id;
        $data->save();

        return redirect()->back();
    }

    public function showsales($id='')
    {
      $title='Order';
      $sale = Sale::where('id',$id)->first();
      $sale_lines = Saleline::where('slae_id',$id)->get();
      $statuslogs=Orderstatuslog::where('sale_id',$id)->orderBy('id','Desc')->get();
      $statuses=Status::where('delete_status','0')->orderBy('order','asc')->get();
      return view('sale.show',compact('title','sale','sale_lines','statuslogs','statuses'));  
    }

    public function loadLastPurchase($product_id,$customer_id='')
    {
        $customer=Customer::where('id',$customer_id)->first();
        $sales=$customer->saleline->where('product_id',$product_id);
        $saletable = view('sale.productsale',compact('sales'))->render();
        echo json_encode(array("saletable"=>$saletable));
    }

    public function statussale(Request $request,$status_id)
    {
        $status=Status::where('id',$status_id)->first();

        $title = $status->name." Order";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $customers = Customer::where('delete_status','0')->orderBy('name','asc')->get();
        $warehousepersion = Warehousepersion::where('delete_status','0')->orderBy('name','asc')->get();
        $users = User::where('role','user')->orderBy('name','asc')->get();
        $statuses=Status::where('delete_status','0')->orderBy('order','asc')->get();
        $locations=Location::where('delete_status','0')->orderBy('name','asc')->get();
        $indexes = Sale::whereBetween('sale_date',[$fromdate, $todate])->where('status_id',$status_id);
        $customer_id='';
        if(!empty($request->customer_id)){
            $customer_id=$request->customer_id;
            $indexes=$indexes->where('customer_id',$customer_id);
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
        $del_sales='';
        if($request->deleted_sales=='on'){
            $del_sales=$request->deleted_sales;
            $indexes=$indexes->where('delete_status','1');
        }
        else{
            $indexes=$indexes->where('delete_status','0');
        }

        $location_id='';
        if(!empty($request->location_id)){
            $location_id=$request->location_id;
            $indexes=$indexes->whereHas('customer', function($q) use ($request)
            {
                $q->where('location_id',$request->location_id);

            });
        }
        $filter='';
        if(!empty($request->filter)){
            $filter=$request->filter;
            $lists=$$filter;
            $indexes=$indexes->orderBy('id','Desc')->get();
            $saletable = view('sale.salesfiltertable',compact('lists','fromdate','todate'))->render();
        }   
        else{
            $indexes=$indexes->orderBy('id','Desc')->get();
            $saletable = view('sale.salestable',compact('indexes'))->render();
        }
        return view('warehouse.salestatus',compact('title','indexes','fromdate','todate','customers','customer_id','saletable','priority','status_id','users','user_id','statuses','del_sales','locations','location_id','filter','warehousepersion'));  
    }

    public function invoiced(Request $request)
    {
        $title = "Invoiced Orders";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $customers = Customer::where('delete_status','0')->orderBy('name','asc')->get();
        $users = User::where('role','user')->orderBy('name','asc')->get();
        $statuses=Status::where('delete_status','0')->orderBy('order','asc')->get();
        $locations=Location::where('delete_status','0')->orderBy('name','asc')->get();
        $indexes = Sale::whereBetween('sale_date',[$fromdate, $todate1])->where('status_id','10');
        $customer_id='';
        if(!empty($request->customer_id)){
            $customer_id=$request->customer_id;
            $indexes=$indexes->where('customer_id',$customer_id);
        }
        $status_id='10';

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
        $del_sales='';
        if($request->deleted_sales=='on'){
            $del_sales=$request->deleted_sales;
            $indexes=$indexes->where('delete_status','1');
        }
        else{
            $indexes=$indexes->where('delete_status','0');
        }

        $location_id='';
        if(!empty($request->location_id)){
            $location_id=$request->location_id;
            $indexes=$indexes->whereHas('customer', function($q) use ($request)
            {
                $q->where('location_id',$request->location_id);

            });
        }
        //print_r($request->all()); exit();
        $indexes=$indexes->orderBy('id','Desc')->get();
        $saletable = view('sale.salestable',compact('indexes'))->render();
        return view('warehouse.invoiced',compact('title','indexes','fromdate','todate','customers','customer_id','saletable','priority','status_id','users','user_id','statuses','del_sales','locations','location_id'));  
    }

    
    public function searchsaleparts(Request $request)
    {
        $title="Order";
        $customers = Customer::where('delete_status','0')->orderBy('name','asc')->get();
        $statuses=Status::where('delete_status','0')->orderBy('order','asc')->get();
        $brands=Brand::where('delete_status','0')->orderBy('id','asc')->get();
        $textsearch = '';
        $arrresults = '';
        $brandid = 0;
        if (!empty($request->all())) {
        $brandid = $request->brand_id;
        $x=0;
        $textsearch = $request->search_text;
        $arrresults = array_chunk(preg_split('/\s+/u', $textsearch),2);
        $products = Product::where('delete_status','0')->orderBy('name','asc')->get();
        $list='';
            if(!empty($arrresults)){
                foreach($arrresults as $bpart){
                    if(count($bpart)>1){
                         $x++;
                         $brand=Brand::find($brandid);
                         $part=$brand->name.$bpart[0];
                         $qty=$bpart[1];
                         $product = Product::where('name',$part)->where('brand_id',$brandid)->first(); 
                         if($product){
                            $list.= view('sale.slist',compact('products','product','qty','x'))->render();
                         }

                    }
                }
            }
        }
        $count=$x;
        return view('sale.create',compact('title','products','customers','list','statuses','brands','count'));  
        
    }

    public function addSaleList($brand_id,$x,$textsearch)
    {
        if(!empty($textsearch)){
            $arrresults = array_chunk(preg_split('/\s+/u', $textsearch),2);
            $products = Product::where('delete_status','0')->orderBy('name','asc')->get();
            $list='';
            if(!empty($arrresults)){
                foreach($arrresults as $bpart){
                    if(count($bpart)>1){
                        $x++;
                        $brand=Brand::find($brandid);
                         $part=$brand->name.$bpart[0];
                         $qty=$bpart[1];
                         $product = Product::where('name',$part)->where('brand_id',$brandid)->first(); 
                         if($product){
                            $list.= view('sale.slist',compact('products','product','qty','x'))->render();
                         }
                    }
                }
            }
        }
        echo json_encode(array("count"=>$x,"saletable"=>$textsearch));
    }
        
}
