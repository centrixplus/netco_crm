<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;

class CompanyController extends Controller
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
        $title = "Company";
        $indexes = Company::where('delete_status','0')->orderBy('id','Desc')->get();
        $countries = Country::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('company.index',compact('title','indexes','countries'));  
    }

    public function store(Request $request)
    {
            $data = Company::find($request->editid);
            if (empty($data)) { $data = new Company; $data->created_by=Auth::user()->id; }
            $data->name = $request->name;
            $data->name_ar = $request->name_ar;
            $data->quote = $request->quote;
            $data->country_id = $request->country_id;
            $data->updated_by=Auth::user()->id;
            $data->save();

        return redirect('/company');
    }

    public function destroy(Company $countries,$id)
    {
        $data = Company::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/company');
    }
}
