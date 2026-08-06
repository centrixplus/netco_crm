<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rvstage extends Model
{

	public function receipts(){
        return $this->hasMany(Receipt::class);
    }
}