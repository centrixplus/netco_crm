<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
class Invoiceline extends Model
{
	public function customer(){
       return $this->belongsTo(Customer::class);
    }

    public function product(){
       return $this->belongsTo(Product::class);
    }

    public function invoice(){
       return $this->belongsTo(Invoice::class);
    }

    public function getRspAttribute($value)
    {
       return $this->attributes['rsp'] = number_format( $value, 3, '.', '' );
    }

    public function getRspOrginalAttribute($value)
    {
       return $this->attributes['rsp_orginal'] = number_format( $value, 3, '.', '' );
    }

    public function getTargetPriceAttribute($value)
    {
       return $this->attributes['target_price'] = number_format( $value, 3, '.', '' );
    }

    public function getCostAttribute($value)
    {
       return $this->attributes['cost'] = number_format( $value, 3, '.', '' );
    }

    public function getAmountAttribute($value)
    {
       return $this->attributes['amount'] = number_format( $value, 3, '.', '' );
    }

}