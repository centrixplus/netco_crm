<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContainerReceiptLog extends Model
{
	protected $table = "container_receipt_logs";

	protected $casts = [
		'received_qty' => 'integer',
	];

	public function line(){
		return $this->belongsTo(ContainerLine::class,'container_line_id','id');
	}

	public function container(){
		return $this->belongsTo(Container::class);
	}

	public function creator(){
		return $this->belongsTo(User::class,'created_by','id');
	}

	public function updater(){
		return $this->belongsTo(User::class,'updated_by','id');
	}
}
