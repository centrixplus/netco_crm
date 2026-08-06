<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContainerUploadUnmatched extends Model
{
	protected $table = "container_upload_unmatched";

	public $timestamps = false;

	public function uploadlog(){
		return $this->belongsTo(ContainerUploadLog::class,'upload_log_id','id');
	}
}
