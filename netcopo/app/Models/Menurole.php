<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menurole extends Model
{
    public function role(){
       return $this->belongsTo(Role::class);
    }
    public function menu(){
       return $this->belongsTo(Menu::class);
    }

    public static function getMenuRole($role_id,$menu_id){
    	$menurole=Menurole::where('role_id',$role_id)->where('menu_id',$menu_id)->first();
    	$menurole_id=0;
    	if($menurole){
    		$menurole_id=$menurole->view_option;
    	}

    	return $menurole_id;
    }
}
