<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\Location;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Auth;

class CustomerController extends Controller
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
        $title = "Customers";
        $indexes = Customer::where('delete_status','0')->orderBy('id','Desc')->get();
        $locations=Location::where('delete_status','0')->orderBy('name','Asc')->get();
        $companies = Company::where('delete_status','0')->orderBy('id','Desc')->get();
        $branches = Branch::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('customer.index',compact('title','indexes','locations','companies','branches'));  
    }

    public function store(Request $request)
    {
        
        if(!empty($request->editid)){
            $data = Customer::find($request->editid);
            if (empty($data)) { $data = new Customer; }
            $data->name = strtoupper($request->name);
            $data->mobile = $request->mobile;
            $data->code = $request->code;
            $data->area = $request->area;
            $data->note = $request->note;
            $data->location_id=$request->location_id;
            $data->company_id=$request->company_id;
            $data->branch_id=$request->branch_id;
            $data->is_active = $request->is_active;
            $data->updated_by=Auth::user()->id;
            $data->save();
        }
        else{
            $validatedData = $request->validate([
                'code' => 'required|unique:customers',
            ]);
            $data = new Customer; 
            $data->name = strtoupper($request->name);
            $data->mobile = $request->mobile;
            $data->code = $request->code;
            $data->area = $request->area;
            $data->location_id=$request->location_id;
            $data->company_id=$request->company_id;
            $data->branch_id=$request->branch_id;
            $data->note = $request->note;
            $data->is_active = $request->is_active;
            $data->created_by=Auth::user()->id;
            $data->save();
        }

        return redirect('/customer');
    }

    public function view($id='')
    {
         $title='Customer Info';
         $customer = Customer::find($id);
         $indexes= Customer::find($id)->sale;
         $payments= Customer::find($id)->payment;
         $saletable = view('sale.salestable',compact('indexes'))->render();
         $paymenttable = view('payment.paymenttable',compact('payments'))->render();
         return view('customer.view',compact('title','saletable','customer','paymenttable'));  
    }

    public function destroy(Customer $customer,$id)
    {
        $data = Customer::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/customer');
    }

    public function customerinfo($id='')
    {
        $customer = Customer::where('id',$id)->first();
        $sales_count=$customer->sale->where('delete_status','0')->count();
        $new_count=$customer->sale->where('delete_status','0')->where('status','New')->count();
        $processing_count=$customer->sale->where('delete_status','0')->where('status','Processing')->count();
        $delivered_count=$customer->sale->where('delete_status','0')->where('status','Delivered')->count();

        $sales_amount=$customer->sale->where('delete_status','0')->sum('grand_total');
        $new_amount=$customer->sale->where('delete_status','0')->where('status','New')->sum('grand_total');
        $processing_amount=$customer->sale->where('delete_status','0')->where('status','Processing')->sum('grand_total');
        $delivered_amount=$customer->sale->where('delete_status','0')->where('status','Delivered')->sum('grand_total');

        if($sales_count==0){
            $order_date='-';
        }
        else{
            $customer_sale = Sale::where('customer_id',$id)->where('delete_status','0')->orderBy('id','Desc')->first();
            $order_date=date('d M, Y',strtotime($customer_sale->sale_date));
        }

        $invoice_count=Invoice::where('customer_id',$id)->where('delete_status','0')->count();
        $invoice_amount=Invoice::where('customer_id',$id)->where('delete_status','0')->sum('amount_so');
        $invoice=Invoice::where('customer_id',$id)->where('delete_status','0')->orderBy('id','desc')->first();
        $invoice_date='';
        if($invoice){
            $invoice_date=date('d M, Y',strtotime($invoice->invoice_date));
        }

        $invoice_count_new=Invoice::where('customer_id',$id)->where('delete_status','0')->where('status','1')->count();
        $invoice_amount_new=Invoice::where('customer_id',$id)->where('delete_status','0')->where('status','1')->sum('amount_so');

        $invoice_count_apvd=Invoice::where('customer_id',$id)->where('delete_status','0')->where('status','2')->count();
        $invoice_amount_apvd=Invoice::where('customer_id',$id)->where('delete_status','0')->where('status','2')->sum('amount_so');

        $invoice_count_so=Invoice::where('customer_id',$id)->where('delete_status','0')->where('status','3')->count();
        $invoice_amount_so=Invoice::where('customer_id',$id)->where('delete_status','0')->where('status','3')->sum('amount_so');

       echo json_encode(array("name"=>$customer->name,"mobile"=>$customer->mobile,"area"=>$customer->area,"note"=>$customer->note,"new_count_new"=>$new_count,"sales_count"=>$sales_count,"processing_count"=>$processing_count,"delivered_count"=>$delivered_count,"sales_amount"=>$sales_amount,"new_amount"=>$new_amount,"processing_amount"=>$processing_amount,"delivered_amount"=>$delivered_amount,"order_date"=>$order_date,"customer"=>$customer,"invoice_date"=>$invoice_date,"invoice_amount"=>$invoice_amount,"invoice_count"=>$invoice_count,"invoice_amount_so"=>$invoice_amount_so,"invoice_count_so"=>$invoice_count_so,"invoice_amount_apvd"=>$invoice_amount_apvd,"invoice_count_apvd"=>$invoice_count_apvd,"invoice_amount_new"=>$invoice_amount_new,"invoice_count_new"=>$invoice_count_new));
    }
}
