<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
class Poline extends Model
{
	  public function po(){
       return $this->belongsTo(Po::class);
    }

    public function product(){
       return $this->belongsTo(Product::class);
  	}

    // public function getPriceAttribute($value)
    // {
    //    return $this->attributes['price'] = number_format( $value, 3, '.', '' );
    // }

    public function getPriceAttribute($value)
    {
       return $this->attributes['price'] = round($value, 3);
    }

    public function getTotalPriceAttribute($value)
    {
       return $this->attributes['total_price'] = round($value, 3);
    }

}