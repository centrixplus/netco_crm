<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
class Invoice extends Model
{
	  public function customer(){
       return $this->belongsTo(Customer::class);
    }

    public function invoicelines(){
        return $this->hasMany(Invoiceline::class);
    }

    public function user(){
       return $this->belongsTo(User::class,'created_by','id');
    }

    public function userupdated(){
       return $this->belongsTo(User::class,'updated_by','id');
    }

    public function invoicelogs(){
        return $this->hasMany(Invoicelog::class)->orderBy('id',"Desc");
    }

    public function invoicestatus(){
       return $this->belongsTo(Invoicestatus::class,'status','id');
    }

    public function store(){
       return $this->belongsTo(Store::class);
    }

    public function sale(){
       return $this->belongsTo(Sale::class);
    }

    public function getAmountSoAttribute($value)
    {
       return $this->attributes['amount_so'] = number_format( $value, 3, '.', '' );
    }
}