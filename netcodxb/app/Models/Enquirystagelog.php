<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquirystagelog extends Model
{
    public function enquiry(){
       return $this->belongsTo(Enquiry::class);
    }

    public function stage(){
       return $this->belongsTo(Stage::class);
    }

}
