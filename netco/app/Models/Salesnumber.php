<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
class Salesnumber extends Model
{
	public function sale(){
       return $this->belongsTo(Sale::class);
    }

}