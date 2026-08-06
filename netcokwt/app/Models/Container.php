<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
	protected $table = "containers";

	public function vendor(){
		return $this->belongsTo(Vendor::class);
	}

	public function lines(){
		return $this->hasMany(ContainerLine::class)->where('delete_status','0');
	}

	public function extralines(){
		return $this->hasMany(ContainerExtraLine::class);
	}

	// Distinct box numbers (CtnNo) inside this container.
	public function boxes(){
		return $this->hasMany(ContainerLine::class)
			->where('delete_status','0')
			->select('container_id','box_no')
			->groupBy('container_id','box_no');
	}

	// Resolve comma-separated tag ids to their names.
	public function getTagNamesAttribute(){
		if(empty($this->tags)){ return ''; }
		$ids = array_filter(explode(',', $this->tags));
		if(empty($ids)){ return ''; }
		return Tag::whereIn('id',$ids)->pluck('name')->implode(', ');
	}
}
