<?php

namespace App\Http\Controllers;
use Excel;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Pricelog;
use App\Models\Dealerpricelog;
use App\Models\Productstock;
use App\Models\Producthistory;
use App\Models\Productstocklog;
use App\Models\Group;
use App\Models\Color;
use App\Models\Size;
use App\Models\Packagetype;
use App\Models\Uom;
use App\Models\Uploadlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;

class ProductsController extends Controller
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

    public function index(Request $request)
    {

        $title = "Parts";
        $products = Product::where('delete_status','0');
        $brands=Brand::where('delete_status','0')->orderBy('name','Asc')->get();
        $categories=Category::where('delete_status','0')->orderBy('name','Asc')->get();
        $subcategories=Subcategory::where('delete_status','0')->orderBy('name','Asc')->get();
        $sizes=Size::where('delete_status','0')->orderBy('name','Asc')->get();
        $groups=Group::where('delete_status','0')->orderBy('name','Asc')->get();
        $colors=Color::where('delete_status','0')->orderBy('name','Asc')->get();
        $uoms=Uom::where('delete_status','0')->orderBy('name','Asc')->get();
        $packagelists=Packagetype::where('delete_status','0')->orderBy('name','Asc')->get();

        if(!empty($request->brand_search) && !empty($request->product_search)){

            $brand=Brand::find($request->brand_search);
            $product=$brand->name.$request->product_search;
            $products=Product::where('name',$product)->get();
        }
        else{
            $products=$products->orderBy('id','Desc')->limit(100)->get();
        }
        
        return view('products.index',compact('title','brands','categories','subcategories','groups','colors','uoms','packagelists','sizes','products'));  
    }

    public function productQueryWindow()
    {
        $title = "Query Window";
        return view('products.querywindow',compact('title')); 
    }

    public function store(Request $request)
    {   

        if(!empty($request->editid)){
            $data = Product::find($request->editid);
            if (empty($data)) { $data = new Product; }
            $brand=Brand::find($request->brand_id);
            $productname=$brand->name.$request->product_id;
            $product=Product::where('brand_id',$request->brand_id)->where('name',$productname)->first();
            $product_id=0;
            if($product){
                $product_id=$product->id;
            }

            $data->brand_id = $request->brand_id;
            $data->name = $request->name;
            $data->uom_id = $request->uom_id;
            $data->price = $request->price;
            $data->min_price = $request->min_price;
            $data->cost = $request->cost;
            $data->group_id = $request->group_id;
            $data->category_id = $request->category_id;
            $data->subcategory_id = $request->subcategory_id;
            $data->size_id = $request->size_id;
            $data->color_id = $request->color_id;
            $data->item_code_invoice = $request->item_code_invoice;
            $data->product_id = $product_id;
            $data->vendor_item_code = $request->vendor_item_code;
            $data->hrm_code = $request->hrm_code;
            $data->po_qty_limit = $request->po_qty_limit;
            $data->packagetype_id = $request->packagetype_id;
            $data->package_qty = $request->package_qty;
            $data->is_tax = $request->is_tax;
            $data->sale_type = $request->sale_type;
            $data->note = $request->note;
            $data->note_ar = $request->note_ar;
            
            $data->updated_by=Auth::user()->id;
            $data->save();

            $data = new Pricelog; 
            $data->product_id = $request->editid;
            $data->price = $request->price;
            $data->created_by=Auth::user()->id;
            $data->save();

            
        }
        else{

            $validatedData = $request->validate([
                'name' => 'required|unique:products',
            ]);

            $brand=Brand::find($request->brand_id);
            $product=$brand->name.$request->name;

            $product_id=Product::insertGetId(['brand_id'=>$request->brand_id,'name'=>$request->name,'uom_id'=>$request->uom_id,'price'=>$request->price,'min_price'=>$request->min_price,'cost'=>$request->cost,'group_id'=>$request->group_id,'category_id'=>$request->category_id,'subcategory_id'=>$request->subcategory_id,'size_id'=>$request->size_id,'color_id'=>$request->color_id,'item_code_invoice'=>$request->item_code_invoice,'product_id'=>$request->product_id,'vendor_item_code'=>$request->vendor_item_code,'hrm_code'=>$request->hrm_code,'po_qty_limit'=>$request->po_qty_limit,'packagetype_id'=>$request->packagetype_id,'package_qty'=>$request->package_qty,'is_tax'=>$request->is_tax,'sale_type'=>$request->sale_type,'note'=>$request->note,'note_ar'=>$request->note_ar,'created_by'=>Auth::user()->id]);

            $data = Product::find($product_id);
            if (empty($data)) { $data = new Product; }
            $data->reference_id = $product_id;
            $data->save();

            $data = new Producthistory; 
            $data->product_id = $product_id;
            $data->brand_id = $request->brand_id;
            $data->name = $request->name;
            $data->created_by=Auth::user()->id;
            $data->save();

            $data = new Pricelog; 
            $data->product_id = $product_id;
            $data->price = $request->price;
            $data->cost = $request->cost;
            $data->min_price = $request->min_price;
            $data->created_by=Auth::user()->id;
            $data->save();

            $data = new Productstock; 
            $data->product_id = $product_id;
            $data->brand_id = $request->brand_id;
            $data->qty =0;
            $data->pdc =0;
            $data->t_qty =0;
            $data->created_by=Auth::user()->id;
            $data->save();
        }

        return redirect('/products');
    }

    public function destroy(Product $products,$id)
    {
        $data = Product::find($id);
        $data->delete_status = 1;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect('/products');
    }

    public function stock(Request $request)
    {
        $title = "Stock";
        $indexes = Productstock::where('delete_status','0');
        if(!empty($request->brand_search) && !empty($request->product__stock_search)){
           
            $brand=Brand::find($request->brand_search);
            $product=$brand->name.$request->product__stock_search;
            $product=Product::where('name',$product)->first();
            if($product){

                $indexes=Productstock::where('product_id',$product->id)->get();
            }
            else{
                $indexes=Productstock::where('brand_id',$request->brand_search)->orderBy('updated_at','Desc')->limit(100)->get();
            }
            
        }
        else{
            $indexes=$indexes->orderBy('updated_at','Desc')->limit(100)->get();
        }

        $brands=Brand::where('delete_status','0')->orderBy('name','Asc')->get();
        return view('products.stock',compact('title','indexes','brands')); 
    }

    public function stockstore(Request $request)
    {   
        //print_r($request->brand_ids); exit();
        header('Content-Type: text/html; charset=utf-8');
        //$path = $request->file('import_file')->getRealPath();
        $path = $request->file('import_file');
        //$data = Excel::load($path)->get();
        Productstock::whereIn('brand_id',$request->brand_ids)->update(['qty'=>0,'t_qty'=>0,'pdc'=>0]);

        $data = Excel::toArray([], $path);
        $data = $data[0];
        $x=0;
         unset($data[0]);
        $chunks=array_chunk($data, 10000);
        $parts='';
        $filecount=0;
        $successcount=0;
        $failcount=0;
        if(count($chunks) > 0){
          foreach ($chunks as $key => $data) {
                if(count($data) > 0){
                    foreach ($data as $key => $value) {
                        $filecount++;
                        $product=Product::where('name',$value[0])->first();
                        if($product){
                            $x++;

                            Productstock::where('product_id',$product->id)->update(['qty'=>$value[2],'t_qty'=>$value[3],'pdc'=>$value[4],'updated_by'=>Auth::user()->id]);
                            $data = new Productstocklog; 
                            $data->product_id = $product->id;
                            $data->qty = $value[2];
                            $data->t_qty = $value[3];
                            $data->pdc = $value[4];
                            $data->created_by=Auth::user()->id;
                            $data->save();
                        }
                        else{
                            $failcount++;
                            $parts.=$value[0].',';
                        }
                    }
                }
                
            }
        }
        
        $alert = '<strong> '.$x.'</strong> Records Updated Successfully Not Uploaded List, '.$failcount.'';
        $brandlist=implode(',', $request->brand_ids);
        $data = new Uploadlog; 
        $data->name = 'Stock Upload';
        $data->brand_id = $brandlist;
        $data->filecount = $filecount;
        $data->successcount = $x;
        $data->failcount = $failcount;
        $data->message = $parts;
        $data->created_by=Auth::user()->id;
        $data->save();
        return redirect('/stock')->with('alert', $alert);
    }

    public function price_stock_upload(Request $request)
    {
        header('Content-Type: text/html; charset=utf-8');
        //$path = $request->file('import_file')->getRealPath();
        $path = $request->file('import_file');
        //$data = Excel::load($path)->get();
        $data = Excel::toArray([], $path);
        $data = $data[0];
        $x=0;
        $filecount=0;
        $failcount=0;
        $validate=0;
        $alert='';
        unset($data[0]);
        $chunks=array_chunk($data, 10000);
        $parts='';
        
        Productstock::whereIn('brand_id',$request->brand_ids)->update(['qty'=>0,'t_qty'=>0,'pdc'=>0]);
        if(count($chunks) > 0){
            foreach ($chunks as $key => $data) {
                if(count($data) > 0){
                    foreach ($data as $key => $value) {
                        $filecount++;
                        $product=Product::where('name',$value[0])->first();
                        if($product){
                             $x++;
                                $cost=round($value[1],3);
                                $price=round($value[3],3);
                                $min_price=round($value[2],3);
                                
                                $data = Product::find($product->id);
                                $data->cost = $cost;
                                $data->price = $price;
                                $data->min_price = $min_price;
                                $data->updated_by=Auth::user()->id;
                                $data->save();

                                Productstock::where('product_id',$product->id)->update(['qty'=>$value[4],'t_qty'=>$value[5],'pdc'=>$value[6],'updated_by'=>Auth::user()->id]);

                                $data = new Pricelog; 
                                $data->product_id = $product->id;
                                $data->cost = $cost;
                                $data->price = $price;
                                $data->min_price = $min_price;
                                $data->created_by=Auth::user()->id;
                                $data->save();

                                $data = new Productstocklog; 
                                $data->product_id = $product->id;
                                $data->qty = $value[4];
                                $data->t_qty = $value[5];
                                $data->pdc = $value[6];
                                $data->created_by=Auth::user()->id;
                                $data->save(); 
                        }
                        else{
                            $failcount++;
                            $parts.=$value[0].',';
                        }
                    }
                }
            }
        }

        $alert = '<strong> '.$x.'</strong> Records Updated Successfully Not Uploaded List, '.$failcount.'';
        $brandlist=implode(',', $request->brand_ids);
        $data = new Uploadlog; 
        $data->name = 'Price & Stock Upload';
        $data->brand_id = $brandlist;
        $data->filecount = $filecount;
        $data->successcount = $x;
        $data->failcount = $failcount;
        $data->message = $parts;
        $data->created_by=Auth::user()->id;
        $data->save();
        return redirect('/products')->with('alert', $alert);
    }
    
    public function dealerpriceupload(Request $request)
    {
        header('Content-Type: text/html; charset=utf-8');
        //$path = $request->file('import_file')->getRealPath();
        $path = $request->file('import_file');
        //$data = Excel::load($path)->get();
        $data = Excel::toArray([], $path);
        $data = $data[0];
        $x=0;
        $filecount=0;
        $failcount=0;
        $validate=0;
        $alert='';
        unset($data[0]);
        $chunks=array_chunk($data, 10000);
        $parts='';
        if(count($chunks) > 0){
            foreach ($chunks as $key => $data) {
                if(count($data) > 0){
                    foreach ($data as $key => $value) {
                        $filecount++;
                        $product=Product::where('name',$value[0])->first();
                        if($product){
                             $x++;

                                $price=round($value[1],3);
                                $cost=round($value[2],3);

                                $data = Product::find($product->id);
                                $data->dealer_price = $price;
                                $data->dealer_cost = $cost;
                                $data->updated_by=Auth::user()->id;
                                $data->save();

                                $data = new Dealerpricelog; 
                                $data->product_id = $product->id;
                                $data->price = $value[1];
                                $data->cost = $value[2];
                                $data->created_by=Auth::user()->id;
                                $data->save();
                        }
                        else{
                            $failcount++;
                            $parts.=$value[0].',';
                        }
                       
                    }
                    
                }
               
            }

        }

         $alert = '<span class="p-1 bg-success bg-soft"><strong> '.$x.'</strong> Records Updated Successfully, Not Uploaded List:'.$parts.'</span>';
        $data = new Uploadlog; 
        $data->name = 'Product Dealer Price Upload';
        $data->filecount = $filecount;
        $data->successcount = $x;
        $data->failcount = $failcount;
        $data->message = $parts;
        $data->created_by=Auth::user()->id;
        $data->save();

        return redirect('/products')->with('alert', $alert);
    }

    public function upload(Request $request)
    {   

        header('Content-Type: text/html; charset=utf-8');
        //$path = $request->file('import_file')->getRealPath();
        $path = $request->file('import_file');
        //$data = Excel::load($path)->get();
        $data = Excel::toArray([], $path);
        $data = $data[0];
        $x=0;
        $filecount=0;
        $failcount=0;
        $validate=0;
        $alert='';
        unset($data[0]);
        $chunks=array_chunk($data, 10000);
        $parts='';
        if(count($chunks) > 0){
            foreach ($chunks as $key => $data) {
                if(count($data) > 0){
                    foreach ($data as $key => $value) {
                        $filecount++;
                        $product=Product::where('brand_id',$request->brand)->where('name',$value[0])->first();
                        if($product){
                             $x++;

                                $cost=round($value[2],3);
                                $price=round($value[3],3);
                                $min_price=round($value[4],3);

                                $data = Product::find($product->id);
                                $data->cost = $cost;
                                $data->price = $price;
                                $data->min_price = $min_price;
                                $data->updated_by=Auth::user()->id;
                                $data->save();

                                $data = new Pricelog; 
                                $data->product_id = $product->id;
                                $data->cost = $value[2];
                                $data->price = $value[3];
                                $data->min_price = $value[4];
                                $data->created_by=Auth::user()->id;
                                $data->save();
                        }
                        else{
                            $failcount++;
                            $parts.=$value[0].',';
                        }
                       
                    }
                    
                }
               
            }

        }

         $alert = '<span class="p-1 bg-success bg-soft"><strong> '.$x.'</strong> Records Updated Successfully, Not Uploaded List:'.$failcount.'</span>';
        $data = new Uploadlog; 
        $data->name = 'Product Price Upload';
        $data->brand_id = $request->brand;
        $data->filecount = $filecount;
        $data->successcount = $x;
        $data->failcount = $failcount;
        $data->message = $parts;
        $data->created_by=Auth::user()->id;
        $data->save();

        return redirect('/products')->with('alert', $alert);
    }

    public function show($id='')
    {
       $title="View Product";
       $product = Product::find($id);
       $pricelogs =$product->pricelog;
       $dealerpricelogs=$product->dealerpricelogs;
       $producthistory=$product->producthistory;
       return view('products.view',compact('title','product','pricelogs','producthistory','dealerpricelogs')); 
    }
    public function productsupload(Request $request)
    {
       header('Content-Type: text/html; charset=utf-8');
        //$path = $request->file('import_file')->getRealPath();
        $path = $request->file('import_file');
        //$data = Excel::load($path)->get();
        $data = Excel::toArray([], $path);
        $data = $data[0];
        unset($data[0]);
        $x=0;
        $y=0;
        $filecount=0;
        $failcount=0;
        $chunks=array_chunk($data, 10000);
        $parts='';
        
        if(count($chunks) > 0){
          foreach ($chunks as $key => $data) {
            if(count($data) > 0){
                foreach ($data as $key => $value) {
                   $brand=Brand::find($request->brand_id);
                        $filecount++;
                        $product=$brand->name.$value[0];
                        $productcount=Product::where('brand_id',$request->brand_id)->where('name',$product)->count();
                        if($productcount==0){
                            $x++;

                            $cost=round($value[2],3);
                            $price=round($value[4],3);
                            $min_price=round($value[3],3);

                            $product_id=Product::insertGetId(['brand_id'=>$request->brand_id,'name'=>$product,'price'=>$price,'min_price'=>$min_price,'cost'=>$cost,'note'=>$value[1],'created_by'=>Auth::user()->id]);

                            $data = Product::find($product_id);
                            $data->reference_id = $product_id;
                            $data->save();

                            $data = new Pricelog; 
                            $data->product_id = $product_id;
                            $data->cost = $cost;
                            $data->price = $price;
                            $data->min_price = $min_price;
                            $data->created_by=Auth::user()->id;
                            $data->save();

                            $data = new Productstock; 
                            $data->product_id = $product_id;
                            $data->brand_id = $request->brand_id;
                            $data->qty =0;
                            $data->pdc =0;
                            $data->t_qty =0;
                            $data->created_by=Auth::user()->id;
                            $data->save();
                        }
                        else{
                            $failcount++;
                            $parts.=$value[0].',';
                        }
                }
            }
          }
        }

        $data = new Uploadlog; 
        $data->name = 'Part Upload';
        $data->brand_id = $request->brand_id;
        $data->filecount = $filecount;
        $data->successcount = $x;
        $data->failcount = $failcount;
        $data->message = $parts;
        $data->created_by=Auth::user()->id;
        $data->save();

         $alert = '<strong> '.$x.'</strong> Records Updated Successfully, Not Uploaded List:'.$failcount;
        return redirect('/products')->with('alert', $alert);
    }
    public function productsuploadmain(Request $request)
    {   

        header('Content-Type: text/html; charset=utf-8');
        //$path = $request->file('import_file')->getRealPath();
        $path = $request->file('import_file');
        //$data = Excel::load($path)->get();
        $data = Excel::toArray([], $path);
        $data = $data[0];
        $x=0;
        $y=0;
        if(count($data) > 0){
            foreach ($data as $key => $value) {
               $y++;
               if($y >1){
                   if($value[0]!=''){
                        $brand=Brand::find($request->brand_id);

                        $product=$brand->name.$value[0];
                        $productcount=Product::where('brand_id',$request->brand_id)->where('name',$product)->count();
                        if($productcount==0){
                            $x++;
                            $product_id=Product::insertGetId(['brand_id'=>$request->brand_id,'name'=>$product,'price'=>$value[4],'min_price'=>$value[3],'cost'=>$value[2],'note'=>$value[1],'created_by'=>Auth::user()->id]);

                            $data = Product::find($product_id);
                            if (empty($data)) { $data = new Product; }
                            $data->reference_id = $product_id;
                            $data->save();

                            $data = new Producthistory; 
                            $data->product_id = $product_id;
                            $data->brand_id = $request->brand_id;
                            $data->name = $product;
                            $data->created_by=Auth::user()->id;
                            $data->save();

                            $data = new Pricelog; 
                            $data->product_id = $product_id;
                            $data->cost = $value[2];
                            $data->price = $value[4];
                            $data->min_price = $value[3];
                            $data->created_by=Auth::user()->id;
                            $data->save();

                            $data = new Productstock; 
                            $data->product_id = $product_id;
                            $data->brand_id = $request->brand_id;
                            $data->qty =0;
                            $data->pdc =0;
                            $data->t_qty =0;
                            $data->created_by=Auth::user()->id;
                            $data->save();
                        }
                    }
               } 
            }
        }

        $alert = '<strong> '.$x.'</strong> Records Updated Successfully';
        return redirect('/products')->with('alert', $alert);
    }

    
    public function productshistoryupload(Request $request)
    {   

        header('Content-Type: text/html; charset=utf-8');
        //$path = $request->file('import_file')->getRealPath();
        $path = $request->file('import_file');
        //$data = Excel::load($path)->get();
        $data = Excel::toArray([], $path);
        $data = $data[0];
        unset($data[0]);
        $x=0;
        $y=0;
        $filecount=0;
        $failcount=0;
        $parts='';
        $chunks=array_chunk($data, 10000);
        
        if(count($chunks) > 0){
          foreach ($chunks as $key => $data) {
                if(count($data) > 0){
                    foreach ($data as $key => $value) {
                        $filecount++;
                       if($value[1]!=''){
                            
                            $x++;
                            if($value[1]!=$value[0]){
                                $brand=Brand::find($request->brand_id);
                                $product=$brand->name.$value[1];
                                $old_product=$brand->name.$value[0];
                                $product=Product::where('brand_id',$request->brand_id)->where('name',$product)->first();
                                $old_product=Product::where('brand_id',$request->brand_id)->where('name',$old_product)->first();
                                if($old_product){

                                    if($product){
                                        Product::where('reference_id',$old_product->id)->update(['reference_id'=>$product->id]);

                                        $data = new Producthistory; 
                                        $data->product_id = $old_product->id;
                                        $data->brand_id = $request->brand_id;
                                        $data->name = $product->name;
                                        $data->created_by=Auth::user()->id;
                                        $data->save();
                                    }
                                    else{
                                        $failcount++;
                                        $parts.=$value[0].',';
                                    }
                                    
                                }
                                else{
                                    $failcount++;
                                    $parts.=$value[0].',';
                                }
                            }
                            
                        }

                    }
                }
            }
        }

        $data = new Uploadlog; 
        $data->name = 'Parts Supersession Upload';
        $data->brand_id = $request->brand_id;
        $data->filecount = $filecount;
        $data->successcount = $x;
        $data->failcount = $failcount;
        $data->message = $parts;
        $data->created_by=Auth::user()->id;
        $data->save();

        $alert = '<strong> '.$x.'</strong> Records Updated Successfully, Not Uploaded List:'.$failcount;
        return redirect('/products')->with('alert', $alert);
    }

    public function productslatestupload(Request $request)
    {  
        $brand=Brand::find($request->brand_id);

        $productname=$brand->name.$request->reference_id;
        $product=Product::where('brand_id',$request->brand_id)->where('name',$productname)->first();
        $reference_id='';
        if($product){
            $reference_id=$product->id;
        }
        
        if(!empty($reference_id)){
            $product=Product::find($request->p_id);
            $product_new=Product::find($request->p_id);
            
            Product::where('reference_id',$product->reference_id)->update(['reference_id'=>$reference_id]);

            $data = new Producthistory; 
            $data->product_id = $request->p_id;
            $data->name = $product_new->name;
            $data->created_by=Auth::user()->id;
            $data->save();

            $alert = 'Records Updated Successfully';
        }
        else{
            $alert = 'Records Faild to Updated,New Product Name is not Available';
        }
        
        return redirect('/products')->with('alert', $alert);
    }

    public function getPart(Request $request)
    {
        $res = Product::select("name","id")
                ->where("name","LIKE","%{$request->search}%")
                ->get();

        foreach($res as $employee){
         $response[] = array("value"=>$employee->id,"label"=>$employee->name);
      }

      return response()->json($response);

    }

    public function productsinfoupload(Request $request)
    {
        header('Content-Type: text/html; charset=utf-8');
        //$path = $request->file('import_file')->getRealPath();
        $path = $request->file('import_file');
        //$data = Excel::load($path)->get();
        $data = Excel::toArray([], $path);
        $data = $data[0];
        $x=0;
        $filecount=0;
        $failcount=0;
        $validate=0;
        $alert='';
        unset($data[0]);
        $chunks=array_chunk($data, 10000);
        $parts='';
        if(count($chunks) > 0){
            foreach ($chunks as $key => $data) {
                if(count($data) > 0){
                    foreach ($data as $key => $value) {
                        $filecount++;
                        $product=Product::where('name',$value[0])->first();
                        if($product){
                             $x++;

                                $data = Product::find($product->id);
                                $data->category_id = $value[1];
                                $data->subcategory_id = $value[2];
                                $data->group_id = $value[3];
                                $data->color_id = $value[4];
                                $data->size_id = $value[5];
                                $data->uom_id = $value[6];
                                $data->packagetype_id = $value[7];
                                $data->package_qty = $value[8];
                                $data->is_tax = $value[9];
                                $data->sale_type = $value[10];
                                $data->note = $value[11];
                                $data->note_ar = $value[12];
                                $data->updated_by=Auth::user()->id;
                                $data->save();
                        }
                        else{
                            $failcount++;
                            $parts.=$value[0].',';
                        }
                       
                    }
                    
                }
               
            }

        }

         $alert = '<span class="p-1 bg-success bg-soft"><strong> '.$x.'</strong> Records Updated Successfully, Not Uploaded List:'.$failcount.'</span>';
        $data = new Uploadlog; 
        $data->name = 'Product Info Upload';
        $data->filecount = $filecount;
        $data->successcount = $x;
        $data->failcount = $failcount;
        $data->message = $parts;
        $data->created_by=Auth::user()->id;
        $data->save();

        return redirect('/products')->with('alert', $alert);
    }

    public function productStockAdd(Request $request)
    {
        if($request->product_id!=''){
            Productstock::where('product_id',$request->product_id)->update(['qty'=>$request->qty,'t_qty'=>$request->t_qty,'pdc'=>$request->pdc,'updated_by'=>Auth::user()->id]);

            $data = new Productstocklog; 
            $data->product_id = $request->product_id;
            $data->qty = $request->qty;
            $data->t_qty = $request->t_qty;
            $data->pdc = $request->pdc;
            $data->created_by=Auth::user()->id;
            $data->save();
        }
        return redirect()->back();
    }

}
