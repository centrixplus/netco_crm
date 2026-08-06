<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{

	protected $fillable = [
        'name'
    ];

	public function customer(){
        return $this->hasMany(Customer::class);
    }

    public function sale()
	{
	    return $this->hasManyThrough(Sale::class, Customer::class);
	}
}
