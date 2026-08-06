<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Saleline extends Model
{
	public function sale(){
       return $this->belongsTo(Sale::class,'slae_id','id');
    }

    public function product(){
       return $this->belongsTo(Product::class);
    }

    public function getPriceAttribute($value)
    {
       return $this->attributes['price'] = number_format( $value, 3, '.', '' );
    }

    public function getRateAttribute($value)
    {
       return $this->attributes['rate'] = number_format( $value, 3, '.', '' );
    }

    public function enquiryline(){
       return $this->belongsTo(Enquiryline::class);
    }

    public function producthistory(){
       return $this->belongsTo(Producthistory::class,'product_history_id','id');
    }
}