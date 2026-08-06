<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
class Purchaserequestlog extends Model
{
	public function purchaserequest(){
       return $this->belongsTo(Purchaserequest::class);
    }

    public function user(){
       return $this->belongsTo(User::class,'created_by','id');
    }

    public function prstatus(){
       return $this->belongsTo(Prstatus::class,'prstatus_id','id');
    }


}