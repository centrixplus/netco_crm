<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{

	public function product(){
        return $this->hasMany(Product::class);
    }

    public function uploadlog(){
        return $this->hasMany(Uploadlog::class);
    }

    public function salelines()
	{
	    return $this->hasManyThrough(Saleline::class, Product::class);
	}

}
