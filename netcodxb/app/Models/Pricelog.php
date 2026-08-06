<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pricelog extends Model
{

	public function product(){
       return $this->belongsTo(Product::class);
    }

    public function getCostAttribute($value)
    {
       return $this->attributes['cost'] = number_format( $value, 2, '.', '' );
    }

    public function getPriceAttribute($value)
    {
       return $this->attributes['price'] = number_format( $value, 2, '.', '' );
    }

     public function getMinPriceAttribute($value)
    {
       return $this->attributes['min_price'] = number_format( $value, 2, '.', '' );
    }

}
