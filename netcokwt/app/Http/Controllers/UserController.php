<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Store;
use App\Models\Userrole;
use App\Models\Role;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Auth;
use DB;

class UserController extends Controller
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

        // $receipt_count= DB::table('receipts')->where('branch_id','1')->whereYear('receipt_date', date('Y'))->count();
        // print_r($receipt_count); exit();
        $title = "Users";
        $indexes = User::orderBy('id','Desc')->get();
        $userroles=Role::orderBy('id','Desc')->get();
        $roles = Userrole::where('delete_status','0')->orderBy('name','Asc')->get();
        $branches = Branch::where('delete_status','0')->orderBy('id','Desc')->get();
        $companies = Company::where('delete_status','0')->orderBy('id','Desc')->get();
        $stores = Store::where('delete_status','0')->orderBy('id','Desc')->get();
        
        return view('users',compact('title','indexes','userroles','roles','branches','companies','stores'));  
    }

     public function profile()
    {
        $title = "Users";
        $indexes = User::orderBy('id','Desc')->get();
        return view('user',compact('title','indexes'));  
    }

    public function store(Request $request)
    {
        if($request->editid==0){
            $data = new User; 
            $data->name = $request->name;
            $data->email = $request->email;
            $data->role = $request->role;
            $data->branch_id = $request->branch_id;
            $data->company_id = $request->company_id;
            $data->store_id = $request->store_id;
            $data->password = Hash::make($request->password);
            $data->save();
        }
        else{
            $data = User::find($request->editid);
            $data->name = $request->name;
            $data->email = $request->email;
            $data->branch_id = $request->branch_id;
            $data->company_id = $request->company_id;
            $data->store_id = $request->store_id;
            if(!empty($request->password)){ $data->password = Hash::make($request->password); }
            $data->role = $request->role;
            $data->save();
        }
        

        return redirect('/user');
    }

    public function update(Request $request)
    {
        if(!empty($request->editid)){
            $data = User::find($request->editid);
            if (empty($data)) { $data = new User; }
            $data->password = Hash::make($request->password);
            $data->save();
        }

        return redirect()->back();
    }

    


}
