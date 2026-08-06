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
        $brandid = 0;
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

            if(!empty($arrresults)){
                foreach($arrresults as $bpart){
                    if(count($bpart)>1){
                         $brand=Brand::find($brandid);
                         $part=$brand->name.$bpart[0];
                         $product = Product::where('name',$part)->where('brand_id',$brandid)->first(); 
                          if(!empty($product)){
                                $price=$bpart[1]*$product->price;
                                $data = new Enquirycart; 
                                $data->brand_id = $request->brand_id;
                                $data->part = $bpart[0];
                                $data->customer_id = Auth::user()->id;
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
                                $data->customer_id = Auth::user()->id;
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
        $enquiries=Enquirycart::where('customer_id',Auth::user()->id)->where('cart_status','0')->where('delete_status','0')->get();
        //print_r($arrresults); exit();
        $parts = Product::where('delete_status','0')->get();
        $brands = Brand::where('delete_status','0')->get();
        return view('enquiry.index',compact('parts','arrresults','textsearch','brands','brandid','enquiries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function enquiryrequest()
    {
        $enquiries=Enquirycart::where('customer_id',Auth::user()->id)->where('cart_status','0')->where('delete_status','0')->get();

        $enquiry_id=Enquiry::insertGetId(['customer_id'=>Auth::user()->id,'enquiry_date'=>date('Y-m-d'),'stage_id'=>'1','created_by'=>Auth::user()->id]);
        foreach ($enquiries as $log) {
            $data = new Enquiryline; 
            $data->enquiry_id = $enquiry_id;
            $data->product_id = $log->product_id;
            $data->quantity = $log->quantity;
            $data->created_by=Auth::user()->id;
            $data->save();

            Enquirycart::where('id',$log->id)->update(['cart_status'=>'1']);
        }
        $this->addEnquirystagelog($enquiry_id,'1');
        $alert = 'Enquiry Added Successfully';
       return redirect('/enquiry')->with('alert', $alert);;
    }

    public function addEnquirystagelog($enquiry_id,$val){

        $data = new Enquirystagelog; 
        $data->enquiry_id = $enquiry_id;
        $data->stage_id = $val;
        $data->created_by=Auth::user()->id;
        $data->save();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
