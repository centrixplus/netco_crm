<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	public function branch(){
        return $this->hasMany(Branch::class);
    }

    public function receipt(){
        return $this->hasMany(Receipt::class);
    }

    public function country(){
       return $this->belongsTo(Country::class);
    }

     public function store(){
        return $this->hasMany(Store::class);
    }
}