<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
	public function receipt(){
        return $this->hasMany(Receipt::class);
    }

    public function company(){
       return $this->belongsTo(Company::class);
    }
}