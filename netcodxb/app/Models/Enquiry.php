<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class Enquiry extends Model
{
    protected static function boot()
     {
        $role = Auth::user()->role;
        parent::boot();
        if ($role == 'Customer') {
          static::addGlobalScope('customer_id', function(Builder $builder) {
              $builder->where('customer_id',Auth::user()->customer_id);
          });
        }
     }

    public function customer(){
       return $this->belongsTo(Customer::class);
    }

    public function discount(){
       return $this->belongsTo(Discount::class);
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
}
