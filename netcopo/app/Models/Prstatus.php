<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
class Prstatus extends Model
{
	public function prstatus(){
       return $this->belongsTo(Prstatus::class,'prstatus_id','id');
    }
}