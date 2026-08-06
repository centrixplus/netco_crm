<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Status extends Model
{
	Protected  $table="status";

	public function sale(){
        return $this->hasMany(Sale::class);
    }

    public function orderstatuslog(){
        return $this->hasMany(Orderstatuslog::class);
    }
}
