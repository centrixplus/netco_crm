<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    public static function FindName($id){
        $product = Product::select('name')->where('id',$id)->first();
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

    public function invoiceline(){
        return $this->hasMany(Invoiceline::class);
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

    public function producthistory(){
        return $this->hasMany(Producthistory::class)->orderBy('id','Desc');
    }

    public function pricelog(){
        return $this->hasMany(Pricelog::class)->orderBy('id','Desc');
    }

    public function dealerpricelogs(){
        return $this->hasMany(Dealerpricelog::class)->orderBy('id','Desc');
    }

    public function productstocklogs(){
        return $this->hasMany(Productstocklog::class)->orderBy('id','Desc');
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
       return $this->attributes['price'] = number_format( $value, 3, '.', '' );
    }

    public function getCostAttribute($value)
    {
       return $this->attributes['cost'] = number_format( $value, 3, '.', '' );
    }

    public function getDealerPriceAttribute($value)
    {
       return $this->attributes['dealer_price'] = number_format( $value, 3, '.', '' );
    }

    public function getMinPriceAttribute($value)
    {
       return $this->attributes['min_price'] = number_format( $value, 3, '.', '' );
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class,'enquirycarts');
    }

    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function color(){
        return $this->belongsTo(Color::class);
    }

    public function uom(){
        return $this->belongsTo(Uom::class);
    }

    public function packagetype(){
        return $this->belongsTo(Packagetype::class);
    }

    public function size(){
        return $this->belongsTo(Size::class);
    }

    public static function caldiscPrice($price,$percent){
       if($price==0 || $percent==0){
        $price_amount=0;
        return number_format( $price_amount, 3, '.', '' );
       }
       $amount=($price/100)*$percent;
       $price_amount=$price-$amount;
       return number_format( $price_amount, 3, '.', '' );
    }

    

}
