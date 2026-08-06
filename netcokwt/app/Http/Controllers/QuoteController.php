<?php

namespace App\Http\Controllers;
use Excel;
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
use App\Models\Quote;
use App\Models\Quoteline;
use App\Models\Currency;
use Auth;
use DB;

class QuoteController extends Controller
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
    public function create(Request $request)
    {
       // print_r($request->all()); exit();
        $textsearch = '';
        $arrresults = '';
        $customers=Customer::where('delete_status','0')->orderBy('name','Asc')->get();
        $brandid = 0;
    
        //print_r($arrresults); exit();
        $brands = Brand::where('delete_status','0')->get();
        $currencies = Currency::get();
        
        $users = User::get();

        $stores=Store::where('delete_status','0')->where('branch_id',Auth::user()->branch_id)->orderBy('name','asc')->get();
        return view('quote.create',compact('arrresults','textsearch','brands','brandid','customers','stores','users','currencies'));
    }

    public function store(Request $request){
        if (!empty($request->search_text) || !empty($request->import_file)) {
            $brandid = $request->brand_id;

            $quote_count= DB::table('quotes')->whereYear('quote_date', date('Y'))->count();
            $quote_count=$quote_count+1;

            $code='QO'.date('y').'-'.str_pad($quote_count, 5, '0', STR_PAD_LEFT);
            $usr=User::where('id',$request->user_id)->first();
            $quote_id=Quote::insertGetId(['customer_id'=>$request->customer_id,'quote_date'=>$request->quote_date,'note'=>$request->note,'created_by'=>Auth::user()->id,'brand_id'=>$request->brand_id,'user_id'=>$request->user_id,'created_at'=>date('Y-m-d H:i:s'),'quote_no'=>$code,'price_level'=>$request->price_level,'currency_id'=>$request->currency,'store_id'=>$usr->store_id]);

            if (!empty($request->search_text)) {
                $textsearch = $request->search_text;
                $arrresults = array_chunk(preg_split('/\s+/u', $textsearch),3);
                if(!empty($arrresults)){
                    foreach($arrresults as $bpart){
                        $qty=0;
                        if(count($bpart)>1){
                            $data = new Quoteline; 
                            $data->quote_id = $quote_id;
                            $data->product = $bpart[0];
                            $data->quantity = $bpart[1];
                            $data->price = $bpart[2];
                            $data->save();
                        }
                    }
                }
            }
            else{
                header('Content-Type: text/html; charset=utf-8');
                $path = $request->file('import_file');

                $data = Excel::toArray([], $path);
                $data = $data[0];
                $x=0;
                unset($data[0]);
                $chunks=array_chunk($data, 10000);
                if(count($chunks) > 0){
                    foreach ($chunks as $key => $data) {
                        if(count($data) > 0){
                            foreach ($data as $key => $value) {
                                $data = new Quoteline; 
                                $data->quote_id = $quote_id;
                                $data->product = $value[0];
                                $data->quantity = $value[1];
                                $data->price = $value[3];
                                $data->description = $value[2];
                                $data->save(); 
                            } 
                        }
                    }
                }
            }
            return redirect('view/quote/'.$quote_id);
        }
        else{
            $alert="<span class=\"text-danger\">Error: Need to Enter Quote Info or Upload File</span>";
            return redirect('/quote')->with('alert', $alert);
        }

        
    }

    public function index(Request $request)
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
        $indexes = Quote::whereBetween('quote_date',[$fromdate, $todate]);
        $currencies = Currency::get();
        $customer_id='';
        if(!empty($request->customer_id)){
            $customer_id=$request->customer_id;
            $indexes=$indexes->where('customer_id',$customer_id);
        }
        $brand_id='';
        if(!empty($request->brand_id)){
            $brand_id=$request->brand_id;
            $indexes=$indexes->where('brand_id',$brand_id);
        }
        $user_id='';
        if(!empty($request->user_id)){
            $user_id=$request->user_id;
            $indexes=$indexes->where('created_by',$user_id);
        }
        $currency_id='';
        if(!empty($request->currency)){
            $currency_id=$request->currency;
            $indexes=$indexes->where('currency_id',$currency_id);
        }
        $indexes=$indexes->where('delete_status','0')->orderBy('id','Desc')->get();

        return view('quote.index',compact('title','indexes','fromdate','todate','customers','customer_id','brand_id','users','user_id','brands','currencies','currency_id'));  
    }

    public function view($id)
    {
        $title="View Quote";
        $quote = Quote::where('id',$id)->first();

        return view('quote.view',compact('quote','title'));
    }

    public function edit($id)
    {
        $title="Edit Quote";
        $quote = Quote::where('id',$id)->first();

        $customers=Customer::where('delete_status','0')->orderBy('name','Asc')->get();
    
        //print_r($arrresults); exit();
        $brands = Brand::where('delete_status','0')->get();

        $users = User::get();
        $currencies = Currency::get();

        return view('quote.edit',compact('quote','title','customers','brands','users','currencies'));
    }

    public function update(Request $request)
    {
        Quote::where('id',$request->edit_id)->update(['customer_id'=>$request->customer_id,'quote_date'=>$request->quote_date,'note'=>$request->note,'updated_by'=>Auth::user()->id,'brand_id'=>$request->brand_id,'user_id'=>$request->user_id,'updated_at'=>date('Y-m-d H:i:s'),'price_level'=>$request->price_level,'currency_id'=>$request->currency]);

        return redirect('view/quote/'.$request->edit_id);
    }

    public function delete($id='')
    {
      Quote::where('id',$id)->update(['delete_status'=>'1','updated_by'=>Auth::user()->id]);

      Quoteline::where('quote_id',$id)->update(['delete_status'=>'1']);
      return redirect()->back();
    }

    

}