<?php

namespace App\Http\Controllers;
use Excel;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Pricelog;
use App\Models\Productstock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Auth;

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

    public function index()
    {
        $title = "Parts";
        $indexes = Product::where('delete_status','0')->orderBy('id','Desc')->get();
        $brands=Brand::where('delete_status','0')->orderBy('name','Asc')->get();
        $categories=Category::where('delete_status','0')->orderBy('name','Asc')->get();
        $subcategories=Subcategory::where('delete_status','0')->orderBy('name','Asc')->get();

        return view('products.index',compact('title','indexes','brands','categories','subcategories'));  

    }

    public function store(Request $request)
    {   

        if(!empty($request->editid)){
            $data = Product::find($request->editid);
            if (empty($data)) { $data = new Product; }
            $data->name = $request->name;
            $data->brand_id = $request->brand_id;
            $data->note = $request->note;
            $data->category_id = $request->category_id;
            $data->subcategory_id = $request->subcategory_id;
            $data->price = $request->price;
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
            
            $product_id=Product::insertGetId(['name'=>$request->name,'category_id'=>$request->category_id,'subcategory_id'=>$request->subcategory_id,'brand_id'=>$request->brand_id,'note'=>$request->note,'price'=>$request->price,'created_by'=>Auth::user()->id]);

            $data = new Pricelog; 
            $data->product_id = $product_id;
            $data->price = $request->price;
            $data->created_by=Auth::user()->id;
            $data->save();

            $data = new Productstock; 
            $data->product_id = $product_id;
            $data->qty =0;
            $data->pdc =0;
            $data->t_qty =0;
            $data->created_by=Auth::user()->id;
            $data->save();

            $data = Product::find($product_id);
            if (empty($data)) { $data = new Product; }
            $data->reference_id = $product_id;
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

    public function stock()
    {
        $title = "Stock";
        $indexes = Productstock::where('delete_status','0')->orderBy('updated_at','Desc')->get();
        $brands=Brand::where('delete_status','0')->orderBy('name','Asc')->get();
        return view('products.stock',compact('title','indexes','brands')); 
    }

    public function stockstore(Request $request)
    {   

        header('Content-Type: text/html; charset=utf-8');
        //$path = $request->file('import_file')->getRealPath();
        $path = $request->file('import_file');
        //$data = Excel::load($path)->get();
        $data = Excel::toArray([], $path);
        $data = $data[0];
        $x=0;
        $parts='';
        if(count($data) > 0){
            foreach ($data as $key => $value) {
                
                    $product=Product::where('name',$value[0])->first();
                    if($product){
                        $x++;
                        $productstock=Productstock::where('product_id',$product->id)->first();

                        if($productstock){
                            $data = Productstock::find($productstock->id);
                            if (empty($data)) { $data = new Productstock; }
                            $data->product_id = $product->id;
                            $data->qty = $value[2];
                            $data->t_qty = $value[3];
                            $data->pdc = $value[4];
                            $data->updated_by=Auth::user()->id;
                            $data->save();
                        }
                        else{
                            $data = new Productstock; 
                            $data->product_id = $product->id;
                            $data->qty = $value[2];
                            $data->t_qty = $value[3];
                            $data->pdc = $value[4];
                            $data->created_by=Auth::user()->id;
                            $data->save();
                        }
                    }
                    else{
                        $parts=$parts.$value[0].',';
                    }
               
            }
        }

        $alert = '<strong> '.$x.'</strong> Records Updated Successfully,Not Uploaded List: '.$parts;
        return redirect('/stock')->with('alert', $alert);;
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
        if(count($data) > 0){
            foreach ($data as $key => $value) {
               

                    $product=Product::where('name',$value[0])->first();
                    if($product){
                         $x++;
                            $data = Product::find($product->id);
                            if (empty($data)) { $data = new Product; }
                            $data->cost = $value[2];
                            $data->price = $value[3];
                            $data->updated_by=Auth::user()->id;
                            $data->save();

                            $data = new Pricelog; 
                            $data->product_id = $product->id;
                            $data->cost = $value[2];
                            $data->price = $value[3];
                            $data->created_by=Auth::user()->id;
                            $data->save();
                    }
               
            }
        }
        $x=$x-1;
        $alert = '<strong> '.$x.'</strong> Records Updated Successfully';
        return redirect('/products')->with('alert', $alert);;
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
        $chunks=array_chunk($data, 10000);
        $parts='';
        
        if(count($chunks) > 0){
          foreach ($chunks as $key => $data) {
            if(count($data) > 0){
                foreach ($data as $key => $value) {
                   $brand=Brand::find($request->brand_id);

                        $product=$brand->name.$value[0];
                        $productcount=Product::where('brand_id',$request->brand_id)->where('name',$product)->count();
                        if($productcount==0){
                            $x++;
                            $product_id=Product::insertGetId(['brand_id'=>$request->brand_id,'name'=>$product,'price'=>$value[4],'min_price'=>$value[3],'cost'=>$value[2],'note'=>$value[1],'created_by'=>Auth::user()->id]);

                            $data = Product::find($product_id);
                            $data->reference_id = $product_id;
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
                        else{
                            $parts.=$value[0].',';
                        }
                }
            }
          }
        }

         $alert = '<strong> '.$x.'</strong> Records Updated Successfully, Not Uploaded List:'.$parts;
        return redirect('/products')->with('alert', $alert);
    }
}
