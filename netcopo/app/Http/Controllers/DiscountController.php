<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;

class DiscountController extends Controller
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
        $title = "Discount";
        $indexes = Discount::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('discount.index',compact('title','indexes'));  
    }

    public function store(Request $request)
    {

        if(!empty($request->editid)){
            $data = Discount::find($request->editid);
            if (empty($data)) { $data = new Discount; }
            $data->name = $request->name;
            $data->percent = $request->percent;
            $data->updated_by=Auth::user()->id;
            $data->save();
        }
        else{
            $data = new Discount; 
            $data->name = $request->name;
            $data->percent = $request->percent;
            $data->created_by=Auth::user()->id;
            $data->save();
        }

        return redirect('/discount');
    }

    public function destroy(Discount $Discounts,$id)
    {
        $data = Discount::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/discount');
    }
}
