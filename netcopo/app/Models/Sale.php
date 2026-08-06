<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;
class Sale extends Model
{
	protected $fillable = [
        'customer_id'
    ];

	Protected  $table="sales";

	 protected static function boot()
	 {
	    $role = Auth::user()->role;
	    parent::boot();
	    if ($role == 'user') {
	      static::addGlobalScope('created_by', function(Builder $builder) {
	          $builder->where('created_by',Auth::user()->id);
	      });
	    }
	    if ($role == 'warehouse') {
	      static::addGlobalScope('created_by', function(Builder $builder) {
	          $builder->wherein('status_id',['2','3','10']);
	      });
	    }
	 }
	
	public function customer(){
       return $this->belongsTo(Customer::class);
    }

    public function saleline(){
        return $this->hasMany(Saleline::class)->where('delete_status','0');
    }

    public function orderstatus(){
       return $this->belongsTo(Status::class,'status_id','id');
    }

    public function warehousepersion(){
       return $this->belongsTo(Warehousepersion::class,'warehousepersion_id','id');
    }

    public function user(){
       return $this->belongsTo(User::class,'created_by','id');
    }

      public static function getAmountInwords($number) {
   
		   $no = round($number);
		   $point = round($number - $no, 2) * 100;
		   $hundred = null;
		   $digits_1 = strlen($no);
		   $i = 0;
		   $str = array();
		   $words = array('0' => '', '1' => 'one', '2' => 'two',
		    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
		    '7' => 'seven', '8' => 'eight', '9' => 'nine',
		    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
		    '13' => 'thirteen', '14' => 'fourteen',
		    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
		    '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
		    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
		    '60' => 'sixty', '70' => 'seventy',
		    '80' => 'eighty', '90' => 'ninety');
		   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
		   while ($i < $digits_1) {
		     $divider = ($i == 2) ? 10 : 100;
		     $number = floor($no % $divider);
		     $no = floor($no / $divider);
		     $i += ($divider == 10) ? 1 : 2;
		     if ($number) {
		        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
		        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
		        $str [] = ($number < 21) ? $words[$number] .
		            " " . $digits[$counter] . $plural . " " . $hundred
		            :
		            $words[floor($number / 10) * 10]
		            . " " . $words[$number % 10] . " "
		            . $digits[$counter] . $plural . " " . $hundred;
		     } else $str[] = null;
		  }
		  $str = array_reverse($str);
		  $result = implode('', $str);
		  $points = ($point) ?
		    "." . $words[$point / 10] . " " . 
		          $words[$point = $point % 10] : '';
		    $result=$result . " Only";
		    $result=ucwords($result);
		    return $result;
	}

	public  static function saleno($id='')
	{
		$code='NDSO-'.str_pad($id, 4, '0', STR_PAD_LEFT);
		return $code;
	}

	public function getGrandTotalAttribute($value)
    {
       return $this->attributes['grand_total'] = number_format( $value, 3, '.', '' );
    }
}