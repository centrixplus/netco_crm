<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
	public function invoice(){
        return $this->hasMany(Invoice::class);
    }

    public function company(){
       return $this->belongsTo(Company::class);
    }

    public function branch(){
       return $this->belongsTo(Branch::class);
    }
}