<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dealerpricelog extends Model
{

	  public function product(){
       return $this->belongsTo(Product::class);
    }

    public function createdBy(){
       return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
