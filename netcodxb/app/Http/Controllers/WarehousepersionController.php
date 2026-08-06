<?php

namespace App\Http\Controllers;

use App\Models\Warehousepersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;

class WarehousepersionController extends Controller
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
        $title = "Warehouse Person";
        $indexes = Warehousepersion::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('warehousepersion.index',compact('title','indexes'));  
    }

    public function store(Request $request)
    {

        if(!empty($request->editid)){
            $data = Warehousepersion::find($request->editid);
            if (empty($data)) { $data = new Warehousepersion; }
            $data->name = $request->name;
            $data->updated_by=Auth::user()->id;
            $data->save();
        }
        else{
            $data = new Warehousepersion; 
            $data->name = $request->name;
            $data->created_by=Auth::user()->id;
            $data->save();
        }

        return redirect('/warehousepersion');
    }

    public function destroy(Warehousepersion $warehousepersion,$id)
    {
        $data = Warehousepersion::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('warehousepersion');
    }
}
