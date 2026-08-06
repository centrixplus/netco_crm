<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orderstatuslog extends Model
{
	public function sale(){
       return $this->belongsTo(Sale::class);
    }

    public function orderstatus(){
       return $this->belongsTo(Status::class,'status_id','id');
    }
}