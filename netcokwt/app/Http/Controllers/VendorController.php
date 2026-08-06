<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $title = "Vendor";
        $indexes = Vendor::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('vendor.index',compact('title','indexes'));
    }

    public function store(Request $request)
    {
            $data = Vendor::find($request->editid);
            if (empty($data)) { $data = new Vendor; $data->created_by=Auth::user()->id; }
            $data->name = $request->name;
            $data->email = $request->email;
            $data->phone = $request->phone;
            $data->address = $request->address;
            $data->note = $request->note;
            $data->updated_by=Auth::user()->id;
            $data->save();

        return redirect('/vendor');
    }

    public function destroy(Vendor $vendor,$id)
    {
        $data = Vendor::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/vendor');
    }
}
