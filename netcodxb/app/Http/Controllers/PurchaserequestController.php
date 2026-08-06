<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Purchaserequestline;
use App\Models\Purchaserequest;
use App\Models\Purchaserequestlog;
use App\Models\Requesttype;
use App\Models\Prstatus;
use App\Models\Brand;
use App\Models\Enquiry;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Sale;
use App\Models\Store;
use App\Models\User;
use Auth;
use DB;

class PurchaserequestController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth'); 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Purchase Request";
        $stores=Store::where('delete_status','0')->where('branch_id',Auth::user()->branch_id)->orderBy('name','asc')->get();
        $customers = Customer::where('delete_status','0')->orderBy('name','asc')->get();
        $requesttypes = Requesttype::orderBy('name','asc')->get();
        
        return view('purchaserequest.index',compact('title','customers','stores','requesttypes'));  
    }

    public function updatePurchaseRequestLine($customer_id='',$product_id,$order_quantity,$target_price,$editid=0,$notes,$pr_id=0)
    {
          $purchaserequestline=Purchaserequestline::where('purchase_request_id','0')->where('created_by',Auth::user()->id)->where('customer_id',$customer_id)->where('product_id',$product_id)->count();
          if($purchaserequestline==0 || $editid!=0){

              $duplicate=0;

              $data = Purchaserequestline::find($editid);
              if (empty($data)) { $data = new Purchaserequestline; }
              $data->customer_id = $customer_id;
              $data->product_id = $product_id;
              $data->quantity = $order_quantity;
              $data->target_price = $target_price;
              $data->note = $notes;
              $data->purchase_request_id=$pr_id;
              $data->created_by=Auth::user()->id;
              $data->save();

          }
          else{
              $duplicate=1;
          }

          if($editid!=0){
            $duplicate=0;
          }
          
           echo json_encode(array("customer_id"=>$customer_id,"duplicate"=>$duplicate));
    }

    public function loadpurchaselines($customer_id='',$pr_id=0)
    {

      if($pr_id==0){
        $purchaserequestlines=Purchaserequestline::where('created_by',Auth::user()->id)->where('customer_id',$customer_id)->where('purchase_request_id',$pr_id)->orderBy('id','desc')->get();
      }
      else{
        $purchaserequestlines=Purchaserequestline::where('purchase_request_id',$pr_id)->orderBy('id','desc')->get();
      }

      

      $purchaserequestlineslists = view('purchaserequest.purchaserequestline',compact('purchaserequestlines'))->render();
      echo json_encode(array("purchaserequestlineslist"=>$purchaserequestlineslists));
    }

    public function deletePurchaserequestLine($id='')
    {
      Purchaserequestline::where('id',$id)->delete();
      echo json_encode(array("id"=>$id));
    }

    public function store(Request $request)
    {
        $store=Store::find($request->store_id);
        //print_r($store); exit();

        $receipt_count= DB::table('purchaserequests')->whereYear('pr_date', date('Y'))->count();
        $receipt_count=$receipt_count+1;
        $code='PR'.date('y').'-'.str_pad($receipt_count, 5, '0', STR_PAD_LEFT);

        if($request->customer_id==''){
        	$customer_id=0;
        }
        else{
        	$customer_id=$request->customer_id;
        }
        $pr_id=Purchaserequest::insertGetId(['customer_id'=>$customer_id,'pr_date'=>$request->pr_date,'note'=>$request->comment,'created_by'=>Auth::user()->id,'request_type'=>$request->request_type,'store_id'=>$request->store_id,'priority'=>$request->priority,'pr_no'=>$code,'created_at'=>date('Y-m-d H:i:s')]);

        Purchaserequestline::where('purchase_request_id','0')->where('created_by',Auth::user()->id)->where('customer_id',$customer_id)->update(['purchase_request_id'=>$pr_id]);

            self::updatePRStatus($pr_id,'1');

        return redirect('/createpurchaserequest');
    }

    public function update(Request $request)
    {
      //print_r($request->request_type); exit();
      if($request->customer_id==''){
          $customer_id=0;
        }
        else{
          $customer_id=$request->customer_id;
        }
      Purchaserequest::where('id',$request->pr_id)->update(['customer_id'=>$customer_id,'pr_date'=>$request->pr_date,'note'=>$request->comment,'updated_by'=>Auth::user()->id,'request_type'=>$request->request_type,'store_id'=>$request->store_id,'priority'=>$request->priority,'updated_at'=>date('Y-m-d H:i:s')]);
      return redirect('/edit/purchaserequest/'.$request->pr_id);
    }

    public function updatePRStatus($pr_id,$status){

            $data = new Purchaserequestlog; 
            $data->purchaserequest_id = $pr_id;
            $data->prstatus_id = $status;
            $data->created_by=Auth::user()->id;
            $data->save();
    }

    public function list(Request $request)
    {
        $title="Purchase Request List";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $indexes=Purchaserequest::where('delete_status','0')->whereBetween('pr_date',[$fromdate, $todate]);
        $customers = Customer::where('delete_status','0')->orderBy('name','asc')->get();
        $stores=Store::where('delete_status','0')->where('branch_id',Auth::user()->branch_id)->orderBy('name','asc')->get();
        
        $users=User::get();
        $customer_id='';
        if(!empty($request->customer_id)){
            $customer_id=$request->customer_id;
            $indexes=$indexes->where('customer_id',$customer_id);
        }

        $created_by='';
        if(!empty($request->created_by)){
            $created_by=$request->created_by;
            $indexes=$indexes->where('created_by',$created_by);
        }

        $status_id='';
        if(!empty($request->status_id)){
            $status_id=$request->status_id;
            $indexes=$indexes->where('prstatus_id',$status_id);
        }

        $requesttype_id='';
        if(!empty($request->requestype_id)){
            $requesttype_id=$request->requestype_id;
            $indexes=$indexes->where('request_type',$requesttype_id);
        }

        $priority='';
        if(!empty($request->priority)){
            $priority=$request->priority;
            $indexes=$indexes->where('priority',$priority);
        }

        $indexes=$indexes->orderBy('id','Desc')->get();
        $statuses=Prstatus::orderBy('id','asc')->get();
        $requesttypes = Requesttype::orderBy('name','asc')->get();
        $prlist_table = view('purchaserequest.prlist',compact('indexes'))->render();

        return view('purchaserequest.list',compact('title','customers','fromdate','todate','customer_id','indexes','statuses','status_id','stores','users','created_by','prlist_table','priority','requesttypes','requesttype_id'));
    }

    public function load5oldprlines($product_id='')
    {

      $old5purchaserequestlines=Purchaserequestline::where('purchase_request_id','!=','0')->where('product_id',$product_id)->orderBy('id','desc')->get();

      $old5purchaserequestlineslists = view('purchaserequest.old5list',compact('old5purchaserequestlines'))->render();
      echo json_encode(array("old5prhtml"=>$old5purchaserequestlineslists));
    }

    public function view($prid='')
    {
      $pr=Purchaserequest::where('id',$prid)->first();
      $title='View Purchase Request - '.$pr->pr_no;
      return view('purchaserequest.view',compact('title','pr'));
    }

    public function delete($id)
    {
        $data = Purchaserequest::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/purchaserequest');
    }

    public function statusupdate($id,$status)
    {
        Purchaserequest::where('id',$id)->update(['prstatus_id'=>$status,'updated_by'=>Auth::user()->id]);

        self::updatePRStatus($id,$status);
        return redirect()->back();
    }

    public function edit($id)
    {
        $title = "Edit Purchase Request";
        $pr=Purchaserequest::find($id);
        $prlines=Purchaserequestline::where('purchase_request_id',$id)->get();
        $customers = Customer::where('delete_status','0')->orderBy('name','asc')->get();
        $stores=Store::where('delete_status','0')->where('branch_id',Auth::user()->branch_id)->orderBy('name','asc')->get();
        $requesttypes = Requesttype::orderBy('name','asc')->get();
        return view('purchaserequest.edit',compact('title','customers','pr','prlines','stores','requesttypes'));  
    }


    

    

}