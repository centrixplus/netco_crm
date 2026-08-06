<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    public function customer(){
       return $this->belongsTo(Customer::class);
    }

    public function enquirylines(){
        return $this->hasMany(Enquiryline::class)->where('delete_status','0');
    }

    public function stage(){
       return $this->belongsTo(Stage::class);
    }

    public function enquirystagelogs(){
        return $this->hasMany(Enquirystagelog::class)->where('delete_status','0')->orderBy('id','desc');
    }

    public function discount(){
       return $this->belongsTo(Discount::class);
    }
}
