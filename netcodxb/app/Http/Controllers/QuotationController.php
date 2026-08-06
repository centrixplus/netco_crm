<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Enquiry;
use App\Models\Enquiryline;
use App\Models\Enquirycart;
use App\Models\Stage;
use App\Models\Customer;
use App\Models\Enquirystagelog;
use App\Models\Discount;
use App\Models\Setting;
use App\Models\Sale;
use App\Models\Saleline;
use App\Models\Orderstatuslog;
use App\Models\Productstock;
use App\Models\Producthistory;

use Excel;
use Auth;
use DB;

class QuotationController extends Controller
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
        $title="Quotation";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $indexes=Enquiry::where('delete_status','0')->wherein('stage_id',['3','4'])->whereBetween('quote_date',[$fromdate, $todate1]);
        $customers = Customer::where('delete_status','0')->orderBy('name','asc')->get();
        $customer_id='';
        if(!empty($request->customer_id)){
            $customer_id=$request->customer_id;
            $indexes=$indexes->where('customer_id',$customer_id);
        }
        $indexes=$indexes->orderBy('id','Desc')->get();
        $quotationtable = view('enquiry.quotationtable',compact('indexes'))->render();
        $discounts=Discount::where('delete_status','0')->orderBy('percent','Asc')->get();
        return view('enquiry.quotation',compact('quotationtable','title','customers','fromdate','todate','customer_id','discounts'));
    }

    public function quote($id)
    {
        $title="Quotation";
        $quote=Enquiry::find($id);
        $quotelines=$quote->enquirylines;
        $setting=Setting::find('1');
        return view('enquiry.quote',compact('quotelines','quote','setting','title'));
    }

    public function quoteapprove($id='')
    {
        $val='4';
        Enquiry::where('id',$id)->update(['stage_id'=>$val,'updated_by'=>Auth::user()->id]);
        $this->addEnquirystagelog($id,$val);

        return redirect()->back();
    }

    public function addEnquirystagelog($enquiry_id,$val){

        $data = new Enquirystagelog; 
        $data->enquiry_id = $enquiry_id;
        $data->stage_id = $val;
        $data->created_by=Auth::user()->id;
        $data->save();
    }

    
    public function linksaleorder(Request $request)
    {
        $enquiry_ids=implode(',', $request->enquiry);
        $no_qty=0;
        $grand_total=0;
        $textsearch = $request->search_text;
        $arrresults = array_chunk(preg_split('/\s+/u', $textsearch),2);
        $verify=0;
        $wronglist='<h5>Unmatch Part List with Selected Quote:</h5><table class="table table-bordered mb-3"><thead class="table-light"><tr><td>S.No</td><td>Part No</td><td>Qty</td></tr></thead><tbody>';
        if(!empty($arrresults)){
            foreach($arrresults as $bpart){
                $product = Product::where('name',$request->brand.$bpart[0])->first(); 
                if(!empty($product)){
                    $enquiryline=Enquiryline::where('product_id',$product->id)->whereIn('enquiry_id',explode(',', $enquiry_ids))->first();
                    if(empty($enquiryline)){
                        $verify++;
                        $wronglist.='<tr><td>'.$verify.'</td><td>'.$bpart[0].'</td><td>'.$bpart[1].'</td></tr>';
                    }
                }
                else{
                    $verify++;
                    $wronglist.='<tr><td>'.$verify.'</td><td>'.$bpart[0].'</td><td>'.$bpart[1].'</td></tr>';
                    
                }
            }
        }
        $wronglist.='</tbody></table>';

        $enquiry=Enquiry::wherein('id',explode(',', $enquiry_ids))->first();

        $receipt_count= DB::table('sales')->where('branch_id',$enquiry->branch_id)->whereYear('sale_date', date('Y'))->count();
        $receipt_count=$receipt_count+1;
        $code='SO'.date('y').'-'.str_pad($enquiry->company_id, 1, '0', STR_PAD_LEFT).''.str_pad($enquiry->branch_id, 2, '0', STR_PAD_LEFT).''.str_pad($enquiry->store_id, 2, '0', STR_PAD_LEFT).''.str_pad($receipt_count, 5, '0', STR_PAD_LEFT);

        if($verify==0){
                   $slae_id=Sale::insertGetId(['customer_id'=>$request->customer_id,'sale_date'=>date('Y-m-d'),'priority'=>$request->priority,'note'=>$request->comment,'created_by'=>Auth::user()->id,'created_at'=>date('Y-m-d H:i:s'),'enquiry_id'=>$enquiry_ids,'sales_from'=>'Enquiry','store_id'=>$enquiry->store_id,'branch_id'=>$enquiry->branch_id,'company_id'=>$enquiry->company_id,'sale_no'=>$code]);

                    foreach($arrresults as $bpart){

                        print_r($bpart[0]);
                        $product = Product::where('name',$request->brand.$bpart[0])->first(); 
                        $enquiryline=Enquiryline::where('product_id',$product->id)->wherein('enquiry_id',explode(',', $enquiry_ids))->first();
                        Enquiryline::where('id',$enquiryline->id)->update(['so_quantity'=>$bpart[1],'is_so'=>'1']);

                        if($bpart[1] >0){
       
                            $productstock=Productstock::where('product_id',$product->id)->first();
                            $c_qty=$productstock->qty+$productstock->t_qty ;
                            if($c_qty >= $bpart[1]){
                                $l_qty=0;
                                $l_price=0;
                                $reason='0';
                            }
                            else{
                                $req_qty=$bpart[1];
                                $l_qty=$req_qty-$c_qty;
                                $l_price=$l_qty*$enquiryline->rate;
                                if($productstock->pdc=='0'){
                                    $reason='PDC';
                                }
                                else{
                                    $reason='NETCO';

                                }
                            }

                            $price=$bpart[1]*$enquiryline->rate;
                            $cost_price=$enquiryline->product_c*$bpart[1];

                            $data = new Saleline; 
                            $data->slae_id = $slae_id;
                            $data->enquiryline_id=$enquiryline->id;
                            $data->product_id = $enquiryline->product_id;
                            $data->quantity = $bpart[1];
                            $data->rate = $enquiryline->rate;
                            $data->cost = $enquiryline->product_c;
                            $data->cost_price = $cost_price;
                            $data->price = $price;
                            $data->reason = $reason;
                            $data->l_qty = $l_qty;
                            $data->l_price = $l_price;
                            $data->created_by=Auth::user()->id;
                            $data->save();

                            $no_qty=$no_qty+$bpart[1];
                            $grand_total=$grand_total+$price;
                        }

                   }

                   $data = new Orderstatuslog; 
                        $data->sale_id = $slae_id;
                        $data->document_no = 0;
                        $data->status = 'New';
                        $data->created_by=Auth::user()->id;
                        $data->save();

                        $grand_total=number_format($grand_total, 2, '.', '' );

                        Enquiryline::where('id',$enquiryline->id)->update(['so_quantity'=>$bpart[1],'is_so'=>'1']);

                        $val='5';
                        Enquiry::whereIn('id',explode(',', $enquiry_ids))->update(['stage_id'=>$val,'sale_id'=>$slae_id,'updated_by'=>Auth::user()->id,'sale_date'=>date('Y-m-d')]);
                        $ids=explode(',', $enquiry_ids);
                        foreach ($ids as $enquiry_id) {
                            $this->addEnquirystagelog($enquiry_id,$val);
                        }
                        

                        Sale::where('id',$slae_id)->update(['grand_total'=>$grand_total,'no_of_qty'=>$no_qty]);
                        $msg='<span style="padding:3px;" class="bg-success">Sale Order Created Successfully and Order No is: '.Sale::saleno($slae_id).'</span>';
                        return redirect('/showsales/'.$slae_id)->with('alert', $msg);
        }
        else{
            $alert = '<span class="p-1 m-1 bg-danger bg-soft">Sale Order Failed to Create,Non Quote Items are Uploded.</span>';
            $alert.='<br><br><span class="bg-soft bg-warning"><b>'.$wronglist.'</b></span>';
             return redirect()->back()->with('alert', $alert);
        }
    }

    public function store(Request $request)
    {
        $enquiry=Enquiry::find($request->enquiry_id);
        $customer_id=$enquiry->customer_id;
                    
        $slae_id=Sale::insertGetId(['customer_id'=>$customer_id,'sale_date'=>date('Y-m-d'),'priority'=>$request->priority,'note'=>$request->comment,'created_by'=>Auth::user()->id,'created_at'=>date('Y-m-d H:i:s'),'enquiry_id'=>$request->enquiry_id]);

        header('Content-Type: text/html; charset=utf-8');
        //$path = $request->file('import_file')->getRealPath();
        $path = $request->file('sofile');
        //$data = Excel::load($path)->get();
        $data = Excel::toArray([], $path);
        $data = $data[0];
        $x=0;
        $validate=0;
        $y=0;
        $no_qty=0;
        $grand_total=0;
        if(count($data) > 0){
            
            foreach ($data as $key => $value) {
             $y++;
              if($y>2){
                    $enquiryline=Enquiryline::find($value[1]);
                    $product=Product::find($enquiryline->product_id);

                    Enquiryline::where('id',$enquiryline->id)->update(['so_quantity'=>$value[4],'is_so'=>'1']);

                        if($value[4] >0){
                            $product=Product::where('id',$product->id)->first();
                            $productstock=Productstock::where('product_id',$$product->id)->first();
                            $c_qty=$productstock->qty+$productstock->t_qty ;
                            if($c_qty >= $value[4]){
                                $l_qty=0;
                                $l_price=0;
                                $reason='0';
                            }
                            else{
                                $req_qty=$value[4];
                                $l_qty=$req_qty-$c_qty;
                                $l_price=$l_qty*$enquiryline->rate;
                                if($productstock->pdc=='0'){
                                    $reason='PDC';
                                }
                                else{
                                    $reason='NETCO';

                                }
                            }
                            $price=$value[4]*$enquiryline->rate;
                            $cost_price=$enquiryline->product_c*$value[4];
                            $data = new Saleline; 
                            $data->slae_id = $slae_id;
                            $data->product_id = $enquiryline->product_id;
                            $data->quantity = $value[4];
                            $data->rate = $enquiryline->rate;
                            $data->cost = $enquiryline->product_c;
                            $data->cost_price = $cost_price;
                            $data->price = $price;
                            $data->reason = $reason;
                            $data->l_qty = $l_qty;
                            $data->l_price = $l_price;
                            $data->created_by=Auth::user()->id;
                            $data->save();

                            $no_qty=$no_qty+$value[4];
                            $grand_total=$grand_total+$price;
                        }

                    $data = new Orderstatuslog; 
                    $data->sale_id = $slae_id;
                    $data->document_no = 0;
                    $data->status = 'New';
                    $data->created_by=Auth::user()->id;
                    $data->save();

                    $grand_total=number_format($enquiryline->grand_total, 2, '.', '' );

                    Enquiryline::where('id',$enquiryline->id)->update(['so_quantity'=>$value[4],'is_so'=>'1']);

                    $val='5';
                    Enquiry::where('id',$request->enquiry_id)->update(['stage_id'=>$val,'updated_by'=>Auth::user()->id]);
                    $this->addEnquirystagelog($request->enquiry_id,$val);

                    Sale::where('id',$slae_id)->update(['grand_total'=>$grand_total,'no_of_qty'=>$no_qty]);
                    $sale=Sale::find($slae_id);
                    
                    $msg='<span style="padding:3px;" class="bg-success">Sale Order Created Successfully and Order No is: '.$sale->sale_no.'</span>';
                    return redirect('/showsales/'.$slae_id)->with('alert', $msg);
               }
            }
        }
    }

    
    public function saleorder($id){

        $title="Convert Sale Order";
        $cdate=date('Y-m-d');
        $quote=Enquiry::find($id);
        $customer = Customer::where('id',$quote->customer_id)->first();

        $quotes=Enquiry::where('delete_status','0')->where('customer_id',$quote->customer_id)->where('store_id',$quote->store_id)->where('brand_id',$quote->brand_id)->where('stage_id','4')->where('expiry_date','>=',$cdate)->get();
        $brands=Brand::where('delete_status','0')->get();
        return view('enquiry.linksaleorder',compact('title','customer','quotes','quote','brands'));
    }

    public function getQuoteOrderList($ids='')
    {
        $indexes=Enquiryline::whereIn('enquiry_id',explode(',', $ids))->get();

        $quotelist = view('enquiry.quotelist',compact('indexes'))->render();

        echo json_encode(array("quotelist"=>$quotelist));
    }

    public function convertedquote(Request $request)
    {
        $title="Converted Quotation";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $indexes=Enquiry::where('delete_status','0')->where('stage_id','5')->whereBetween('sale_date',[$fromdate, $todate1]);
        $customers = Customer::where('delete_status','0')->orderBy('name','asc')->get();
        $customer_id='';
        if(!empty($request->customer_id)){
            $customer_id=$request->customer_id;
            $indexes=$indexes->where('customer_id',$customer_id);
        }
        $indexes=$indexes->orderBy('id','Desc')->get();
        $discounts=Discount::where('delete_status','0')->orderBy('percent','Asc')->get();
        return view('enquiry.convertquote',compact('indexes','title','customers','fromdate','todate','customer_id','discounts'));
    }
}