<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;

class SubcategoryController extends Controller
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
        $title = "Subcategory";
        $indexes = Subcategory::where('delete_status','0')->orderBy('id','Desc')->get();
        $categories = Category::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('subcategory.index',compact('title','indexes','categories'));  
    }

    public function store(Request $request)
    {

        if(!empty($request->editid)){
            $data = Subcategory::find($request->editid);
            if (empty($data)) { $data = new Subcategory; }
            $data->category_id = $request->category_id;
            $data->name = $request->name;
            $data->updated_by=Auth::user()->id;
            $data->save();
        }
        else{
            $data = new Subcategory; 
            $data->category_id = $request->category_id;
            $data->name = $request->name;
            $data->created_by=Auth::user()->id;
            $data->save();
        }

        return redirect('/subcategory');
    }

    public function destroy(Subcategory $subcategories,$id)
    {
        $data = Subcategory::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/location');
    }
}
