<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    public static function FindName($id){
        $product = Products::select('name','name_ar')->where('id',$id)->first();
        $name =  '';
        if($product){ $name =  $product->name; }
		return "$name";
	}

	public function saleline(){
        return $this->hasMany(Saleline::class);
    }

    public function enquiryline(){
        return $this->hasMany(Enquiryline::class);
    }

    public function Stock(){
        return $this->hasMany(Stock::class);
    }

    public function enquirycart(){
        return $this->hasMany(Enquirycart::class);
    }

    public function productstock(){
        return $this->hasOne(Productstock::class);
    }

    public function pricelog(){
        return $this->hasMany(Pricelog::class);
    }

    public function brand(){
       return $this->belongsTo(Brand::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function subcategory(){
        return $this->belongsTo(Subcategory::class);
    }

    public function getPriceAttribute($value)
    {
       return $this->attributes['price'] = number_format( $value, 2, '.', '' );
    }

    public function getCostAttribute($value)
    {
       return $this->attributes['cost'] = number_format( $value, 2, '.', '' );
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class,'enquirycarts');
    }

    

}
