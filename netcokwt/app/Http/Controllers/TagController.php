<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Auth;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $title = "Tags";
        $indexes = Tag::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('tag.index',compact('title','indexes'));
    }

    public function store(Request $request)
    {
        $data = Tag::find($request->editid);
        if (empty($data)) { $data = new Tag; $data->created_by = Auth::user()->id; }
        $data->name = $request->name;
        $data->updated_by = Auth::user()->id;
        $data->save();
        return redirect('/tag');
    }

    public function destroy($id)
    {
        $data = Tag::find($id);
        $data->delete_status = 1;
        $data->updated_by = Auth::user()->id;
        $data->save();
        return redirect('/tag');
    }
}
