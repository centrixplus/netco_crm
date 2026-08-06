<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
class Quoteline extends Model
{
	public function quote(){
       return $this->belongsTo(Quote::class);
    }

    public function getPriceAttribute($value)
    {
       return $this->attributes['price'] = number_format( $value, 2, '.', '' );
    }

}