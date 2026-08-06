<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;

class GroupController extends Controller
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
        $title = "Group";
        $indexes = Group::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('group.index',compact('title','indexes'));  
    }

    public function store(Request $request)
    {

        if(!empty($request->editid)){
            $data = Group::find($request->editid);
            if (empty($data)) { $data = new Group; }
            $data->name = $request->name;
            $data->updated_by=Auth::user()->id;
            $data->save();
        }
        else{
            $data = new Group; 
            $data->name = $request->name;
            $data->created_by=Auth::user()->id;
            $data->save();
        }

        return redirect('/group');
    }

    public function destroy(Group $groups,$id)
    {
        $data = Group::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/group');
    }
}
