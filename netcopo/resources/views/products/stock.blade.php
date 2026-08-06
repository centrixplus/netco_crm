@extends('layouts.master')
@section('title',$title)
@section('StyleContent')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/jquery-ui.min.css" rel="stylesheet" type="text/css" />
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
        <h4 class="text-secondary"><span id="createbutton">Upload</span> {{$title}}</h4>
        <span class="text-success">{!!Session::get('alert')!!}</span>
        <div class="row">
            <div class="col-sm-12 pt-3">
                <form  action="{{url('/stock/store')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}  
                <div class="row">
                     <div class="col-lg-3 mb-3">
                         <label>Brand <span class="text-danger">*</span></label>
                         <select class="form-control select2" multiple="" name="brand_ids[]" id="brand_id" required="">
                             <option value="">Select Brand</option>
                             @foreach($brands as $brand)
                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                             @endforeach
                         </select>
                    </div>
                    <div class="col-lg-3 mb-3">
                        <div>
                            <label>Select File<span class="text-danger">*</span><span class="text-danger">Product Name With brand Prefix</span></label>
                            <input class="form-control" type="file" name="import_file" id="import_file" required="">
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

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
            <h4 class="text-secondary"><span id="createbutton">Add</span> {{$title}}</h4>
            <span class="text-success">{!!Session::get('alert')!!}</span>
            <div class="row">
                <div class="col-sm-12 pt-3">
                    <form  action="{{url('/stock/add')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}  
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Product <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id='productname'>
                                <input type="hidden" name="product_id" id='product'>
                            </div>
                        </div>  
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Stock Quanitity <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="1" step="1" name="qty" id="qty">
                            </div>
                        </div> 

                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>T-Quanitity<span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="1" step="1" name="t_qty" id="t_qty">
                            </div>
                        </div> 

                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>PDC <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" min="1" step="1" name="pdc" id="pdc">
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

<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h4 class="text-secondary">{{$title}} List</h4>
            <form  action="{{url('/productstock/search')}}" method="post" enctype="multipart/form-data">
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
                        <input type="text" class="form-control" name="product__stock_search" id="product__stock_search" required="">
                    </div>
                    <div class="col-lg-2 mb-3">
                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Search</button>
                    </div>

                </div>
            </form>
            <table id="datatable-buttons" class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Description</th>
                        <th>Free Quantity</th>
                        <th>Transit Quantity</th>
                        <th>PDC Quantity</th>
                        <th>Stock Date</th>
                    </tr>
                </thead>           
                <tbody>
                    <?php $x=1; ?>
                    @foreach ($indexes as $index)

                    <tr>
                        <td>{{$x++}}</td>
                        <td>@if($index->product){{$index->product->name}}@endif</td>
                        <td>@if($index->product){{$index->product->note}}@endif</td>
                        <td>{{$index->qty}}</td>
                        <td>{{$index->t_qty}}</td>
                        <td>{{$index->pdc}}</td>
                        <td>{{date('d M, Y',strtotime($index->updated_at))}}</td>
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
<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>
<script src="{{asset('/assets')}}/js/pages/form-advanced.init.js"></script>
<script src="{{asset('/assets')}}/libs/jquery-ui.min.js"></script>
<script>
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

</script>
<script type="text/javascript">

    // CSRF Token
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function(){

      $( "#productname" ).autocomplete({
        source: function( request, response ) {
          // Fetch data
          if(request.term.length>5){
             $.ajax({
            url:"{{route('autocomplete')}}",
            type: 'post',
            dataType: "json",
            data: {
               _token: CSRF_TOKEN,
               search: request.term
            },
            success: function( data ) {
               response( data );
            }
          });
          }
         
        },
        select: function (event, ui) {
           // Set selection
           $('#productname').val(ui.item.label); // display the selected text
           $('#product').val(ui.item.value); // save selected id to input
           return false;
        }
      });

    });
</script>
@stop