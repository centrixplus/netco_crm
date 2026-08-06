<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Paymentbank;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Auth;

class PaymentbankController extends Controller
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
        $title = "Payment Bank";
        $indexes = Paymentbank::where('delete_status','0')->orderBy('id','Desc')->get();
        $branches = Branch::where('delete_status','0')->orderBy('id','Desc')->get();
        $companies = Company::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('paymentbank.index',compact('title','indexes','companies','branches'));  
    }

    public function store(Request $request)
    {
            $data = Paymentbank::find($request->editid);
            if (empty($data)) { $data = new Paymentbank; $data->created_by=Auth::user()->id; }
            $data->name = $request->name;
            $data->company_id = $request->company_id;
            $data->branch_id = $request->branch_id;
            $data->updated_by=Auth::user()->id;
            $data->save();

        return redirect('/paymentbank');
    }

    public function destroy(Paymentbank $paymentbanks,$id)
    {
        $data = Paymentbank::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/paymentbank');
    }
}
