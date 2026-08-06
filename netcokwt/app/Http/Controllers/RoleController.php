<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Menu;
use App\Models\Menurole;
use Illuminate\Http\Request;
use Auth;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $title = "Role";
        $indexes = Role::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('role.index',compact('title','indexes'));
    }

    public function store(Request $request)
    {
        $data = Role::find($request->editid);
        $isNew = false;
        if (empty($data)) { $data = new Role; $data->created_by = Auth::user()->id; $isNew = true; }
        $data->name = $request->name;
        $data->updated_by = Auth::user()->id;
        $data->save();

        // New role -> give access (view_option = 1) to every active menu.
        if ($isNew) {
            $menus = Menu::where('delete_status','0')->get();
            foreach ($menus as $m) {
                $exists = Menurole::where('menu_id',$m->id)->where('role_id',$data->id)->exists();
                if (!$exists) {
                    $mr = new Menurole;
                    $mr->menu_id     = $m->id;
                    $mr->role_id     = $data->id;
                    $mr->view_option = 1;
                    $mr->created_by  = Auth::user()->id;
                    $mr->save();
                }
            }
        }

        return redirect('/role');
    }

    public function destroy($id)
    {
        $data = Role::find($id);
        $data->delete_status = 1;
        $data->updated_by = Auth::user()->id;
        $data->save();
        return redirect('/role');
    }
}
