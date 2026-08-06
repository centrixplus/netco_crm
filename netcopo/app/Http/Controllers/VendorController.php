<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Sale;
use App\Models\Country;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Auth;

class VendorController extends Controller
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

    public function index()
    {
        $title = "Vendors";
        $indexes = Vendor::where('delete_status','0')->orderBy('id','Desc')->get();
        $countries=Country::where('delete_status','0')->orderBy('name','Asc')->get();
        $companies = Company::where('delete_status','0')->orderBy('name','Desc')->get();
        $branches = Branch::where('delete_status','0')->orderBy('name','Desc')->get();
        $currencies = Currency::where('delete_status','0')->orderBy('name','Desc')->get();
        return view('vendor.index',compact('title','indexes','countries','companies','branches','currencies'));  
    }

    public function store(Request $request)
    {
        
        if(!empty($request->editid)){
            $data = Vendor::find($request->editid);
            if (empty($data)) { $data = new Vendor; }
            $data->name = strtoupper($request->name);
            $data->mobile = $request->mobile;
            $data->code = $request->code;
            $data->note = $request->note;
            $data->country_id=$request->country_id;
            $data->currency_id=$request->currency_id;
            $data->company_id=$request->company_id;
            $data->branch_id=$request->branch_id;
            $data->is_active = $request->is_active;
            $data->updated_by=Auth::user()->id;
            $data->save();
        }
        else{
            $validatedData = $request->validate([
                'code' => 'required|unique:vendors',
            ]);
            $data = new Vendor; 
            $data->name = strtoupper($request->name);
            $data->mobile = $request->mobile;
            $data->code = $request->code;
            $data->country_id=$request->country_id;
            $data->company_id=$request->company_id;
            $data->currency_id=$request->currency_id;
            $data->branch_id=$request->branch_id;
            $data->note = $request->note;
            $data->is_active = $request->is_active;
            $data->created_by=Auth::user()->id;
            $data->save();
        }

        return redirect('/vendor');
    }

    public function view($id='')
    {
         $title='Vendor Info';
         $vendor = Vendor::find($id);
         $indexes= Vendor::find($id)->sale;
         $payments= Vendor::find($id)->payment;
         $saletable = view('sale.salestable',compact('indexes'))->render();
         $paymenttable = view('payment.paymenttable',compact('payments'))->render();
         return view('vendor.view',compact('title','saletable','vendor','paymenttable'));  
    }

    public function destroy(Vendor $vendor,$id)
    {
        $data = Vendor::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/vendor');
    }

    public function vendorinfo($id='')
    {
        $vendor = Vendor::where('id',$id)->first();
        
        echo json_encode(array("name"=>$vendor->name,"mobile"=>$vendor->mobile,"area"=>$vendor->area,"note"=>$vendor->note,"vendor"=>$vendor));
    }

    public function vendorsearch($company_id='',$branch_id='')
    {
        $vendors = Vendor::where('delete_status','0');

        if($company_id!=0){
            $vendors =$vendors->where('company_id',$company_id);
        }

        if($branch_id!=0){
            $vendors =$vendors->where('branch_id',$branch_id);
        }

        $vendors =$vendors->get();

        $html = view('vendor.vendorsearch',compact('vendors'))->render();

         echo json_encode(array("html"=>$html));
    }
}
