<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Auth;

class SettingsController extends Controller
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
        $title = "Settings";
        $setting = Setting::where('id','1')->first();
        return view('setting',compact('title','setting'));  
    }

    public function store(Request $request)
    {
        if(!empty($request->file('logo'))){
            $extension = $request->logo->extension();
            $path=Storage::putFileAs('public/image',$request->file('logo'),'logo.'.$extension);
            $img='/storage/image/logo.'.$extension;
        }
        
        $id='1';
        $data = Setting::find($id);
        if (empty($data)) { $data = new Setting; }
        $data->name = $request->name;
        $data->mobile = $request->mobile;
        $data->address = $request->address;
        $data->address2 = $request->address2;
        $data->city = $request->city;
        $data->pincode = $request->pincode;
        $data->gst = $request->gst;
        $data->tax = $request->tax;
        $data->currency = $request->currency;
        if(!empty($request->file('logo'))){
            $data->logo=$img;
        }
        $data->save();

        return redirect()->back();
    }
}
