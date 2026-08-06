<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producthistory extends Model
{

	Protected  $table="product_histories";
	
    public function product(){
       return $this->belongsTo(Product::class);
    }

    public function saleline(){
        return $this->hasMany(Saleline::class);
    }

    public function enquiryline(){
        return $this->hasMany(Enquiryline::class);
    }
}