<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
     public static function FindName($id){
        $product = Vendor::select('name')->where('id',$id)->first();
        $name =  '';
        if($product){ $name =  $product->name; }
		return "$name";
	}

	public function country(){
       return $this->belongsTo(Country::class);
    }

    public function currency(){
       return $this->belongsTo(Currency::class);
    }

	
}
