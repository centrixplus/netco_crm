<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function menuroles(){
        return $this->hasMany(Menurole::class)->where('delete_status','0');
    }
}
