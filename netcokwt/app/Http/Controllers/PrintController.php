<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Receipt;
use App\Models\Rvstage;
use App\Models\Receiptlog;
use App\Models\Paymenttype;
use App\Models\Paymentbank;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Rmunate\Utilities\SpellNumber;
use Auth;

class PrintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function receiptprint($id)
    {
      if(Auth::User()){
        $title = "Print Receipt";
        $index = Receipt::where('id',$id)->orderBy('id','Desc')->first();
        $arr = explode(".", $index->receipt_amount, 2);
        $main=self::convert_number_to_words($arr[0]);
        $deci=self::convert_number_to_words($arr[1]);
        if($deci >0){
            $amount= $main." ".$index->company->country->currency_unit." And ".$deci." ".$index->company->country->currency_unit_decimal;
        }
        else{
            $amount= $main." ".$index->company->country->currency_unit;
        }
        return view('receipt.print',compact('title','index','amount'));  
      }
      else{
            return redirect('https://www.google.com/');
        }
    }

    public static function convert_number_to_words($number) {

           $no = floor($number);
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
          return $result;
    }
    
}
