<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContainerLine extends Model
{
	protected $table = "container_lines";

	// Quantities are whole numbers (no decimals) for containers.
	protected $casts = [
		'quantity'     => 'integer',
		'crm_qty'      => 'integer',
		'received_qty' => 'integer',
	];

	public function container(){
		return $this->belongsTo(Container::class);
	}

	public function receiptlogs(){
		return $this->hasMany(ContainerReceiptLog::class)->orderBy('id','Desc');
	}
}
