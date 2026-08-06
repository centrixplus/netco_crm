<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Enquiry;
use App\Models\Enquiryline;
use App\Models\Enquirycart;
use App\Models\Stage;
use App\Models\Customer;
use App\Models\Enquirystagelog;
use App\Models\Discount;
use Excel;
use Auth;
use DB;

class EnquiryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title="Enquiry Request";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $indexes=Enquiry::where('delete_status','0')->wherein('stage_id',['1','2'])->whereBetween('enquiry_date',[$fromdate, $todate]);
        $customers = Customer::where('delete_status','0')->orderBy('name','asc')->get();
        $customer_id='';
        if(!empty($request->customer_id)){
            $customer_id=$request->customer_id;
            $indexes=$indexes->where('customer_id',$customer_id);
        }
        $indexes=$indexes->orderBy('id','Desc')->get();
        $enqyuirytable = view('enquiry.enqyuirytable',compact('indexes'))->render();
        $discounts=Discount::where('delete_status','0')->orderBy('percent','Asc')->get();
        return view('enquiry.enquiries',compact('enqyuirytable','title','customers','fromdate','todate','customer_id','discounts'));
    }


     public function show($id)
    {
        
        $enquiry=Enquiry::where('id',$id)->first();
        if($enquiry->stage_id=='1' || $enquiry->stage_id=='2'){
            $title="Show Enquiry Request";
        }
        
        else{
            $title="Show Quotation";
        }

        $enquirylines=$enquiry->enquirylines;
        $enquirystagelogs=$enquiry->enquirystagelogs;
        return view('enquiry.show',compact('enquiry','title','enquirylines','enquirystagelogs'));
    }

    public function updateEnquirystage($id,$val){
        Enquiry::where('id',$id)->update(['stage_id'=>$val,'updated_by'=>Auth::user()->id]);
        $this->addEnquirystagelog($id,$val);
        return redirect()->back();
    }

    public function addEnquirystagelog($enquiry_id,$val){

        $data = new Enquirystagelog; 
        $data->enquiry_id = $enquiry_id;
        $data->stage_id = $val;
        $data->created_by=Auth::user()->id;
        $data->save();
    }

    public function destroy($id)
    {
        $data = Enquiry::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();

        Enquiryline::where('enquiry_id',$id)->update(['delete_status'=>'1','updated_by'=>Auth::user()->id]); 
        Enquirystagelog::where('enquiry_id',$id)->update(['delete_status'=>'1','updated_by'=>Auth::user()->id]); 

        return redirect()->back();
    }

    public function createquote(Request $request)
    {
        $enquiry=Enquiry::where('id',$request->enquiry_id)->first();
        $enquirylines=$enquiry->enquirylines;
        $discount=Discount::find($request->discount_id);
        $today=date('Y-m-d');
        $expiry_date = date('Y-m-d', strtotime("+".$request->no_of_days_expiry." day", strtotime($today)));

        $receipt_count= DB::table('enquiries')->where('branch_id',$enquiry->branch_id)->where('stage_id','>=',3)->whereYear('quote_date', date('Y'))->count();
        $receipt_count=$receipt_count+1;
        $code='SQ'.date('y').'-'.str_pad($enquiry->company_id, 1, '0', STR_PAD_LEFT).''.str_pad($enquiry->branch_id, 2, '0', STR_PAD_LEFT).''.str_pad($enquiry->store_id, 2, '0', STR_PAD_LEFT).''.str_pad($receipt_count, 5, '0', STR_PAD_LEFT);
        
        if($request->discount_id=='2'){
            header('Content-Type: text/html; charset=utf-8');
            //$path = $request->file('import_file')->getRealPath();
            $path = $request->file('pricefile');
            //$data = Excel::load($path)->get();
            $data = Excel::toArray([], $path);
            $data = $data[0];
            $x=0;
            $validate=0;
            $y=0;
            if(count($data) > 0){
                
                foreach ($data as $key => $value) {
                 $y++;
                  if($y>2){
                        $enquiryline=Enquiryline::find($value[1]);
                        if($enquiryline){
                            $product=Product::find($enquiryline->product_id);
                            if($product->cost >= $value[5]){
                                $validate++;
                            }
                        }
                        
                   }
                }
            }
            if(count($data) > 0 && $validate==0){

                

                Enquiry::where('id',$request->enquiry_id)->update(['stage_id'=>3,'updated_by'=>Auth::user()->id,'discount_id'=>$request->discount_id,'no_of_days_expiry'=>$request->no_of_days_expiry,'expiry_date'=>$expiry_date,'quote_date'=>date('Y-m-d H:i:s'),'quote_no'=>$code]);
                foreach ($data as $key => $value) {
                    $x++;
                    
                    if($x>2){
                        $enquiryline=Enquiryline::find($value[1]);
                        $product=Product::find($enquiryline->product_id);
                        $quantity=$value[4];
                        $price=$value[5]*$quantity;
                        Enquiryline::where('id',$value[1])->update(['rate'=>$value[5],'price'=>$price,'product_rate'=>$product->price,'product_lc'=>$product->min_price,'product_c'=>$product->cost,'updated_by'=>Auth::user()->id]);
                    }
                }

                $alert = '<span class="p-1 bg-success bg-soft"><strong>Quotation Created Successfully</strong> </span>';
                $this->addEnquirystagelog($request->enquiry_id,'3');

                return redirect('/quotationlist')->with('alert', $alert);
            }
            else{
                $alert = '<span class="p-1 bg-danger bg-soft">Upload Failed,Landing Cost or RSP is Lesser than Cost Price</span>';

                return redirect()->back()->with('alert', $alert);
            }
        }
        else{
            Enquiry::where('id',$request->enquiry_id)->update(['stage_id'=>3,'updated_by'=>Auth::user()->id,'discount_id'=>$request->discount_id,'no_of_days_expiry'=>$request->no_of_days_expiry,'expiry_date'=>$expiry_date,'quote_date'=>date('Y-m-d H:i:s'),'quote_no'=>$code]);
            foreach ($enquirylines as $log) {

                $product=Product::find($log->product_id);
                if($request->discount_id=='1'){
                    $rate=$product->price;
                }
                else{
                    $markup_rate=round(($product->min_price / 100) * $discount->percent, 2);
                    $rate=$product->min_price+$markup_rate;

                }

                $price=$log->quantity*$rate;

                Enquiryline::where('id',$log->id)->update(['rate'=>$rate,'price'=>$price,'product_rate'=>$product->price,'updated_by'=>Auth::user()->id]);
            }

            $alert = '<span class="p-1 bg-success bg-soft"><strong>Quotation Created Successfully</strong> </span>';
            $this->addEnquirystagelog($request->enquiry_id,'3');
            return redirect('/quotationlist')->with('alert', $alert);
        }

        
    }
    
     public function convertsaleorder($id)
    {
        $title="Quotation to Sale Order";
        $enquiry=Enquiry::where('id',$id)->first();
        $enquirylines=$enquiry->enquirylines;
        $so_enquirylines=Enquiryline::where('enquiry_id',$id)->where('is_so','0')->where('so_quantity','!=','0')->get();
        return view('enquiry.saleorder',compact('enquiry','title','enquirylines','so_enquirylines'));
    }

    public function loadEnqlist($id='')
    {
        $enquiry=Enquiry::where('id',$id)->first();
        $enquirylines=$enquiry->enquirylines;
        $enqyuirytable = view('enquiry.enqlist',compact('enquirylines'))->render();
        echo json_encode(array("enqlist"=>$enqyuirytable));
    }
    
}
