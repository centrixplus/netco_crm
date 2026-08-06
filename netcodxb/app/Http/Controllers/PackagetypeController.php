<?php

namespace App\Http\Controllers;

use App\Models\Packagetype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;

class PackagetypeController extends Controller
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
        $title = "Packagetype";
        $indexes = Packagetype::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('packagetype.index',compact('title','indexes'));  
    }

    public function store(Request $request)
    {

        if(!empty($request->editid)){
            $data = Packagetype::find($request->editid);
            if (empty($data)) { $data = new Packagetype; }
            $data->name = $request->name;
            $data->updated_by=Auth::user()->id;
            $data->save();
        }
        else{
            $data = new Packagetype; 
            $data->name = $request->name;
            $data->created_by=Auth::user()->id;
            $data->save();
        }

        return redirect('/packagetype');
    }

    public function destroy(Packagetype $packagetypes,$id)
    {
        $data = Packagetype::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/packagetype');
    }
}
