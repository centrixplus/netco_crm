<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paymenttype extends Model
{

	public function receipts(){
        return $this->hasMany(Receipt::class);
    }
}