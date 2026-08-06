@extends('layouts.master')
@section('title',$title)
@section('StyleContent')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<style type="text/css">
 /*   .select2-container.select2-container--default.select2-container--open  {
  z-index: 5000;
}*/
</style>
@endsection
@section('PageContent')
<style type="text/css">
tr.ex2:hover, a.ex2:active {cursor: pointer; background-color:#adf7a9  ! important;font-size: 115%;}
tr.selected {background-color:#adf7a9  ! important;}
</style>
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-16">{{$title}}</h4>
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Details</a></li>
                    <li class="breadcrumb-item active">{{$title}}</li>
            </ol>
        </div>

    </div>
</div>
</div>

<div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Upload Product Price</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  action="{{url('/products/upload')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}  
            <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <div class="col-lg-12 mb-3">
                                 <label>Brand <span class="text-danger">*</span></label>
                                 <select class="form-control" name="brand" id="brand" required="">
                                     <option value="">Select Brand</option>
                                     @foreach($brands as $brand)
                                        <option value="{{$brand->id}}">{{$brand->name}}</option>
                                     @endforeach
                                 </select>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label>Select File: <span class="text-danger">*Product Name With brand Prefix</span></label>
                                <input type="file" name="import_file" class="form-control" required="">
                            </div>
                            
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="myModal1" class="modal fade" tabindex="-1" aria-labelledby="myModal1Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Upload Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  action="{{url('/productsupload')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}  
            <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <div class="col-lg-12 mb-3">
                                 <label>Brand <span class="text-danger">*</span></label>
                                 <select class="form-control" name="brand_id" id="sbrand_id" required="">
                                     <option value="">Select Brand</option>
                                     @foreach($brands as $brand)
                                        <option value="{{$brand->id}}">{{$brand->name}}</option>
                                     @endforeach
                                 </select>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label>Select File: <span class="text-danger">*</span><span class="text-danger">Product Name Without brand Prefix</span></label>
                                <input type="file" name="import_file" class="form-control" required="">
                            </div>
                            
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="myModal2" class="modal fade" tabindex="-1" aria-labelledby="myModal2Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Upload Product History</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  action="{{url('/productshistoryupload')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}  
            <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <div class="col-lg-12 mb-3">
                                 <label>Brand <span class="text-danger">*</span></label>
                                 <select class="form-control" name="brand_id" id="hbrand_id" required="">
                                     <option value="">Select Brand</option>
                                     @foreach($brands as $brand)
                                        <option value="{{$brand->id}}">{{$brand->name}}</option>
                                     @endforeach
                                 </select>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label>Select File: <span class="text-danger">*</span><span class="text-danger">Product Name Without brand Prefix</span></label>
                                <input type="file" name="import_file" class="form-control" required="">
                            </div>
                            
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="myModal3" class="modal fade" tabindex="-1" aria-labelledby="myModal2Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Update Product Latest</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  action="{{url('/productslatestupload')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}  
            <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                             <label>Brand <span class="text-danger">*</span></label>
                             <select class="form-control" name="brand_id" id="pbrand_id" readonly>
                                 <option value="">Select Brand</option>
                                 @foreach($brands as $brand)
                                    <option value="{{$brand->id}}">{{$brand->name}}</option>
                                 @endforeach
                             </select>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>Current Product Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="pname" id="pname">
                            <input type="hidden" name="p_id" id="p_id">
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label>New Product Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="reference_id" id="reference_id" required="">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="myModaldealer" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Upload Product Dealer Price</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  action="{{url('/productsupload/dealerprice')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}  
            <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <div class="col-lg-12 mb-3">
                                <label>Select File: <span class="text-danger">* Product Name With brand Prefix</span></label>
                                <input type="file" name="import_file" class="form-control" required="">
                            </div>
                            
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="myModalpinfo" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Upload Product Info</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  action="{{url('/productsinfoupload')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}  
            <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <div class="col-lg-12 mb-3">
                                <label>Select File: <span class="text-danger">* Product Name With brand Prefix</span></label>
                                <input type="file" name="import_file" class="form-control" required="">
                            </div>
                            
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="row" style="display: none;" id="uploadstockprice">
<div class="col-md-12">
    <div class="card">
        <div class="card-body"> 
        <h4 class="text-secondary"><span id="createbutton">Upload</span> Product Stock & Price</h4>
        <div class="row">
            <div class="col-sm-12 pt-3">
                <form  action="{{url('/products/price_stock_upload')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}  
                <div class="row">
                     <div class="col-lg-3 mb-3">
                         <label>Brand <span class="text-danger">*</span></label>
                         <br>
                         <select class="form-control select2" multiple="" name="brand_ids[]"  style="width: 320px;" id="brand_ids" required="">
                             <option value="">Select Brand</option>
                             @foreach($brands as $brand)
                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                             @endforeach
                         </select>
                    </div>
                    <div class="col-lg-3 mb-3">
                        <div>
                            <label>Select File<span class="text-danger">*</span><span class="text-danger">Product Name With brand Prefix</span></label>
                            <input class="form-control" type="file" name="import_file" id="import_file">
                        </div>
                    </div>  
                    <div class="col-lg-3 mt-3">
                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Upload</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</div> 
</div>
</div>

<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
        <h4 class="text-secondary"><span id="createbutton">Create</span> {{$title}} 

            <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#myModal1">Upload Product<i class="bx bx-upload ms-1"></i></button>

            <button type="button" class="btn btn-info btn-sm btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#myModal">Upload Product Price<i class="bx bx-upload ms-1"></i></button>

            <button type="button" class="btn btn-warning btn-sm btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#myModal2">Upload SuperSession Chain<i class="bx bx-upload ms-1"></i></button>

            <button type="button" class="btn btn-secondary btn-sm btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#myModaldealer">Upload Dealer Price<i class="bx bx-upload ms-1"></i></button>

            <button type="button" class="btn btn-danger btn-sm btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#myModalpinfo">Upload Part Info<i class="bx bx-upload ms-1"></i></button>

            <button type="button" class="btn btn-success btn-sm btn-rounded waves-effect waves-light" onclick="showpricestock()">Upload Product Price & stock<i class="bx bx-upload ms-1"></i></button>
        </h4>

        {!!Session::get('alert')!!}
        <div class="row">
            <div class="col-sm-12 pt-3">
                <form  action="{{url('/products/store')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}  
                <div class="row">
                    <div class="col-lg-2 mb-3">
                         <label>Brand <span class="text-danger">*</span></label>
                         <select class="form-control" name="brand_id" id="brand_id" required="">
                             <option value="">Select Brand</option>
                             @foreach($brands as $brand)
                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                             @endforeach
                         </select>
                    </div>

                    <div class="col-lg-2 mb-3">
                        <div>
                            <label>Product Code <span class="text-danger">*</span> <span class="text-danger">Name With brand Prefix</span></label>
                            <input class="form-control" type="text" name="name" id="name" required="">
                            <input type="hidden" name="editid" id="editid">
                            @error('name')
                            <div class="text-danger">
                                This Name is already there with us
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-2 mb-3">
                        <div>
                            <label>Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" type="text" name="note" id="note" required=""></textarea>
                        </div>
                    </div>

                    <div class="col-lg-2 mb-3">
                         <label>UOM </label>
                         <select class="form-control" name="uom_id" id="uom_id">
                             <option value="">Select UOM</option>
                             @foreach($uoms as $uom)
                                <option value="{{$uom->id}}">{{$uom->name}} - {{$uom->quantity}}</option>
                             @endforeach
                         </select>
                    </div>  

                    <div class="col-lg-2 mb-3">
                        <div>
                            <label>Cost Price</label>
                            <input type="text" class="form-control" name="cost" id="cost"  min="0.1">
                        </div>
                    </div> 

                    <div class="col-lg-2 mb-3">
                        <div>
                            <label>Landing Cost</label>
                            <input type="text" class="form-control" name="min_price" id="min_price"  min="0.1">
                        </div>
                    </div> 

                    <div class="col-lg-2 mb-3">
                        <div>
                            <label>RSP </label>
                            <input type="text" class="form-control" name="price" id="price"  min="0.1">
                        </div>
                    </div> 

                    <div class="col-lg-2 mb-3">
                         <label>Group</label>
                         <select class="form-control" name="group_id" id="group_id">
                             <option value="">Select Group</option>
                             @foreach($groups as $group)
                                <option value="{{$group->id}}">{{$group->name}}</option>
                             @endforeach
                         </select>
                    </div>

                    <div class="col-lg-2 mb-3">
                         <label>Categroy </label>
                         <select class="form-control" name="category_id" id="category_id" onchange="loadSubCategory()">
                             <option value="">Select Category</option>
                             @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                             @endforeach
                         </select>
                    </div>

                    <div class="col-lg-2 mb-3">
                         <label>Subcategory </label>
                         <select class="form-control" name="subcategory_id" id="subcategory_id">
                             <option value="">Select Subcategory</option>
                             @foreach($subcategories as $subcategory)
                                <option class="all c_{{$subcategory->category_id}}" value="{{$subcategory->id}}">{{$subcategory->name}}</option>
                             @endforeach
                         </select>
                    </div>


                    <div class="col-lg-2 mb-3">
                         <label>Size </label>
                         <select class="form-control" name="size_id" id="size_id">
                             <option value="">Select Size</option>
                             @foreach($sizes as $size)
                                <option value="{{$size->id}}">{{$size->name}}</option>
                             @endforeach
                         </select>
                    </div>

                    <div class="col-lg-2 mb-3">
                         <label>Movement </label>
                         <select class="form-control" name="color_id" id="color_id">
                             <option value="">Select Movement</option>
                             @foreach($colors as $color)
                                <option value="{{$color->id}}">{{$color->name}}</option>
                             @endforeach
                         </select>
                    </div>

                    <div class="col-lg-2 mb-3">
                        <div>
                            <label>Item Code on Invoice</label>
                            <input type="text" class="form-control" name="item_code_invoice" id="item_code_invoice">
                        </div>
                    </div> 

                    <div class="col-lg-2 mb-3">
                         <label>Alternate Code</label>
                         <input type="text" name="product_id" id="product_id" class="form-control">
                    </div>

                    <div class="col-lg-2 mb-3">
                        <div>
                            <label>Vendor Item Code</label>
                            <input type="text" class="form-control" name="vendor_item_code" id="vendor_item_code">
                        </div>
                    </div> 

                    <div class="col-lg-2 mb-3">
                        <div>
                            <label>HRM Code</label>
                            <input type="text" class="form-control" name="hrm_code" id="hrm_code">
                        </div>
                    </div> 

                    <div class="col-lg-2 mb-3">
                        <div>
                            <label>P/O Unit</label>
                            <input type="text" class="form-control" name="po_qty_limit" id="po_qty_limit">
                        </div>
                    </div> 

                    <div class="col-lg-2 mb-3">
                         <label>Package Type </label>
                         <select class="form-control" name="packagetype_id" id="packagetype_id">
                             <option value="">Select Package Type</option>
                             @foreach($packagelists as $packagelist)
                                <option value="{{$packagelist->id}}">{{$packagelist->name}}</option>
                             @endforeach
                         </select>
                    </div>

                    <div class="col-lg-2 mb-3">
                        <div>
                            <label>Package Quantity</label>
                            <input type="text" class="form-control" name="package_qty" id="package_qty">
                        </div>
                    </div>

                    <div class="col-lg-2 mb-3">
                        <div>
                            <label>Tax Apply</label>
                            <select class="form-control" name="is_tax" id="is_tax">
                                <option value="">Select</option>
                                <option value="Y">Y</option>
                                <option value="N">N</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-2 mb-3">
                        <div>
                            <label>Sale Type</label>
                            <select class="form-control" name="sale_type" id="sale_type">
                                <option value="">Select</option>
                                <option value="C">C</option>
                                <option value="R">R</option>
                                <option value="B">B</option>
                            </select>
                        </div>
                    </div>

                     <div class="col-lg-2 mb-3">
                        <div>
                            <label>Arabic Description</label>
                            <textarea class="form-control" type="text" name="note_ar" id="note_ar"></textarea>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Save</button>
                        <a href="{{url('/products')}}" class="btn btn-secondary waves-effect waves-light resetbtn mt-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</div> 
</div>

<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h4 class="text-secondary">{{$title}} List</h4>
            <form  action="{{url('/products/search')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}  
                <div class="row">
                    <div class="col-lg-2 mb-3">
                         <label>Brand <span class="text-danger">*</span></label>
                         <select class="form-control" name="brand_search" id="brand_search" required="">
                             <option value="">Select Brand</option>
                             @foreach($brands as $brand)
                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                             @endforeach
                         </select>
                    </div>
                    <div class="col-lg-2 mb-3">
                        <label>Product <span class="text-danger">*</span><span class="text-danger">Name Without brand Prefix</span></label>
                        <input type="text" class="form-control" name="product_search" id="product_search" required="">
                    </div>
                    <div class="col-lg-2 mb-3">
                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Search</button>
                    </div>

                </div>
            </form>
            <div class="table-responsive">
            <table id="datatable-buttons" class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Product Code</th> 
                        <th>LATEST PART NO</th>
                        <th>Cost</th>   
                        <th>LC</th>    
                        <th>RSP</th>   
                        <th>Dealer Price</th>
                        <th>Description</th>
                        <th>PO Count</th>
                        <th>Action</th>
                    </tr>
                </thead>           
                <tbody>
                    <?php $x=1; ?>
                    @foreach ($products as $index)

                    <tr>
                        <td>{{$index->id}}</td>
                        <td>{{$index->brand->name}}</td>
                        <td>@if(!empty($index->category)){{$index->category->name}}@endif</td>
                        <td>@if(!empty($index->subcategory)){{$index->subcategory->name}}@endif</td>
                        <td>{{$index->name}}</td>
                        <td>{{App\Models\Product::FindName($index->reference_id)}}</td>
                        <td>{{$index->cost}}</td>
                        <td>{{$index->min_price}}</td>
                        <td>{{$index->price}}</td>
                        <td>{{$index->dealer_price}}</td>
                        <td>{{$index->note}}</td>
                        <td>{{$index->poline->count()}}</td>
                        <td>
                            <a href="#" data-id="{{$index->id}}" data-brand_id="{{$index->brand_id}}" data-name="{{$index->name}}" data-uom_id="{{$index->uom_id}}" data-price="{{$index->price}}" data-min_price="{{$index->min_price}}" data-cost="{{$index->cost}}" data-group_id="{{$index->group_id}}" data-category_id="{{$index->category_id}}" data-subcategory_id="{{$index->subcategory_id}}" data-size_id="{{$index->size_id}}" data-color_id="{{$index->color_id}}" data-item_code_invoice="{{$index->item_code_invoice}}" data-product_id="{{$index->product_id}}" data-vendor_item_code="{{$index->vendor_item_code}}" data-hrm_code="{{$index->hrm_code}}" data-po_qty_limit="{{$index->po_qty_limit}}" data-packagetype_id="{{$index->packagetype_id}}" data-package_qty="{{$index->package_qty}}" data-is_tax="{{$index->is_tax}}" data-sale_type="{{$index->sale_type}}" data-note="{{$index->note}}" data-note_ar="{{$index->note_ar}}" class="btn btn-outline-secondary waves-effect waves-light btn-sm font-size-18 editbtn"><i class="mdi mdi-pencil"></i></a>

                            <a href="{{url('/products')}}/{{$index->id}}/show" class="btn btn-outline-info waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-eye"></i></a>

                            <button data-id="{{$index->id}}" data-brand_id="{{$index->brand_id}}" data-name="{{$index->name}}" type="button" class="btn btn-outline-warning waves-effect waves-light btn-sm font-size-18 uptbtn" data-bs-toggle="modal" data-bs-target="#myModal3"><i class="bx bx-upload ms-1"></i></button>
                            @if($index->poline->count()==0)
                                <a onclick="return confirm('Are you sure?')" href="{{url('/products')}}/{{$index->id}}/delete" class="btn btn-outline-danger waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-trash-can-outline"></i></a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('ScriptContent')
<script src="{{asset('/assets')}}/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="{{asset('/assets')}}/libs/jszip/jszip.min.js"></script>
<script src="{{asset('/assets')}}/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="{{asset('/assets')}}/libs/pdfmake/build/vfs_fonts.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>
<script src="{{asset('/assets')}}/js/pages/form-advanced.init.js"></script>
<script>
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

$('.editbtn').click(function() {
    var id = $(this).data("id"); $("#editid").val(id);
    var brand_id = $(this).data("brand_id"); $("#brand_id").val(brand_id);
    var name = $(this).data("name"); $("#name").val(name);
    var uom_id = $(this).data("uom_id"); $("#uom_id").val(uom_id);
    var price = $(this).data("price"); $("#price").val(price);
    var min_price = $(this).data("min_price"); $("#min_price").val(min_price);
    var cost = $(this).data("cost"); $("#cost").val(cost);
    var group_id = $(this).data("group_id"); $("#group_id").val(group_id);
    var category_id = $(this).data("category_id"); $("#category_id").val(category_id);
    var subcategory_id = $(this).data("subcategory_id"); $("#subcategory_id").val(subcategory_id);
    var size_id = $(this).data("size_id"); $("#size_id").val(size_id);
    var color_id = $(this).data("color_id"); $("#color_id").val(color_id);
    var item_code_invoice = $(this).data("item_code_invoice"); $("#item_code_invoice").val(item_code_invoice);
    var product_id = $(this).data("product_id"); $("#product_id").val(product_id);
    var vendor_item_code = $(this).data("vendor_item_code"); $("#vendor_item_code").val(vendor_item_code);
    var hrm_code = $(this).data("hrm_code"); $("#hrm_code").val(hrm_code);
    var po_qty_limit = $(this).data("po_qty_limit"); $("#po_qty_limit").val(po_qty_limit);
    var packagetype_id = $(this).data("packagetype_id"); $("#packagetype_id").val(packagetype_id);
    var package_qty = $(this).data("package_qty"); $("#package_qty").val(package_qty);
    var is_tax = $(this).data("is_tax"); $("#is_tax").val(is_tax);
    var sale_type = $(this).data("sale_type"); $("#sale_type").val(sale_type);
    var note = $(this).data("note"); $("#note").val(note);
    var note_ar = $(this).data("note_ar"); $("#note_ar").val(note_ar);

 
    $('#createbutton').html('Update');
});

$('.uptbtn').click(function() {
        var id = $(this).data("id"); $("#p_id").val(id);
        var brand_id = $(this).data("brand_id"); $("#pbrand_id").val(brand_id);
        var name = $(this).data("name"); $("#pname").val(name);
        $('#pbrand_id').prop('readonly', true);
        $('#pname').prop('readonly', true);

});

$('.all').hide();
function loadSubCategory() {
    var category=$('#category_id').val();
    if(category!=''){
        $('.all').hide();
        $('.c_'+category).show();
    }
    else{
        $('.all').hide();
    }
}
function showpricestock(){
    $('#uploadstockprice').show();
}
</script>
@stop