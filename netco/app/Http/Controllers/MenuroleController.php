<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Role;
use App\Models\Menurole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;

class MenuroleController extends Controller
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

    public function index(Request $request)
    {

        // $menu=Menu::get();
        // $role=Role::get();

        // foreach ($menu as $m) {
        //     foreach ($role as $r) {
        //         $data = new Menurole; 
        //         $data->menu_id = $m->id;
        //         $data->role_id = $r->id;
        //         $data->created_by=Auth::user()->id;
        //         $data->save();
        //     }
        // }
        $menus=Menu::where('delete_status','0')->orderBy('name','asc')->get();
        $roles=Role::where('delete_status','0')->orderBy('name','asc')->get();

        $title = "Access Rights";
        $indexes = Menurole::where('delete_status','0')->orderBy('id','Desc');

        $menu_id='';
        if(!empty($request->menu_id)){
            $menu_id=$request->menu_id;
            $indexes=$indexes->where('menu_id',$menu_id);
        }

        $role_id='';
        if(!empty($request->role_id)){
            $role_id=$request->role_id;
            $indexes=$indexes->where('role_id',$role_id);
        }

        $indexes =$indexes->get();
        return view('menurole',compact('title','indexes','menus','roles','menu_id','role_id'));  
    }

    public function update($id,$val)
    {

        if(!empty($id)){
            $data = Menurole::find($id);
            if (empty($data)) { $data = new Brand; }
            $data->view_option = $val;
            $data->updated_by=Auth::user()->id;
            $data->save();
        }
        
        echo json_encode(array("id"=>$id));
    }

}
