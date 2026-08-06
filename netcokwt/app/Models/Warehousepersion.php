<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehousepersion extends Model
{

	protected $fillable = [
        'name'
    ];

	public function sale(){
        return $this->hasMany(Sale::class);
    }


}
