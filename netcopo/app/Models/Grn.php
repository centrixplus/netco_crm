<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
class Grn extends Model
{
	Protected  $table="grn";

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


  public function vendor(){
       return $this->belongsTo(Vendor::class);
  }

  public function grnlines(){
      return $this->hasMany(Grnline::class);
  }

  public function company(){
       return $this->belongsTo(Company::class);
  }

  public function branch(){
     return $this->belongsTo(Branch::class);
  }

  public function user(){
     return $this->belongsTo(User::class,'created_by','id');
  }

  public function userupdated(){
     return $this->belongsTo(User::class,'updated_by','id');
  }

}