@extends('layouts.master')
@section('title',$title)
@section('StyleContent')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
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


<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
            <h4 class="text-secondary">{{$title}}</h4>
                <div class="row">
                    <div class="col-sm-12 pt-3">
                        <div class="row">
                            <div class="col-lg-2 mb-3">
                                 <span class="text-muted">Brand</span><br>
                                 <span class="text-bold">{{$product->brand->name}}</span>
                            </div>

                            <div class="col-lg-2 mb-3">
                                <div>
                                    <span class="text-muted">Product Code</span><br>
                                    <span class="text-bold">{{$product->name}}</span>
                                </div>
                            </div>

                            <div class="col-lg-2 mb-3">
                                 <span class="text-muted">UOM </span><br>
                                 <span class="text-bold">@if(!empty($product->uom)){{$product->uom->name}}</span>@endif
                            </div>  

                            <div class="col-lg-2 mb-3">
                                <div>
                                    <span class="text-muted">Cost Price</span><br>
                                    <span class="text-bold">{{$product->cost}}</span>
                                </div>
                            </div> 

                            <div class="col-lg-2 mb-3">
                                <div>
                                    <span class="text-muted">Landing Cost</span><br>
                                    <span class="text-bold">{{$product->min_price}}</span>
                                </div>
                            </div> 

                            <div class="col-lg-2 mb-3">
                                <div>
                                    <span class="text-muted">RSP</span><br>
                                    <span class="text-bold">{{$product->price}}</span>
                                </div>
                            </div>

                            <div class="col-lg-2 mb-3">
                                 <span class="text-muted">Group</span><br>
                                 <span class="text-bold">@if(!empty($product->group)){{$product->group->name}}</span>@endif
                            </div>

                            <div class="col-lg-2 mb-3">
                                 <span class="text-muted">Categroy </span><br>
                                 <span class="text-bold">@if(!empty($product->group)){{$product->category->name}}</span>@endif
                            </div>

                            <div class="col-lg-2 mb-3">
                                 <span class="text-muted">Subcategory </span><br>
                                 <span class="text-bold">@if(!empty($product->group)){{$product->subcategory->name}}</span>@endif
                            </div>


                            <div class="col-lg-2 mb-3">
                                 <span class="text-muted">Size </span><br>
                                 <span class="text-bold">@if(!empty($product->size)){{$product->size->name}}</span>@endif
                            </div>

                            <div class="col-lg-2 mb-3">
                                 <span class="text-muted">Movement </span><br>
                                 <span class="text-bold">@if(!empty($product->color)){{$product->color->name}}</span>@endif
                            </div>

                            <div class="col-lg-2 mb-3">
                                <div>
                                    <span class="text-muted">Item Code on Invoice</span><br>
                                    <span class="text-bold">{{$product->item_code_invoice}}</span>
                                </div>
                            </div> 

                            <div class="col-lg-2 mb-3">
                                 <span class="text-muted">Alternate Code</span><br>
                                 <span class="text-bold">{{App\Models\Product::FindName($product->product_id)}}</span>
                            </div>

                            <div class="col-lg-2 mb-3">
                                <div>
                                    <span class="text-muted">Vendor Item Code</span><br>
                                    <span class="text-bold">{{$product->vendor_item_code}}</span>
                                </div>
                            </div> 

                            <div class="col-lg-2 mb-3">
                                <div>
                                    <span class="text-muted">HRM Code</span><br>
                                    <span class="text-bold">{{$product->hrm_code}}</span>
                                </div>
                            </div> 

                            <div class="col-lg-2 mb-3">
                                <div>
                                    <span class="text-muted">P/O Unit</span><br>
                                    <span class="text-bold">{{$product->po_qty_limit}}</span>
                                </div>
                            </div> 

                            <div class="col-lg-2 mb-3">
                                 <span class="text-muted">Package Type </span><br>
                                 <span class="text-bold">@if(!empty($product->packagetype)){{$product->packagetype->name}}</span>@endif
                            </div>

                            <div class="col-lg-2 mb-3">
                                <div>
                                    <span class="text-muted">Package Quantity</span><br>
                                    <span class="text-bold">{{$product->package_qty}}</span>
                                </div>
                            </div>

                            <div class="col-lg-2 mb-3">
                                <div>
                                    <span class="text-muted">Tax Apply</span><br>
                                    <span class="text-bold">{{$product->is_tax}}</span>
                                </div>
                            </div>

                            <div class="col-lg-2 mb-3">
                                <div>
                                    <span class="text-muted">Sale Type</span><br>
                                    <span class="text-bold">{{$product->sale_type}}</span>
                                </div>
                            </div>

                            <div class="col-lg-3 mb-3">
                                <div>
                                    <span class="text-muted">Description</span><br>
                                    <span class="text-bold">{{$product->note}}</span>
                                </div>
                            </div>

                             <div class="col-lg-3 mb-3">
                                <div>
                                    <span class="text-muted">Arabic Description</span><br>
                                    <span class="text-bold">{{$product->note_ar}}</span>
                                </div>
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
                <h4 class="text-secondary">Pricelog List</h4>
                <table id="datatable-buttons" class="table table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Cost</th>
                            <th>Landing Cost</th>
                            <th>RSP</th>
                            @if(Auth::user()->role=='admin')<th>Updated By</th>@endif
                        </tr>
                    </thead>           
                    <tbody>
                        <?php $x=1; ?>
                        @foreach ($pricelogs as $index)

                        <tr>
                            <td>{{$x++}}</td>
                            <td>{{date('d M, Y H:i:s',strtotime($index->created_at))}}</td>
                            <td>{{$index->cost}}</td>
                            <td>{{$index->price}}</td>
                            <td>{{$index->min_price}}</td>
                            @if(Auth::user()->role=='admin')<td>{{App\Models\User::find($index->created_by)->name}}</td>@endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
   
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="text-secondary">Dealer Pricelog List</h4>
                <table id="datatable-buttons" class="table table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Price</th>
                            <th>Cost</th>
                            @if(Auth::user()->role=='admin')<th>Updated By</th>@endif
                        </tr>
                    </thead>           
                    <tbody>
                        <?php $x=1; ?>
                        @foreach ($dealerpricelogs as $index)

                        <tr>
                            <td>{{$x++}}</td>
                            <td>{{date('d M, Y H:i:s',strtotime($index->created_at))}}</td>
                            <td>{{$index->price}}</td>
                            <td>{{$index->cost}}</td>
                            @if(Auth::user()->role=='admin')<td>{{App\Models\User::find($index->created_by)->name}}</td>@endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
   
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="text-secondary">Product History Logs</h4>
                <table id="datatable" class="table table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Name</th>
                            @if(Auth::user()->role=='admin')<th>Updated By</th>@endif
                        </tr>
                    </thead>           
                    <tbody>
                        <?php $x=1; ?>
                        @foreach ($producthistory as $index)

                        <tr>
                            <td>{{$x++}}</td>
                            <td>{{date('d M, Y H:i:s',strtotime($index->created_at))}}</td>
                            <td>{{$index->name}}</td>
                            @if(Auth::user()->role=='admin')<td>{{App\Models\User::find($index->created_by)->name}}</td>@endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>

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
<script>
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});
</script>
@stop