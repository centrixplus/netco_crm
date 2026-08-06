<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContainerStatusLog extends Model
{
	protected $table = "container_status_logs";

	public function container(){
		return $this->belongsTo(Container::class);
	}

	public function creator(){
		return $this->belongsTo(User::class,'created_by','id');
	}
}
