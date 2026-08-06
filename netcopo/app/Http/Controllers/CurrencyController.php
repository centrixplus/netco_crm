<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;

class CurrencyController extends Controller
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
        $title = "Currency";
        $indexes = Currency::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('currency.index',compact('title','indexes'));  
    }

    public function store(Request $request)
    {
            $data = Currency::find($request->editid);
            if (empty($data)) { $data = new Currency; $data->created_by=Auth::user()->id; }
            $data->name = $request->name;
            $data->currency_unit_decimal = $request->currency_unit_decimal;
            $data->updated_by=Auth::user()->id;
            $data->save();

        return redirect('/currency');
    }

    public function destroy(Currency $countries,$id)
    {
        $data = Currency::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/currency');
    }
}
