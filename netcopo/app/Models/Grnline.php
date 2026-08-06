<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
class Grnline extends Model
{
	  public function grn(){
       return $this->belongsTo(Grn::class);
    }

    public function grnline(){
       return $this->belongsTo(Grnline::class);
    }

    public function poline(){
       return $this->belongsTo(Poline::class);
    }

    public function po(){
       return $this->belongsTo(Po::class);
    }

    public function product(){
       return $this->belongsTo(Product::class);
  	}

    public function getPriceAttribute($value)
    {
       return $this->attributes['price'] = round($value, 3);
    }

    public function getCostAttribute($value)
    {
       return $this->attributes['cost'] = round($value, 3);
    }

    // public function getPriceAttribute($value)
    // {
    //    return $this->attributes['price'] = number_format( $value, 3, '.', '' );
    // }

}