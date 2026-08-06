<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class Enquirycart extends Model
{

	protected static function boot()
    {
        $role = Auth::user()->role;
        parent::boot();
        if ($role == 'Customer') {
          static::addGlobalScope('created_by', function(Builder $builder) {
              $builder->where('created_by',Auth::user()->id);
          });
        }
     }

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
