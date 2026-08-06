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
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Rmunate\Utilities\SpellNumber;
use Auth;
use DB;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth'); 
    }

    public function index()
    {
        $title = "Create Receipt";
        $indexes = Receipt::where('rvstage_id','1')->where('delete_status','0')->orderBy('id','Desc')->get();
        $customers = Customer::where('delete_status','0')->orderBy('id','Desc')->get();
        $branches = Branch::where('delete_status','0')->orderBy('id','Desc')->get();
        $companies = Company::where('delete_status','0')->orderBy('id','Desc')->get();
        $paymenttypes = Paymenttype::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('receipt.index',compact('title','indexes','companies','customers','branches','paymenttypes'));  
    }

    public function edit($id)
    {
        $title = "Edit Receipt";
        $rv = Receipt::where('id',$id)->first();
        $customers = Customer::where('delete_status','0')->orderBy('id','Desc')->get();
        $branches = Branch::where('delete_status','0')->orderBy('id','Desc')->get();
        $companies = Company::where('delete_status','0')->orderBy('id','Desc')->get();
        $paymenttypes = Paymenttype::where('delete_status','0')->orderBy('id','Desc')->get();
        
        return view('receipt.edit',compact('title','rv','customers','branches','companies','paymenttypes'));  
    }

    public function newreceipt(Request $request)
    {
        $title = "Authorization Receipt";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-t');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $indexes = Receipt::where('rvstage_id','1')->where('delete_status','0')->orderBy('id','Desc');
        $customers = Customer::where('delete_status','0')->orderBy('id','Desc')->get();
        $branches = Branch::where('delete_status','0')->orderBy('id','Desc')->get();
        $companies = Company::where('delete_status','0')->orderBy('id','Desc')->get();
        $paymenttypes = Paymenttype::where('delete_status','0')->orderBy('id','Desc')->get();

        $paymenttype_id='';
        if (!empty($request->paymenttype_id)){
            $paymenttype_id=$request->paymenttype_id;
            $indexes = $indexes->where('paymenttype_id',$paymenttype_id);
        }

        $indexes = $indexes->get();
        return view('receipt.newreceipt',compact('title','indexes','companies','customers','branches','paymenttypes','fromdate','todate','paymenttype_id'));  
    }

    

    public function authorized()
    {
        $title = "Authorized Receipt";
        $customers = Customer::where('delete_status','0')->orderBy('id','Desc')->get();
        $branches = Branch::where('delete_status','0')->orderBy('id','Desc')->get();
        $companies = Company::where('delete_status','0')->orderBy('id','Desc')->get();
        $paymenttypes = Paymenttype::where('delete_status','0')->orderBy('id','Desc')->get();
        $indexes = Receipt::whereIn('rvstage_id',[2,4,5,7,8])->where('delete_status','0')->orderBy('id','Desc')->get();

        return view('receipt.authorized',compact('title','indexes','companies','customers','branches','paymenttypes'));  
    }

    public function posted(Request $request)
    {
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-t');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $title = "Posted Receipt";
        $customers = Customer::where('delete_status','0')->orderBy('id','Desc')->get();
        $branches = Branch::where('delete_status','0')->orderBy('id','Desc')->get();
        $companies = Company::where('delete_status','0')->orderBy('id','Desc')->get();
        $paymenttypes = Paymenttype::where('delete_status','0')->orderBy('id','Desc')->get();
        $indexes = Receipt::where('rvstage_id',3)->whereBetween('receipt_date',[$fromdate, $todate])->where('delete_status','0')->orderBy('id','Desc');

        $paymenttype_id='';
        if (!empty($request->paymenttype_id)){
            $paymenttype_id=$request->paymenttype_id;
            $indexes = $indexes->where('paymenttype_id',$paymenttype_id);
        }

        $indexes = $indexes->get();

        return view('receipt.posted',compact('title','indexes','companies','customers','branches','paymenttypes','fromdate','todate','paymenttype_id'));  
    }

    public function reconciled(Request $request)
    {
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-t');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }

        $title = "Reconciled Receipt";
        $indexes = Receipt::where('rvstage_id','6')->whereBetween('receipt_date',[$fromdate, $todate])->where('delete_status','0')->orderBy('id','Desc')->get();
        return view('receipt.reconciled',compact('title','indexes','fromdate','todate'));  
    }

    public function bouncedreceipt(Request $request)
    {
        $title = "Bounced Receipt";

        $fromdate = date('Y-m-01');
        $todate = date('Y-m-t');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }

        $indexes = Receiptlog::where('rvstage_id','5')->whereBetween('created_at',[$fromdate, $todate])->where('delete_status','0')->orderBy('id','Desc')->get();
        return view('receipt.bouncedreceipt',compact('title','indexes','fromdate','todate'));  
    }

    public function deltedreceipt(Request $request)
    {
        $title = "Deleted Receipt";

        $indexes = Receipt::where('delete_status','1')->get();
        return view('receipt.deltedreceipt',compact('title','indexes'));  
    }

    public function store(Request $request)
    {

        if(empty($request->editid) && $request->paymenttype_id!='1'){
            $validatedData = $request->validate([
                'payment_reference_no' => 'required|unique:receipts',
                'customer_id' => 'required',
            ]);
        }

        if($request->editid){
            $validatedData = $request->validate([
                'customer_id' => 'required',
            ]);
        }

        if(empty($request->editid)){
            $exists = Receipt::where('payment_reference_no', $request->payment_reference_no)->first();

            if ($exists) {
                return redirect()->back()->with('error', 'Duplicate receipt detected!');
            }
        }

        if(!empty($request->file('payment_reference_file'))){
            $extension = $request->payment_reference_file->extension();
            $path=Storage::putFileAs('public/image',$request->file('payment_reference_file'),strtotime(date("Y-m-d h:i:s")).'.'.$extension);
            $img='/storage/image/'.strtotime(date("Y-m-d h:i:s")).'.'.$extension;
        }
            $receipt = Receipt::find($request->editid);
            if (empty($receipt)) { $receipt = new Receipt; $receipt->created_by=Auth::user()->id; }
            $receipt->customer_id = $request->customer_id;
            $receipt->company_id = $request->company_id;
            $receipt->branch_id = $request->branch_id;
            $receipt->receipt_date = $request->receipt_date;
            $receipt->receipt_amount = $request->receipt_amount;
            $receipt->paymenttype_id = $request->paymenttype_id;
            $receipt->payment_reference_no = strtoupper($request->payment_reference_no);
            $receipt->payment_collect_date = $request->payment_collect_date;
            $receipt->payment_bank = strtoupper($request->payment_bank);
            $receipt->particulars = strtoupper($request->particulars);
            $receipt->notes = strtoupper($request->notes);
            if(!empty($request->file('payment_reference_file'))){
                $receipt->payment_reference_file=$img;
            }
            $receipt->updated_by=Auth::user()->id;
            $receipt->save();

            if ($request->editid=='') { 
                $receipt_id=$receipt->id;
            }
            else{
                $receipt_id=$request->editid;
            }

            $receipt_count= DB::table('receipts')->where('branch_id',$request->branch_id)->whereYear('receipt_date', date('Y'))->count();

            $code='RV'.date('y').'-'.str_pad($request->company_id, 2, '0', STR_PAD_LEFT).''.str_pad($request->branch_id, 2, '0', STR_PAD_LEFT).'-'.str_pad($receipt_count, 5, '0', STR_PAD_LEFT);

            if ($request->editid=='') { 
                $receiptu = Receipt::find($receipt_id);
                $receiptu->receipt_no = $code;
                $receiptu->notes = $request->notes;
                $receiptu->save();

                $data = new Receiptlog;
                $data->receipt_id = $receipt_id;
                $data->rvstage_id = 1;
                $data->notes = $request->notes;
                $data->created_by=Auth::user()->id;
                $data->save();
            }

        return redirect()->back()->with('success', 'Data updated successfully!');
    }

    
    public function view(Receipt $receipts,$id)
    {
        $title = "Receipt View";
        $index = Receipt::where('id',$id)->orderBy('id','Desc')->first();
        $paymentbanks = Paymentbank::where('delete_status','0')->get();
        $logs=Receiptlog::where('receipt_id',$id)->orderBy('id','desc')->get();
        $rvstages=Rvstage::orderBy('stage','asc')->get();
        return view('receipt.view',compact('title','index','logs','rvstages','paymentbanks'));  
    }

    public function receiptviewreport(Receipt $receipts,$id)
    {
        $title = "Receipt View";
        $index = Receipt::where('id',$id)->orderBy('id','Desc')->first();
        $paymentbanks = Paymentbank::where('delete_status','0')->get();
        $logs=Receiptlog::where('receipt_id',$id)->orderBy('id','desc')->get();
        $rvstages=Rvstage::orderBy('stage','asc')->get();
        return view('receipt.viewreport',compact('title','index','logs','rvstages','paymentbanks'));  
    }

    

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
            return redirect('/login');
        }
        
    }

    public function destroy(Receipt $receipts,$id)
    {
        $data = Receipt::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/receipt');
    }

    public function customerinforeceipt($id='')
    {
        $customer = Customer::where('id',$id)->first();
        $receipt_count=$customer->receipt->where('delete_status','0')->count();
        $new_count=$customer->receipt->where('delete_status','0')->where('rvstage_id','1')->count();
        $authorized_count=$customer->receipt->where('delete_status','0')->where('rvstage_id','2')->count();
        $posted_count=$customer->receipt->where('delete_status','0')->where('rvstage_id','3')->count();
        $onhold_count=$customer->receipt->where('delete_status','0')->where('rvstage_id','4')->count();
        $bounce_count=$customer->receipt->where('delete_status','0')->where('rvstage_id','5')->count();
        $reconciled_count=$customer->receipt->where('delete_status','0')->where('rvstage_id','6')->count();
        $pdc_count=$customer->receipt->where('delete_status','0')->where('rvstage_id','7')->count();
        $transit_count=$customer->receipt->where('delete_status','0')->where('rvstage_id','8')->count();

        $receipt_amount=$customer->receipt->where('delete_status','0')->sum('receipt_amount');
        $receipt_amount=round($receipt_amount,3);

        $new_amount=$customer->receipt->where('delete_status','0')->where('rvstage_id','1')->sum('receipt_amount');
        $new_amount=round($new_amount,3);

        $authorized_amount=$customer->receipt->where('delete_status','0')->where('rvstage_id','2')->sum('receipt_amount');
        $authorized_amount=round($authorized_amount,3);

        $posted_amount=$customer->receipt->where('delete_status','0')->where('rvstage_id','3')->sum('receipt_amount');
        $posted_amount=round($posted_amount,3);

        $onhold_amount=$customer->receipt->where('delete_status','0')->where('rvstage_id','4')->sum('receipt_amount');
        $onhold_amount=round($onhold_amount,3);

        $bounce_amount=$customer->receipt->where('delete_status','0')->where('rvstage_id','5')->sum('receipt_amount');
        $bounce_amount=round($bounce_amount,3);

        $reconciled_amount=$customer->receipt->where('delete_status','0')->where('rvstage_id','6')->sum('receipt_amount');
        $reconciled_amount=round($reconciled_amount,3);

        $pdc_amount=$customer->receipt->where('delete_status','0')->where('rvstage_id','7')->sum('receipt_amount');
        $pdc_amount=round($pdc_amount,3);

        $transit_amount=$customer->receipt->where('delete_status','0')->where('rvstage_id','8')->sum('receipt_amount');
        $transit_amount=round($transit_amount,3);

        if($receipt_count==0){
            $receipt_date='-';
        }
        else{
            $customer_sale = Receipt::where('customer_id',$id)->where('delete_status','0')->orderBy('id','Desc')->first();
            $receipt_date=date('d M, Y',strtotime($customer_sale->receipt_date));
        }

        $receiptslastfive=Receipt::where('customer_id',$id)->where('delete_status','0')->orderBy('id','Desc')->limit(5)->get();
        $receiptslastfivehtml_val = view('receipt.receiptslast',compact('receiptslastfive'))->render();

       echo json_encode(array("new_count"=>$new_count,"receipt_count"=>$receipt_count,"authorized_count"=>$authorized_count,"posted_count"=>$posted_count,"reconciled_count"=>$reconciled_count,"receipt_amount"=>$receipt_amount,"new_amount"=>$new_amount,"authorized_amount"=>$authorized_amount,"posted_amount"=>$posted_amount,"reconciled_amount"=>$reconciled_amount,"receipt_date"=>$receipt_date,"onhold_amount"=>$onhold_amount,"transit_amount"=>$transit_amount,"bounce_amount"=>$bounce_amount,"pdc_amount"=>$pdc_amount,"onhold_count"=>$onhold_count,"transit_count"=>$transit_count,"bounce_count"=>$bounce_count,"pdc_count"=>$pdc_count,"receiptslastfivehtml"=>$receiptslastfivehtml_val));
    }

    public function updatereconcil($ids='',$reconcil_refrence_no)
    {
        $myArray = explode(',', $ids);
        foreach ($myArray as $key) {
            $receiptu = Receipt::find($key);
            $receiptu->rvstage_id = '6';
            $receipt_reconcil_commission=$receiptu->paymenttype->charges;
            $receipt_reconcil_amount=$receiptu->receipt_amount-$receipt_reconcil_commission;
            $receiptu->receipt_reconcil_commission = $receipt_reconcil_commission;
            $receiptu->receipt_reconcil_amount = $receipt_reconcil_amount;
            $receiptu->reconcil_refrence_no = $reconcil_refrence_no;
            $receiptu->updated_by=Auth::user()->id;
            $receiptu->save();

            $data = new Receiptlog;
            $data->receipt_id = $key;
            $data->rvstage_id = '6';
            $data->created_by=Auth::user()->id;
            $data->save();
        }
        echo json_encode(array("sendvalue"=>$ids));
    }

    public function receiptlogstore(Request $request)
    {
            $data = new Receiptlog;
            $data->receipt_id = $request->receipt_id;
            $data->rvstage_id = $request->rvstage_id;
            $data->notes = $request->notes;
            $data->created_by=Auth::user()->id;
            $data->save();

            $receiptu = Receipt::find($request->receipt_id);
            $receiptu->rvstage_id = $request->rvstage_id;
            if($request->rvstage_id=='3'){
                $receiptu->payment_deposit_refrence_number = $request->payment_deposit_refrence_number;
                $receiptu->paymentbank_id = $request->paymentbank_id;
            }

            if($request->rvstage_id=='6'){
               $receipt_reconcil_commission=$receiptu->paymenttype->charges;
               $receipt_reconcil_amount=$receiptu->receipt_amount-$receipt_reconcil_commission;
               $receiptu->reconcil_refrence_no = $request->reconcil_refrence_no;
               $receiptu->receipt_reconcil_commission = $receipt_reconcil_commission;
               $receiptu->receipt_reconcil_amount = $receipt_reconcil_amount;
            }
            $receiptu->updated_by=Auth::user()->id;
            $receiptu->save();

            if($request->rvstage_id==2){
                return redirect('/newreceipt');
            }
            else if($request->rvstage_id==4 || $request->rvstage_id==5 || $request->rvstage_id==7 || $request->rvstage_id==8 || $request->rvstage_id==3){
                return redirect('/authorized');
            }
            else if($request->rvstage_id==6){
                return redirect('/authorizedreceipt');
            }
            else{
                return redirect()->back();
            }
            
    }

    public function receiptdashboard(Request $request)
    {
        $title = "Receipt Dashboard";
 
        $branches = Branch::where('delete_status','0')->orderBy('id','Desc')->get();
        $companies = Company::where('delete_status','0')->orderBy('id','Desc')->get();
        $paymenttypes = Paymenttype::where('delete_status','0')->orderBy('id','Desc')->get();
        $rvstages=Rvstage::get();

        $fromdate = date('Y-m-01');
        $todate = date('Y-m-t');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }

        $indexes = Receipt::where('delete_status','0')->whereBetween('receipt_date',[$fromdate, $todate])->orderBy('id','Desc');
        $customers = Customer::where('delete_status','0')->orderBy('id','Desc');

        $company_id=Auth::user()->company_id;
         $company_info=Company::find($company_id);
        if (!empty($request->company_id)){
            $company_id=$request->company_id;
            $company_info=Company::find($company_id);
            $indexes = $indexes->where('company_id',$company_id);
            $customers = $customers->where('company_id',$company_id);
        }

        $branch_id=Auth::user()->branch_id;
        if (!empty($request->branch_id)){
            $branch_id=$request->branch_id;
            $indexes = $indexes->where('branch_id',$branch_id);
            $customers = $customers->where('branch_id',$branch_id);
        }

        if(Auth::user()->role=='Receipt'){
            $company_id=Auth::user()->company_id;
            $branch_id=Auth::user()->branch_id;
            $company_info=Company::find($company_id);
        }


        $indexes = $indexes->get();
        $customers = $customers->get();

        return view('receipt.dashboard',compact('title','indexes','companies','branches','paymenttypes','fromdate','todate','company_id','branch_id','company_info','rvstages','customers'));
    }

    public function refreshDashboard($fromdate,$todate,$company_id,$branch_id)
    {
        $title = "Receipt Dashboard";
 
        $branches = Branch::where('delete_status','0')->orderBy('id','Desc')->get();
        $companies = Company::where('delete_status','0')->orderBy('id','Desc')->get();
        $paymenttypes = Paymenttype::where('delete_status','0')->orderBy('id','Desc')->get();
        $rvstages=Rvstage::get();

        $indexes = Receipt::where('delete_status','0')->whereBetween('receipt_date',[$fromdate, $todate])->orderBy('id','Desc');
        $customers = Customer::where('delete_status','0')->orderBy('id','Desc');

        $company_id=Auth::user()->company_id;
         $company_info=Company::find($company_id);
        if (!empty($company_id)){
            $company_info=Company::find($company_id);
            $indexes = $indexes->where('company_id',$company_id);
            $customers = $customers->where('company_id',$company_id);
        }

        $branch_id=Auth::user()->branch_id;
        if (!empty($branch_id)){
            $branch_id=$branch_id;
            $indexes = $indexes->where('branch_id',$branch_id);
            $customers = $customers->where('branch_id',$branch_id);
        }

        if(Auth::user()->role=='Receipt'){
            $company_id=Auth::user()->company_id;
            $branch_id=Auth::user()->branch_id;
            $company_info=Company::find($company_id);
        }


        $indexes = $indexes->get();
        $customers = $customers->get();

        $receiptslastfivehtml_val = view('receipt.dashboardcontent',compact('indexes','companies','branches','paymenttypes','fromdate','todate','company_id','branch_id','company_info','rvstages','customers'))->render();

        echo json_encode(array("html"=>$receiptslastfivehtml_val));
    }

    public function receiptreport(Request $request)
    {
        $title = "Receipt Report";

        $fromdate = date('Y-m-01');
        $todate = date('Y-m-t');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        
        $customers = Customer::where('delete_status','0')->orderBy('id','Desc')->get();
        $branches = Branch::where('delete_status','0')->orderBy('id','Desc')->get();
        $companies = Company::where('delete_status','0')->orderBy('id','Desc')->get();
        $paymenttypes = Paymenttype::where('delete_status','0')->orderBy('id','Desc')->get();
        $users = User::orderBy('name','Asc')->get();
        $rvstages=Rvstage::get();

        $fromdate = date('Y-m-01');
        $todate = date('Y-m-t');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }

        $indexes = Receipt::where('delete_status','0')->whereBetween('receipt_date',[$fromdate, $todate])->orderBy('id','Desc');

        $company_id='';
        $company_info='';
        if (!empty($request->company_id)){
            $company_id=$request->company_id;
            $company_info=Company::find($company_id);
            $indexes = $indexes->where('company_id',$company_id);
        }

        $branch_id='';
        if (!empty($request->branch_id)){
            $branch_id=$request->branch_id;
            $indexes = $indexes->where('branch_id',$branch_id);
        }

        $customer_id='';
        if (!empty($request->customer_id)){
            $customer_id=$request->customer_id;
            $indexes = $indexes->where('customer_id',$customer_id);
        }

        $paymenttype_id='';
        if (!empty($request->paymenttype_id)){
            $paymenttype_id=$request->paymenttype_id;
            $indexes = $indexes->where('paymenttype_id',$paymenttype_id);
        }

        $rvstage_id='';
        if (!empty($request->rvstage_id)){
            $rvstage_id=$request->rvstage_id;
            $indexes = $indexes->where('rvstage_id',$rvstage_id);
        }

        $payment_collect_date='';
        if (!empty($request->payment_collect_date)){
            $payment_collect_date=$request->payment_collect_date;
            $indexes = $indexes->where('payment_collect_date',$payment_collect_date);
        }

        $user_id='';
        if (!empty($request->user_id)){
            $user_id=$request->user_id;
            $indexes = $indexes->where('created_by',$user_id);
        }

        

        if(Auth::user()->role=='Receipt'){
            $company_id=Auth::user()->company_id;
            $branch_id=Auth::user()->branch_id;
            $company_info=Company::find($company_id);
        }

        $indexes = $indexes->get();


        return view('receipt.report',compact('title','indexes','companies','customers','branches','paymenttypes','fromdate','todate','company_id','company_info','branch_id','customer_id','paymenttype_id','rvstages','rvstage_id','payment_collect_date','user_id','users'));  
    }

    public function customersearch($company_id='',$branch_id='')
    {
        $customers = Customer::where('delete_status','0');

        if($company_id!=0){
            $customers =$customers->where('company_id',$company_id);
        }

        if($branch_id!=0){
            $customers =$customers->where('branch_id',$branch_id);
        }

        $customers =$customers->get();

        $html = view('receipt.customersearch',compact('customers'))->render();

         echo json_encode(array("html"=>$html));
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
