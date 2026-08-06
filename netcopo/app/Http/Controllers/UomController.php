<?php

namespace App\Http\Controllers;

use App\Models\Uom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;

class UomController extends Controller
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
        $title = "Uom";
        $indexes = Uom::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('uom.index',compact('title','indexes'));  
    }

    public function store(Request $request)
    {

        if(!empty($request->editid)){
            $data = Uom::find($request->editid);
            if (empty($data)) { $data = new Uom; }
            $data->name = $request->name;
            $data->quantity = $request->quantity;
            $data->updated_by=Auth::user()->id;
            $data->save();
        }
        else{
            $data = new Uom; 
            $data->name = $request->name;
            $data->quantity = $request->quantity;
            $data->created_by=Auth::user()->id;
            $data->save();
        }

        return redirect('/uom');
    }

    public function destroy(Uom $uoms,$id)
    {
        $data = Uom::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/uom');
    }
}
