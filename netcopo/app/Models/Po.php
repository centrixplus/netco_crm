<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
class Po extends Model
{
	Protected  $table="pos";

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

  public function polines(){
      return $this->hasMany(Poline::class);
  }

  public function status(){
       return $this->belongsTo(Postatus::class,'postatus_id','id');
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