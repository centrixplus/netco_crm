<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;

class CountryController extends Controller
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
        $title = "Country";
        $indexes = Country::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('country.index',compact('title','indexes'));  
    }

    public function store(Request $request)
    {
            $data = Country::find($request->editid);
            if (empty($data)) { $data = new Country; $data->created_by=Auth::user()->id; }
            $data->name = $request->name;
            $data->currency_unit = $request->currency_unit;
            $data->currency_unit_decimal = $request->currency_unit_decimal;
            $data->updated_by=Auth::user()->id;
            $data->save();

        return redirect('/country');
    }

    public function destroy(Country $countries,$id)
    {
        $data = Country::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/country');
    }

    public function activeUsers()
    {

        $title ="Active Users";

        return view('country.active-users', compact('title'));
    }

    public function activelist()
    {
        $activeUsers = DB::table('user_sessions')
            ->join('users', 'user_sessions.user_id', '=', 'users.id')
            ->select('users.name', 'users.email', 'user_sessions.ip_address', 'user_sessions.user_agent', 'user_sessions.last_activity')
            ->orderBy('user_sessions.last_activity', 'desc')
            ->get();

        return view('country.activelist', compact('activeUsers'));
    }
}
