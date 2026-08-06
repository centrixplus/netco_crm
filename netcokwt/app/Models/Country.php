<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
	public function customer(){
        return $this->hasMany(Customer::class);
    }

    public function company(){
        return $this->hasMany(Company::class);
    }
}