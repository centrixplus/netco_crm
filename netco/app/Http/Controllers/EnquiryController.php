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

class EnquiryController extends Controller
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


     public function show($id)
    {
        $title="Show Enquiry Request";
        $enquiry=Enquiry::where('id',$id)->first();
        $enquirylines=$enquiry->enquirylines;
        $enquirystagelogs=$enquiry->enquirystagelogs;
        return view('enquiry.show',compact('enquiry','title','enquirylines','enquirystagelogs'));
    }

    public function destroy($id)
    {
        $data = Enquiry::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();

        Enquiryline::where('enquiry_id',$id)->update(['delete_status'=>'1','updated_by'=>Auth::user()->id]); 
        Enquirystagelog::where('enquiry_id',$id)->update(['delete_status'=>'1','updated_by'=>Auth::user()->id]); 

        return redirect()->back();
    }

    
}
