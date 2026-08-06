<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class Receipt extends Model
{

    protected static function boot()
   {
      $role = Auth::user()->role;
      parent::boot();
      if ($role == 'SM-01') {
        static::addGlobalScope('created_by', function(Builder $builder) {
            $builder->where('created_by',Auth::user()->id);
        });
      }
      
   }

   public function user(){
       return $this->belongsTo(User::class,'created_by','id');
    }

    public function company(){
       return $this->belongsTo(Company::class);
    }

    public function branch(){
       return $this->belongsTo(Branch::class);
    }

    public function customer(){
       return $this->belongsTo(Customer::class);
    }

    public function paymenttype(){
       return $this->belongsTo(Paymenttype::class);
    }

    public function rvstage(){
       return $this->belongsTo(Rvstage::class);
    }

    public function paymentbank(){
       return $this->belongsTo(Paymentbank::class);
    }


    public function receiptlogs(){
        return $this->hasMany(Receiptlog::class);
    }

    

    
}