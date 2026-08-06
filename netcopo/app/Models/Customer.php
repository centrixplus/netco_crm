<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    protected $fillable = [
        'name', 'location_id'
    ];

     public static function FindName($id){
        $product = Customer::select('name')->where('id',$id)->first();
        $name =  '';
        if($product){ $name =  $product->name; }
		return "$name";
	}

	public function sale(){
        return $this->hasMany(Sale::class)->where('delete_status','0')->orderBy('id','Desc');
    }

    public function receipt(){
        return $this->hasMany(Receipt::class)->where('delete_status','0')->orderBy('id','Desc');
    }

    public function enquiry(){
        return $this->hasMany(Enquiry::class)->where('delete_status','0')->orderBy('id','Desc');
    }

    public function country(){
       return $this->belongsTo(Country::class);
    }

    public function currency(){
       return $this->belongsTo(Currency::class);
    }

    public function saleline()
    {
        return $this->hasManyThrough(Saleline::class, Sale::class,

            'customer_id', // Foreign key on users table...
            'slae_id', // Foreign key on posts table...
            'id', // Local key on countries table...
            'id' // Local key on users table...
        );
    }

    public function enquiryline()
    {
        return $this->hasManyThrough(Enquiryline::class, Enquiry::class,

            'customer_id', // Foreign key on users table...
            'enquiry_id', // Foreign key on posts table...
            'id', // Local key on countries table...
            'id' // Local key on users table...
        );
    }

    public function products()
    {
        return $this->belongsToMany(Product::class,'enquirycarts');
    }
}
