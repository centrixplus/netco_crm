<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Role;
use App\Models\Menurole;
use Illuminate\Http\Request;
use Auth;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $title = "Menu";
        $indexes = Menu::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('menu.index',compact('title','indexes'));
    }

    public function store(Request $request)
    {
        $data = Menu::find($request->editid);
        $isNew = false;
        if (empty($data)) { $data = new Menu; $data->created_by = Auth::user()->id; $isNew = true; }
        $data->name = $request->name;
        $data->updated_by = Auth::user()->id;
        $data->save();

        // New menu -> give access (view_option = 1) to every active role.
        if ($isNew) {
            $roles = Role::where('delete_status','0')->get();
            foreach ($roles as $r) {
                $exists = Menurole::where('menu_id',$data->id)->where('role_id',$r->id)->exists();
                if (!$exists) {
                    $mr = new Menurole;
                    $mr->menu_id     = $data->id;
                    $mr->role_id     = $r->id;
                    $mr->view_option = 1;
                    $mr->created_by  = Auth::user()->id;
                    $mr->save();
                }
            }
        }

        return redirect('/menu');
    }

    public function destroy($id)
    {
        $data = Menu::find($id);
        $data->delete_status = 1;
        $data->updated_by = Auth::user()->id;
        $data->save();
        return redirect('/menu');
    }
}
