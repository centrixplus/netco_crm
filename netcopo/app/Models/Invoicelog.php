<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
class Invoicelog extends Model
{
	public function invoice(){
       return $this->belongsTo(Invoice::class);
    }

    public function user(){
       return $this->belongsTo(User::class,'created_by','id');
    }

    public function invoicestatus(){
       return $this->belongsTo(Invoicestatus::class,'invoicestatus_id','id');
    }


}