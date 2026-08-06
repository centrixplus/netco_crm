<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
class Pogrnlog extends Model
{
	  public function grn(){
       return $this->belongsTo(Grn::class);
    }

    public function po(){
       return $this->belongsTo(Po::class);
    }

}