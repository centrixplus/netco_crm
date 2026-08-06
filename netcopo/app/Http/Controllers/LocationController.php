<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;

class LocationController extends Controller
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
        $title = "Location";
        $indexes = Location::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('location.index',compact('title','indexes'));  
    }

    public function store(Request $request)
    {

        if(!empty($request->editid)){
            $data = Location::find($request->editid);
            if (empty($data)) { $data = new Location; }
            $data->name = $request->name;
            $data->updated_by=Auth::user()->id;
            $data->save();
        }
        else{
            $data = new Location; 
            $data->name = $request->name;
            $data->created_by=Auth::user()->id;
            $data->save();
        }

        return redirect('/location');
    }

    public function destroy(Location $locations,$id)
    {
        $data = Location::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/location');
    }
}
