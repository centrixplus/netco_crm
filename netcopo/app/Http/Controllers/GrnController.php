<?php

namespace App\Http\Controllers;
use Excel;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Location;
use App\Models\Productstock;
use App\Models\Producthistory;
use App\Models\Brand;
use App\Models\Enquiry;
use App\Models\Enquiryline;
use App\Models\Enquirycart;
use App\Models\Stage;
use App\Models\Vendor;
use App\Models\Enquirystagelog;
use App\Models\Invoice;
use App\Models\Invoiceline;
use App\Models\Invoicelog;
use App\Models\Invoicestatus;
use App\Models\Sale;
use App\Models\Saleline;
use App\Models\Orderstatuslog;
use App\Models\Store;
use App\Models\User;
use App\Models\Quote;
use App\Models\Quoteline;
use App\Models\Po;
use App\Models\Poline;
use App\Models\Currency;
use App\Models\Postatus;
use App\Models\Grn;
use App\Models\Grnline;
use App\Models\Pogrnlog;
use App\Models\Company;
use App\Models\Branch;
use Auth;
use DB;

class GrnController extends Controller
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
    public function create(Request $request)
    {
        $vendors=Vendor::where('delete_status','0')->orderBy('name','Asc')->get();
        $companies = Company::where('delete_status','0')->orderBy('id','Desc')->get();
        $branches = Branch::where('delete_status','0')->orderBy('id','Desc')->get();
        return view('grn.create',compact('vendors','companies','branches'));
    }

    public function store(Request $request)
    {   
        header('Content-Type: text/html; charset=utf-8');
        $path = $request->file('import_file');
        $data = Excel::toArray([], $path)[0];
        unset($data[0]); // remove header row

        if (count($data) == 0) {
            return back()->with('error', 'Uploaded file is empty.');
        }

        $errors = [];

        // ✅ Preload all unique values to minimize DB hits
        $poNumbers = collect($data)->pluck(4)->filter()->unique()->toArray();
        $partNumbers = collect($data)->pluck(0)->filter()->unique()->toArray();

        $pos = Po::whereIn('po_no', $poNumbers)->get()->keyBy('po_no');
        $products = Product::whereIn('name', $partNumbers)->get()->keyBy('name');

        foreach ($data as $index => $row) {
            $rowNum = $index + 2; // Excel header is row 1

            $partNumber   = trim($row[0] ?? '');
            $description  = trim($row[1] ?? '');
            $qty          = trim($row[2] ?? '');
            $vendorPrice  = trim($row[3] ?? '');
            $poNumber     = trim($row[4] ?? '');

            // 1️⃣ Check PO existence
            $po = $pos[$poNumber] ?? null;
            if (!$po) {
                $errors[] = "Row {$rowNum}: PO Number '{$poNumber}' not found in POS table.";
                continue;
            }

            // 2️⃣ Check Vendor match
            if ($po->vendor_id != $request->vendor_id) {
                $errors[] = "Row {$rowNum}: Vendor does not match for PO '{$poNumber}'.";
                continue;
            }

            // 3️⃣ Check Product existence
            $product = $products[$partNumber] ?? null;
            if (!$product) {
                $errors[] = "Row {$rowNum}: Product '{$partNumber}' not found in Products table.";
                continue;
            }

            // 4️⃣ Validate numeric Qty & Price
            if (!is_numeric($qty) || $qty <= 0) {
                $errors[] = "Row {$rowNum}: Invalid Quantity '{$qty}'.";
                continue;
            }
            if (!is_numeric($vendorPrice) || $vendorPrice <= 0) {
                $errors[] = "Row {$rowNum}: Invalid VendorPrice '{$vendorPrice}'.";
                continue;
            }

            // 5️⃣ Check existing PO line
            $existingLine = Poline::where('po_id', $po->id)
                ->where('product_id', $product->id)
                ->first();

            if (!$existingLine) {
                $errors[] = "Row {$rowNum}: Product '{$partNumber}' not found in PO lines for '{$poNumber}'.";
                continue;
            }

            // 6️⃣ Check VendorPrice matches existing line
            if ($request->price_check=="on" && $existingLine->price != $vendorPrice) {
                $errors[] = "Row {$rowNum}: VendorPrice '{$vendorPrice}' does not match existing line price '{$existingLine->price}' for '{$partNumber}'.";
                continue;
            }

            // 7️⃣ Check balance quantity
            if ($qty > $existingLine->balance_quantity) {
                $errors[] = "Row {$rowNum}: Quantity '{$qty}' exceeds balance quantity ({$existingLine->balance_quantity}) for '{$partNumber}'.";
                continue;
            }
        }

        // ❌ Stop upload if any errors
        if (count($errors) > 0) {
            return back()->with('error', implode('<br>', $errors));
        }

        // ✅ Create GRN if all checks pass
        $grn_count = DB::table('grn')->whereYear('grn_date', date('Y'))->count() + 1;
        $code = 'GRN' . date('y') . '-' . str_pad($grn_count, 5, '0', STR_PAD_LEFT);

        if ($request->price_check=="on"){
            $price_check="On";
        }
        else{
            $price_check="Off";
        }

        $grn_id = Grn::insertGetId([
            'vendor_id' => $request->vendor_id,
            'company_id' => $request->company_id,
            'branch_id' => $request->branch_id,
            'grn_date' => $request->grn_date,
            'grn_refrence_no' => $request->grn_refrence_no,
            'note' => $request->note,
            'document_no' => $request->document_no,
            'inquiry_no' => $request->inquiry_no,
            'project_no' => $request->project_no,
            'cost_check' => $price_check,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
            'grn_no' => $code
        ]);

        // ✅ Insert GRN lines
        foreach ($data as $row) {
            $product = $products[trim($row[0])];
            $po = $pos[trim($row[4])];
            $poline = Poline::where('po_id', $po->id)
                            ->where('product_id', $product->id)
                            ->first();

            $qty = $row[2];
            $cost = $row[3];
            $total_price = $cost * $qty;

            $grnLine = new Grnline();
            $grnLine->po_id = $po->id;
            $grnLine->poline_id = $poline->id;
            $grnLine->grn_id = $grn_id;
            $grnLine->product_id = $product->id;
            $grnLine->description = $row[1];
            $grnLine->quantity = $qty;
            $grnLine->cost = $cost;
            $grnLine->price = $total_price;
            $grnLine->save();

            // ✅ Update balance quantities
            $received_quantity= $poline->received_quantity+$qty;
            $balance_quantity= $poline->balance_quantity-$qty;
            Poline::where('id',$poline->id)->update(['received_quantity'=>$received_quantity,'balance_quantity'=>$balance_quantity]);
        }

        Pogrnlog::insert([
            'po_id' => 0,
            'grn_id' => $grn_id,
            'note' => "GRN Created",
            'created_by' => Auth::user()->id,
            'created_at' => now()
        ]);

        return redirect('view/grn/' . $grn_id)
            ->with('success', 'GRN created successfully!');
    }

    public function index(Request $request)
    {
        $title = "GRN";
        $fromdate = date('Y-m-01');
        $todate = date('Y-m-d');
        if (!empty($request->fromdate) && !empty($request->todate)){
            $fromdate = $request->fromdate;
            $todate = $request->todate;
        }
        $todate1 = date('Y-m-d', strtotime("+1 day", strtotime($todate)));
        $vendors = Vendor::where('delete_status','0')->orderBy('name','asc')->get();
        $indexes = Grn::with(["vendor", "grnlines", "company", "branch"])->whereBetween('grn_date',[$fromdate, $todate]);
        $companies = Company::where('delete_status','0')->orderBy('id','Desc')->get();
        $branches = Branch::where('delete_status','0')->orderBy('id','Desc')->get();

        $company_id='';
        if(!empty($request->company_id)){
            $company_id=$request->company_id;
            $indexes=$indexes->where('company_id',$company_id);
        }

        $branch_id='';
        if(!empty($request->branch_id)){
            $branch_id=$request->branch_id;
            $indexes=$indexes->where('branch_id',$branch_id);
        }

        $vendor_id='';
        if(!empty($request->vendor_id)){
            $vendor_id=$request->vendor_id;
            $indexes=$indexes->where('vendor_id',$vendor_id);
        }
        
        $indexes=$indexes->where('delete_status','0')->orderBy('id','Desc')->get();

        return view('grn.index',compact('title','indexes','fromdate','todate','vendors','vendor_id','companies','branches','company_id','branch_id'));  
    }

    public function view($id)
    {
        
        $grn = Grn::where('id',$id)->first();
        $title=$grn->grn_no.'-'.$grn->grn_date;
        return view('grn.view',compact('grn','title'));
    }

    public function edit($id)
    {
        $title="Edit GRN";
        $grn = Grn::where('id',$id)->first();

        $vendors=Vendor::where('delete_status','0')->orderBy('name','Asc')->get();

        return view('grn.edit',compact('grn','title','vendors'));
    }

    public function delete($id='')
    {
      Grn::where('id',$id)->update(['delete_status'=>'1','updated_by'=>Auth::user()->id]);
      $grnlines = Grnline::where('grn_id',$id)->get();

      foreach ($grnlines as $log) {
        $received_quantity= $log->poline->received_quantity-$log->quantity;
        $balance_quantity= $log->poline->balance_quantity+$log->quantity;
        Poline::where('id',$log->poline_id)->update(['received_quantity'=>$received_quantity,'balance_quantity'=>$balance_quantity]);
      }
      Grnline::where('grn_id',$id)->update(['delete_status'=>'1']);

      Pogrnlog::insert([
            'po_id' => 0,
            'grn_id' => $id,
            'note' => "GRN Deleted",
            'created_by' => Auth::user()->id,
            'created_at' => now()
        ]);
      return redirect()->back();
    }
    

    

}