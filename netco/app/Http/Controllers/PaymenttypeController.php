<?php

namespace App\Http\Controllers;

use App\Models\Paymenttype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;

class PaymenttypeController extends Controller
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
        $title = "Paymenttype";
        $indexes = Paymenttype::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('paymenttype.index',compact('title','indexes'));  
    }

    public function store(Request $request)
    {
            $data = Paymenttype::find($request->editid);
            if (empty($data)) { $data = new Paymenttype; $data->created_by=Auth::user()->id; }
            $data->name = $request->name;
            $data->updated_by=Auth::user()->id;
            $data->save();

        return redirect('/paymenttype');
    }

    public function destroy(Paymenttype $paymenttype,$id)
    {
        $data = Paymenttype::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/paymenttype');
    }
}
