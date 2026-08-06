<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
class Quote extends Model
{
	Protected  $table="quotes";

  protected static function boot()
    {
      $role = Auth::user()->role;
      parent::boot();
      if ($role != 'admin') {
        static::addGlobalScope('created_by', function(Builder $builder) {
            $builder->where('created_by',Auth::user()->id);
        });
      }
    }

	public function customer(){
       return $this->belongsTo(Customer::class);
    }

    public function user(){
       return $this->belongsTo(User::class);
    }

    public function brand(){
       return $this->belongsTo(Brand::class);
    }

    public function quoteline(){
        return $this->hasMany(Quoteline::class);
    }

    public function currency(){
       return $this->belongsTo(Currency::class,'currency_id','id');
    }
}