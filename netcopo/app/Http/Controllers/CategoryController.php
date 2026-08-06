<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;

class CategoryController extends Controller
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
        $title = "Category";
        $indexes = Category::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('category.index',compact('title','indexes'));  
    }

    public function store(Request $request)
    {

        if(!empty($request->editid)){
            $data = Category::find($request->editid);
            if (empty($data)) { $data = new Category; }
            $data->name = $request->name;
            $data->updated_by=Auth::user()->id;
            $data->save();
        }
        else{
            $data = new Category; 
            $data->name = $request->name;
            $data->created_by=Auth::user()->id;
            $data->save();
        }

        return redirect('/category');
    }

    public function destroy(Category $categories,$id)
    {
        $data = Category::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/category');
    }
}
