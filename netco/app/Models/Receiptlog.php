<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receiptlog extends Model
{

    public function receipt(){
       return $this->belongsTo(Receipt::class);
    }

    public function rvstage(){
       return $this->belongsTo(Rvstage::class);
    }

}