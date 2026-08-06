<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquirycart extends Model
{


    public function customer(){
       return $this->belongsTo(Customer::class);
    }

    public function product(){
       return $this->belongsTo(Product::class);
    }

    public function brand(){
       return $this->belongsTo(Brand::class);
    }

   
}
