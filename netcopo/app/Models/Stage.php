<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Stage extends Model
{

	public function enquiry(){
        return $this->hasMany(Enquiry::class);
    }

    public function enquirystagelog(){
        return $this->hasMany(Enquirystagelog::class);
    }

}
