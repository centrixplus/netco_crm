<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContainerExtraLine extends Model
{
	protected $table = "container_extra_lines";

	protected $casts = [
		'received_qty' => 'integer',
	];

	public function container(){
		return $this->belongsTo(Container::class);
	}

	public function creator(){
		return $this->belongsTo(User::class,'created_by','id');
	}
}
