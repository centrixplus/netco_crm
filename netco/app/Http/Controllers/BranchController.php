<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Sale;
use App\Models\Salesnumber;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Auth;

class BranchController extends Controller
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
        $title = "Branch";
         $sales=Sale::where('sale_date','=','2024-01-15')->limit(8)->get();
         //print_r($sales); exit();
        $count=129;
        foreach ($sales as $sale) {

            $count++;
             $code='NDSO-'.date('Y').'-'.str_pad($count, 4, '0', STR_PAD_LEFT);


            print_r($sale->id);
            print_r("-");
             
             print_r($count); print_r('<br>');
            // Salesnumber::where('sale_id',$sale->id)->update(['saleno'=>$code]); 
        }
        exit();
        $indexes = Branch::where('delete_status','0')->orderBy('id','Desc')->get();
        $companies = Company::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('branch.index',compact('title','indexes','companies'));  
    }

    public function store(Request $request)
    {

        if(!empty($request->file('logo'))){
            $extension = $request->logo->extension();
            $path=Storage::putFileAs('public/image',$request->file('logo'),'logo'.strtotime(date("Y-m-d h:i:s")).'.'.$extension);
            $img='/storage/image/logo'.strtotime(date("Y-m-d h:i:s")).'.'.$extension;
        }
            $data = Branch::find($request->editid);
            if (empty($data)) { $data = new Branch; $data->created_by=Auth::user()->id; }
            $data->name = $request->name;
            $data->name_ar = $request->name_ar;
            $data->phone = $request->phone;
            $data->email = $request->email;
            $data->company_id = $request->company_id;
            $data->address = $request->address;
            $data->address_ar = $request->address_ar;
            if(!empty($request->file('logo'))){
                $data->logo=$img;
            }
            $data->updated_by=Auth::user()->id;
            $data->save();

        return redirect('/branch');
    }

    public function destroy(Branch $branchs,$id)
    {
        $data = Branch::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/branch');
    }
}
