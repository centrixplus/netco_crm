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

<div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel">Upload Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form  action="{{url('/products/upload')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}  
            <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <div class="col-lg-12 mb-3">
                                 <label>Brand <span class="text-danger">*</span></label>
                                 <select class="form-control" name="brnad" id="brand" required="">
                                     <option value="">Select Brand</option>
                                     @foreach($brands as $brand)
                                        <option value="{{$brand->id}}">{{$brand->name}}</option>
                                     @endforeach
                                 </select>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label>Select File: <span class="text-danger">*</span></label>
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

<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
        <h4 class="text-secondary"><span id="createbutton">Create</span> {{$title}} <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#myModal">Upload Product<i class="bx bx-upload ms-1"></i></button></h4>
        <div class="row">
            <div class="col-sm-12 pt-3">
                <form  action="{{url('/products/store')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}  
                <div class="row">
                    <div class="col-lg-2 mb-3">
                         <label>Brand <span class="text-danger">*</span></label>
                         <select class="form-control" name="brnad_id" id="brand_id" required="">
                             <option value="">Select Brand</option>
                             @foreach($brands as $brand)
                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                             @endforeach
                         </select>
                    </div>
                    <div class="col-lg-2 mb-3">
                         <label>Categroy <span class="text-danger">*</span></label>
                         <select class="form-control" name="category_id" id="category_id" required="" onchange="loadSubCategory()">
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
                    <div class="col-lg-3 mb-3">
                        <div>
                            <label>Name <span class="text-danger">*</span></label>
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
                            <label>RSP</label>
                            <input type="number" class="form-control" name="price" id="price" required="">
                        </div>
                    </div> 
                    <div class="col-lg-3 mb-3">
                        <div>
                            <label>Description</label>
                            <textarea class="form-control" type="text" name="note" id="note">{{ old('note') }}</textarea>
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
            <table id="datatable-buttons" class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Name</th> 
                        <th>Cost</th>    
                        <th>RSP</th>   
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>           
                <tbody>
                    <?php $x=1; ?>
                    @foreach ($indexes as $index)

                    <tr>
                        <td>{{$x++}}</td>
                        <td>{{$index->brand->name}}</td>
                        <td>@if(!empty($index->category)){{$index->category->name}}@endif</td>
                        <td>@if(!empty($index->subcategory)){{$index->subcategory->name}}@endif</td>
                        <td>{{$index->name}}</td>
                        <td>{{$index->cost}}</td>
                        <td>{{$index->price}}</td>
                        <td>{{$index->note}}</td>
                        <td>
                            <a href="#" data-brand_id="{{$index->brand_id}}"  data-category_id="{{$index->category_id}}" data-subcategory_id="{{$index->subcategory_id}}" data-id="{{$index->id}}" data-name="{{$index->name}}" data-price="{{$index->price}}" data-note="{{$index->note}}" class="btn btn-outline-secondary waves-effect waves-light btn-sm font-size-18 editbtn"><i class="mdi mdi-pencil"></i></a>

                            <a onclick="return confirm('Are you sure?')" href="{{url('/products')}}/{{$index->id}}/delete" class="btn btn-outline-danger waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-trash-can-outline"></i></a>
                        </td>
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

$('.editbtn').click(function() {
    var id = $(this).data("id"); $("#editid").val(id);
    var name = $(this).data("name"); $("#name").val(name);
    var price = $(this).data("price"); $("#price").val(price);
    var note = $(this).data("note"); $("#note").val(note);
    var brand_id = $(this).data("brand_id"); $("#brand_id").val(brand_id);
    var category_id = $(this).data("category_id"); $("#category_id").val(category_id);
    var subcategory_id = $(this).data("subcategory_id"); $("#subcategory_id").val(subcategory_id);
    $('#createbutton').html('Update');
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
</script>
@stop