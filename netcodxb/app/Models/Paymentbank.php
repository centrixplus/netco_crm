<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paymentbank extends Model
{

    public function company(){
       return $this->belongsTo(Company::class);
    }

    public function branch(){
       return $this->belongsTo(Branch::class);
    }
}