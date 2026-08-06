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

}