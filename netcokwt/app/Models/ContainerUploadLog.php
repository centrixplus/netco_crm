<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContainerUploadLog extends Model
{
	protected $table = "container_upload_logs";

	public function container(){
		return $this->belongsTo(Container::class);
	}

	public function creator(){
		return $this->belongsTo(User::class,'created_by','id');
	}

	public function matchedReceipts(){
		return $this->hasMany(ContainerReceiptLog::class,'upload_log_id','id');
	}

	public function unmatched(){
		return $this->hasMany(ContainerUploadUnmatched::class,'upload_log_id','id');
	}
}
