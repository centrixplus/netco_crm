<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
class Purchaserequest extends Model
{

    protected static function boot()
    {
      $role = Auth::user()->role;
      parent::boot();
      if ($role != 'PR' && $role != 'admin') {
        static::addGlobalScope('created_by', function(Builder $builder) {
            $builder->where('created_by',Auth::user()->id);
        });
      }
    }

	  public function customer(){
       return $this->belongsTo(Customer::class);
    }

    public function user(){
       return $this->belongsTo(User::class,'created_by','id');
    }

    public function userupdated(){
       return $this->belongsTo(User::class,'updated_by','id');
    }

    public function prstatus(){
       return $this->belongsTo(Prstatus::class,'prstatus_id','id');
    }

    public function requesttype(){
       return $this->belongsTo(Requesttype::class,'request_type','id');
    }

    public function purchaserequestlines(){
        return $this->hasMany(Purchaserequestline::class,'purchase_request_id','id');
    }

     public function purchaserequestlogs(){
        return $this->hasMany(Purchaserequestlog::class);
    }

    

}