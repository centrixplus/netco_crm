<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Location;
use App\Models\Productstock;
use App\Models\Producthistory;
use App\Models\Brand;
use App\Models\Enquiry;
use App\Models\Enquiryline;
use App\Models\Enquirycart;
use App\Models\Stage;
use App\Models\Customer;
use App\Models\Enquirystagelog;
use App\Models\Invoice;
use App\Models\Invoiceline;
use App\Models\Invoicelog;
use App\Models\Invoicestatus;
use App\Models\Sale;
use App\Models\Saleline;
use App\Models\Orderstatuslog;
use App\Models\Store;
use App\Models\User;

use Auth;
use DB;

class SearchController extends Controller
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
    public function index(Request $request)
    {
       // print_r($request->all()); exit();
        $textsearch = '';
        $arrresults = '';
        $customers=Customer::where('delete_status','0')->orderBy('name','Asc')->get();
        $brandid = 0;
        
        $enquiries=Enquirycart::where('cart_status','0')->where('delete_status','0')->get();
        //print_r($arrresults); exit();
        $brands = Brand::where('delete_status','0')->get();
        $stores=Store::where('delete_status','0')->where('branch_id',Auth::user()->branch_id)->orderBy('name','asc')->get();
        return view('enquiry.index',compact('arrresults','textsearch','brands','brandid','enquiries','customers','stores'));
    }

    public function quoteindex(Request $request)
    {
       // print_r($request->all()); exit();
        $textsearch = '';
        $arrresults = '';
        $customers=Customer::where('delete_status','0')->orderBy('name','Asc')->get();
        $brandid = 0;
        
        $enquiries=Enquirycart::where('cart_status','0')->where('delete_status','0')->get();
        //print_r($arrresults); exit();
        $brands = Brand::where('delete_status','0')->get();
        return view('enquiry.createquote',compact('arrresults','textsearch','brands','brandid','enquiries','customers'));
    }

    public function createinvoice()
    {
        $title = "Performa Invoice";
        $stores=Store::where('delete_status','0')->where('branch_id',Auth::user()->branch_id)->orderBy('name','asc')->get();
        $customers = Customer::where('delete_status','0')->orderBy('name','asc')->get();
        
        return view('enquiry.invoice',compact('title','customers','stores'));  
    }

    public function editinvoice($id)
    {
        $title = "Performa Invoice";
        $invoice=Invoice::find($id);
        $invoicelines=Invoiceline::where('invoice_id',$id)->get();
        $customers = Customer::where('delete_status','0')->orderBy('name','asc')->get();
        $stores=Store::where('delete_status','0')->where('branch_id',Auth::user()->branch_id)->orderBy('name','asc')->get();
        return view('enquiry.editinvoice',compact('title','customers','invoice','invoicelines','stores'));  
    }

    public function loadInvoiceLines($customer_id='')
    {
      $invoicelines=Invoiceline::where('invoice_id','0')->where('created_by',Auth::user()->id)->where('customer_id',$customer_id)->orderBy('id','desc')->get();
      $invoicelineslists = view('enquiry.invoiceline',compact('invoicelines'))->render();
      $invoiceamount=Invoiceline::where('invoice_id','0')->where('created_by',Auth::user()->id)->where('customer_id',$customer_id)->sum('amount');
       $invoiceamount=number_format($invoiceamount, 3, '.', '');
      $invoices=Invoice::where('delete_status','0')->where('customer_id',$customer_id)->orderBy('id','Desc')->limit('5')->get();
      $invoicelists = view('enquiry.invoicelists',compact('invoices'))->render();
      echo json_encode(array("invoicelineslists"=>$invoicelineslists,"invoiceamount"=>$invoiceamount,"invoicelists"=>$invoicelists));
    }

    public function loadInvoiceLinesAppointment($id='')
    {
      $invoicelines=Invoiceline::where('invoice_id',$id)->orderBy('id','desc')->get();
      $invoicelineslists = view('enquiry.invoiceline',compact('invoicelines'))->render();
      $invoiceamount=Invoiceline::where('invoice_id',$id)->sum('amount');
      $inv=Invoice::find($id);
      $invoices=Invoice::where('delete_status','0')->where('customer_id',$inv->customer_id)->orderBy('id','Desc')->limit('5')->get();
      $invoicelists = view('enquiry.invoicelists',compact('invoices'))->render();
      echo json_encode(array("invoicelineslists"=>$invoicelineslists,"invoiceamount"=>$invoiceamount,"invoicelists"=>$invoicelists));
    }

    public function updateInvoiceLine($customer_id='',$product_id,$order_quantity,$so_quantity,$target_price,$is_invoiced,$editid=0,$invoice_id=0,$rsp=0)
    {
          $invoiceline=Invoiceline::where('invoice_id','0')->where('created_by',Auth::user()->id)->where('customer_id',$customer_id)->where('product_id',$product_id)->count();
          if($invoiceline==0 || $editid!=0){

              $duplicate=0;
              $product=Product::find($product_id);
              $stock=Productstock::where('product_id',$product_id)->first();
              $quantity_los=0;
              $los_price=0;
              $reason='Success';
              if($order_quantity > $stock->qty){
                $quantity_los=$order_quantity-$stock->qty;
                $los_price=$quantity_los*$rsp;
                $reason='Stock';
              }

              if($order_quantity <= $stock->qty){
                  if($is_invoiced==0){
                    $quantity_los=$order_quantity;
                    $los_price=$quantity_los*$rsp;
                    $reason='Price';
                  }
              }
              $data = Invoiceline::find($editid);
              if (empty($data)) { $data = new Invoiceline; }
              $data->customer_id = $customer_id;
              $data->product_id = $product_id;
              $data->quantity_po = $order_quantity;
              $data->quantity_so = $so_quantity;
              $data->is_invoiced = $is_invoiced;
              $data->target_price = number_format($target_price, 3, '.', '');
              $data->rsp_orginal = number_format($product->price, 3, '.', '');
              $data->rsp = number_format($rsp, 3, '.', '');
              $data->cost = number_format($product->cost, 3, '.', '');
              $data->amount = number_format($so_quantity * $rsp, 3, '.', '');
              $data->los_price = number_format($los_price, 3, '.', '');
              $data->reason = $reason;
              $data->invoice_id = $invoice_id;
              $data->quantity_los = $quantity_los;
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

    public function convertinvoicetoso(Request $request)
    {    
         $invoice_ids=implode(',', $request->pi);
         $invoices=Invoice::whereIn('id',$request->pi)->get();
         if($invoices[0]->sale_id==0){
            $lists=Invoiceline::whereIn('invoice_id',$request->pi)->where('is_invoiced','1')->get();
             //print_r($lists); exit();
             $invoicelinesa=Invoiceline::whereIn('invoice_id',$request->pi)->where('is_invoiced','1')->get();
             $amount_so=0;
             foreach ($invoicelinesa as $inln) {
               $amount_so_line=$inln->target_price*$inln->quantity_so;
               $amount_so=$amount_so+$amount_so_line;
             }
             $no_of_qty=Invoiceline::whereIn('invoice_id',$request->pi)->where('is_invoiced','1')->sum('quantity_so');
             
            if($amount_so<=0){
                $msg='<span style="padding:3px;" class="bg-danger">Sale Order Not Created</span>';
                return redirect()->back()->with('alert', $msg);
            }
            else{
                $customer_id=$request->customer_id;
                $grand_total=number_format($amount_so, 2, '.', '' );

                $store=Store::find($invoices[0]->store_id);
                $receipt_count= DB::table('sales')->where('branch_id',$store->branch_id)->whereYear('sale_date', date('Y'))->count();
                $receipt_count=$receipt_count+1;
                $code='SO'.date('y').'-'.str_pad($store->company_id, 1, '0', STR_PAD_LEFT).''.str_pad($store->branch_id, 2, '0', STR_PAD_LEFT).''.str_pad($invoices[0]->store_id, 2, '0', STR_PAD_LEFT).''.str_pad($receipt_count, 5, '0', STR_PAD_LEFT);

                $slae_id=Sale::insertGetId(['customer_id'=>$customer_id,'sale_date'=>date('Y-m-d'),'priority'=>$request->priority,'grand_total'=>$grand_total,'no_of_qty'=>$no_of_qty,'note'=>$request->comment,'created_by'=>Auth::user()->id,'created_at'=>date('Y-m-d H:i:s'),'sales_from'=>'PI','enquiry_id'=>$invoice_ids,'store_id'=>$invoices[0]->store_id,'branch_id'=>$store->branch_id,'company_id'=>$store->company_id,'sale_no'=>$code]);

                foreach ($lists as $list) { 
              
                    if($list->product_id!='' && $list->quantity_so!='0' && $list->quantity_so!=''){
                        $product=Product::where('id',$list->product_id)->first();
                        $productstock=Productstock::where('product_id',$list->product_id)->first();

                        $c_qty=$productstock->qty+$productstock->t_qty ;
                        if($c_qty >= $list->quantity_so){
                            $l_qty=0;
                            $l_price=0;
                            $reason='0';
                        }
                        else{
                            $req_qty=$list->quantity_so;
                            $l_qty=$req_qty-$c_qty;
                            $l_price=$l_qty*$list->target_price;
                            if($productstock->pdc=='0'){
                                $reason='PDC';
                            }
                            else{
                                $reason='NETCO';

                            }
                        }
                        $cost_price=$list->target_price*$list->quantity_so;
                        $data = new Saleline; 
                        $data->slae_id = $slae_id;
                        $data->product_id = $list->product_id;
                        $data->quantity = $list->quantity_so;
                        $data->target_price = $list->target_price;
                        $data->rate = $list->target_price;
                        $data->cost = $list->cost;
                        $data->cost_price = $cost_price;
                        $data->price = $list->target_price;
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

                DB::table('invoices')->whereIn('id',$request->pi)->update(['sale_id'=>$slae_id,'status'=>'3']);

                foreach ($request->pi as $id) {
                    self::updateInvoiceStatus($id,'3');
                }

                $sale=Sale::find($slae_id);
                $msg='<span style="padding:3px;" class="bg-success">Sale Order Created Successfully and Order No is: '.$sale->sale_no.'</span>';
                return redirect('/showsales/'.$slae_id)->with('alert', $msg);
            }
         }
         else{
            return redirect()->back();
         }
         
            
    }
    
    public function store(Request $request){
     if (!empty($request->all())) {
        $brandid = $request->brand_id;
        if (!empty($request->search_text)) {
            $textsearch = $request->search_text;
            $arrresults = array_chunk(preg_split('/\s+/u', $textsearch),2);
            // foreach($arrresults as $ar){
            //     echo $ar[0].'       qty:'.$ar[1].'<br>';
            // }
            // print_r($arrresults); 
            // exit();
        }
        else{
            $arrresults = array(array($request->partno,$request->qty));
        }
            // $arrresults = array_unique($arrresults);
            // print_r($arrresults); exit();
            if(!empty($arrresults)){
                foreach($arrresults as $bpart){
                    $qty=0;
                    if(count($bpart)>1){
                         $brand=Brand::find($brandid);
                         $part=$brand->name.$bpart[0];
                         $product=Product::where('name',$part)->where('brand_id',$brandid)->first();
                          if(!empty($product)){
                               $qty=$product->productstock->qty+$product->productstock->t_qty;
                               if($product->is_sellable!=0 || $qty==0){
                                  $product=Product::where('id',$product->reference_id)->first();
                               }
                                $data = new Enquirycart; 
                                $data->brand_id = $request->brand_id;
                                $data->part = $bpart[0];
                                $data->product_id = $product->id;
                                $data->quantity = $bpart[1];
                                $data->created_by=Auth::user()->id;
                                $data->save();
                          }
                          else{
                             if(!empty($bpart[0])){
                                $data = new Enquirycart; 
                                $data->brand_id = $request->brand_id;
                                $data->part = $bpart[0];
                                $data->product_id = 0;
                                $data->quantity = $bpart[1];
                                $data->created_by=Auth::user()->id;
                                $data->save();
                            }
                          }
                    }
                }
            }
        }

        if($request->form_type=='quote'){
          return redirect('/quote')->with('customers_id',$request->customers_id);

        }
        else{
          return redirect('/enquiry');

        }

        
    }

    public function invoicestore(Request $request)
    {
        $store=Store::find($request->store_id);
        //print_r($store); exit();

        $receipt_count= DB::table('invoices')->where('branch_id',$store->branch_id)->whereYear('invoice_date', date('Y'))->count();
        $receipt_count=$receipt_count+1;
        $code='SP'.date('y').'-'.str_pad($store->company_id, 1, '0', STR_PAD_LEFT).''.str_pad($store->branch_id, 2, '0', STR_PAD_LEFT).''.str_pad($request->store_id, 2, '0', STR_PAD_LEFT).''.str_pad($receipt_count, 5, '0', STR_PAD_LEFT);

        $invoice_id=Invoice::insertGetId(['customer_id'=>$request->customer_id,'invoice_date'=>$request->invoice_date,'remarks'=>$request->comment,'created_by'=>Auth::user()->id,'amount_so'=>$request->grand_total,'store_id'=>$request->store_id,'branch_id'=>$store->branch_id,'company_id'=>$store->company_id,'invoice_no'=>$code,'created_at'=>date('Y-m-d H:i:s')]);



        Invoiceline::where('invoice_id','0')->where('created_by',Auth::user()->id)->where('customer_id',$request->customer_id)->update(['invoice_id'=>$invoice_id]);

            self::updateInvoiceStatus($invoice_id,'1');

        return redirect('/createinvoice');
    }

    public function invoiceupdate(Request $request)
    {
      $store=Store::find($request->store_id);
      //print_r($request->sale_no); exit();
      $inv=Invoice::Find($request->invoice_id);
      $s1=substr($inv->invoice_no, 0,8);
      $s2=substr($inv->invoice_no,-5);
      $code=$s1.''.str_pad($request->store_id, 2, '0', STR_PAD_LEFT).$s2;
      Invoice::where('id',$request->invoice_id)->update(['customer_id'=>$request->customer_id,'invoice_date'=>$request->invoice_date,'remarks'=>$request->comment,'updated_by'=>Auth::user()->id,'amount_so'=>$request->grand_total,'store_id'=>$request->store_id,'branch_id'=>$store->branch_id,'company_id'=>$store->company_id,'invoice_no'=>$code]);
      return redirect('/editinvoice/'.$request->invoice_id);
    }

    public function invoiceapprove($id)
    {

       Invoice::where('id',$id)->update(['status'=>'2']);

       self::updateInvoiceStatus($id,'2');

       return redirect()->back();
    }

    public function updatepistatus(Request $request)
    {
      Invoice::where('id',$request->invoice_id)->update(['status'=>$request->order_status]);

       self::updateInvoiceStatus($request->invoice_id,$request->order_status);

       return redirect()->back();
    }

    public function updateInvoiceStatus($invoice_id,$status){

            $data = new Invoicelog; 
            $data->invoice_id = $invoice_id;
            $data->invoicestatus_id = $status;
            $data->created_by=Auth::user()->id;
            $data->save();
    }

    public function invoice(Request $request)
    {

        $title="Performa Invoices";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $indexes=Invoice::where('delete_status','0')->whereBetween('invoice_date',[$fromdate, $todate]);
        $customers = Customer::where('delete_status','0')->orderBy('name','asc')->get();
        $stores=Store::where('delete_status','0')->where('branch_id',Auth::user()->branch_id)->orderBy('name','asc')->get();
        $locations = Location::where('delete_status','0')->orderBy('name','asc')->get();
        $users=User::get();
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

        $store_id='';
        if(!empty($request->store_id)){
            $store_id=$request->store_id;
            $indexes=$indexes->where('store_id',$store_id);
        }

        $created_by='';
        if(!empty($request->created_by)){
            $created_by=$request->created_by;
            $indexes=$indexes->where('created_by',$created_by);
        }

        $location_id='';
        if(!empty($request->location_id)){
            $location_id=$request->location_id;
            $indexes=$indexes->whereHas('customer', function($q) use ($request)
            {
                $q->where('location_id',$request->location_id);

            });
        }

        $indexes=$indexes->orderBy('id','Desc')->get();
        $statuses=Invoicestatus::orderBy('id','asc')->get();
        $ustatuses=Invoicestatus::where('status_show','2')->get();

        $invoicelist_table = view('enquiry.invoicetable',compact('indexes'))->render();

        return view('enquiry.invoices',compact('title','customers','fromdate','todate','customer_id','indexes','statuses','status_id','stores','store_id','ustatuses','users','created_by','locations','location_id','invoicelist_table'));
    }

    public function viewInvoice($id='')
    {
      $title="View Performa Invoices";
      $log=Invoice::Find($id);
      $statuslogs='';
      $ustatuses=Invoicestatus::where('status_show','2')->get();
      $statuslogs=Orderstatuslog::where('sale_id',$log->sale_id)->orderBy('id','Desc')->get();
      return view('enquiry.viewinvoice',compact('title','log','statuslogs','ustatuses'));
    }

    public function deleteInvoice($id='')
    {
      Invoice::where('id',$id)->update(['delete_status'=>'1']);
      return redirect()->back();
    }

    public function invoicelist($id){

        $title="Convert Sale Order";
        $cdate=date('Y-m-d');
        $invoice=Invoice::find($id);
        $customer = Customer::where('id',$invoice->customer_id)->first();

        $invoices=Invoice::where('delete_status','0')->where('customer_id',$invoice->customer_id)->where('store_id',$invoice->store_id)->where('status','2')->get();
        return view('enquiry.linkpiso',compact('title','customer','invoices','invoice'));
    }

    public function getPIOrderList($ids='')
    {
        $indexes=Invoiceline::whereIn('invoice_id',explode(',', $ids))->where('is_invoiced','1')->get();

        $invoicelist = view('enquiry.invoicelinelist',compact('indexes'))->render();

        echo json_encode(array("invoicelist"=>$invoicelist));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function enquiryrequest(Request $request)
    {
        if(Auth::user()->role=='Customer'){
            $stage_id='1';
        }
        else{
            $stage_id='2';
        }
        $store=Store::find($request->store_id);

        $enquiries=Enquirycart::where('created_by',Auth::user()->id)->where('cart_status','0')->where('delete_status','0')->get();
        $brand_id=$enquiries[0]->brand_id;

        $receipt_count= DB::table('enquiries')->where('branch_id',$store->branch_id)->whereYear('enquiry_date', date('Y'))->count();
        $receipt_count=$receipt_count+1;

        $code='SI'.date('y').'-'.str_pad($store->company_id, 1, '0', STR_PAD_LEFT).''.str_pad($store->branch_id, 2, '0', STR_PAD_LEFT).''.str_pad($request->store_id, 2, '0', STR_PAD_LEFT).''.str_pad($receipt_count, 5, '0', STR_PAD_LEFT);

        $enquiry_id=Enquiry::insertGetId(['customer_id'=>$request->customer_id,'enquiry_date'=>date('Y-m-d'),'stage_id'=>$stage_id,'created_by'=>Auth::user()->id,'brand_id'=>$brand_id,'store_id'=>$request->store_id,'branch_id'=>$store->branch_id,'company_id'=>$store->company_id,'enquiry_no'=>$code]);
        foreach ($enquiries as $log) {

            $product=Product::where('id',$log->product_id)->first();

            $data = new Enquiryline; 
            $data->enquiry_id = $enquiry_id;
            $data->product_id = $log->product_id;
            $data->quantity = $log->quantity;
            $data->created_by=Auth::user()->id;
            $data->save();

            Enquirycart::where('id',$log->id)->update(['cart_status'=>'1']);
        }

        if(Auth::user()->role=='Customer'){
            $this->addEnquirystagelog($enquiry_id,'1');
        }
        else{
            $this->addEnquirystagelog($enquiry_id,'1');
            $this->addEnquirystagelog($enquiry_id,'2');

            if($request->form_type=='quote'){
             // $this->addEnquirystagelog($enquiry_id,'3');
            }
        }
      if($request->form_type=='quote'){
        $alert = 'Quote Added Successfully';
       return redirect('/showenquiryrequest/'.$enquiry_id)->with('alert', $alert);
      
      }
      else{
        $alert = 'Enquiry Added Successfully';
        return redirect('/showenquiryrequest/'.$enquiry_id)->with('alert', $alert);
      }
        
    }

    public function addEnquirystagelog($enquiry_id,$val){

        $data = new Enquirystagelog; 
        $data->enquiry_id = $enquiry_id;
        $data->stage_id = $val;
        $data->created_by=Auth::user()->id;
        $data->save();
    }

    public function deleteInvoiceLine($id='')
    {
      Invoiceline::where('id',$id)->delete();
      echo json_encode(array("id"=>$id));
    }

    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Enquirycart::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/enquiry');
    }

    public function enquiryrequestrest()
    {
        Enquirycart::where('created_by',Auth::user()->id)->where('cart_status','0')->update(['delete_status'=>'1','updated_by'=>Auth::user()->id]);
        return redirect('/enquiry');
    }

    public function quoterequestrest($customer_id)
    {
        Enquirycart::where('created_by',Auth::user()->id)->where('cart_status','0')->update(['delete_status'=>'1','updated_by'=>Auth::user()->id]);
        return redirect('/quote')->with('customers_id',$request->customers_id);
    }

    public function quotecartdestroy($id,$customer_id)
    {
        $data = Enquirycart::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
       return redirect('/quote')->with('customers_id',$request->customers_id);
    }

    

     public function enquiryrequestlist(Request $request)
    {
        $title="Enquiry Request";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $indexes=Enquiry::where('delete_status','0')->where('stage_id','1')->whereBetween('enquiry_date',[$fromdate, $todate]);
        $customers = Customer::where('delete_status','0')->orderBy('name','asc')->get();
        $customer_id='';
        if(!empty($request->customer_id)){
            $customer_id=$request->customer_id;
            $indexes=$indexes->where('customer_id',$customer_id);
        }
        $indexes=$indexes->orderBy('id','Desc')->get();
        $enqyuirytable = view('enquiry.enqyuirytable',compact('indexes'))->render();
        return view('enquiry.enquiries',compact('enqyuirytable','title','customers','fromdate','todate','customer_id'));
    }
}
