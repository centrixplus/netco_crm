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
        <h4 class="text-secondary"><span id="createbutton">Update</span> {{$title}}</h4>
        <div class="row">
            <div class="col-sm-12 pt-3">
                <form  action="{{url('/settings/store')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}  
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Name <span class="text-danger">*</span></label>
                            <input class="form-control" value="{{$setting->name}}" type="text" name="name" id="name" required="">
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Mobile</label>
                            <input class="form-control" value="{{$setting->mobile}}" type="text" name="mobile" id="mobile">
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Mobile2</label>
                            <input class="form-control" value="{{$setting->mobile2}}" type="text" name="mobile2" id="mobile2">
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Address</label>
                            <textarea class="form-control" type="text" name="address" id="address">{{$setting->address}}</textarea>
                        </div>
                    </div>  
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Address2</label>
                            <textarea class="form-control" type="text" name="address2" id="address2">{{$setting->address2}}</textarea>
                        </div>
                    </div>     
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>City</label>
                            <input class="form-control" value="{{$setting->city}}" type="text" name="city" id="city">
                        </div>
                    </div>  
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Pincode</label>
                            <input class="form-control" value="{{$setting->pincode}}" type="text" name="pincode" id="pincode">
                        </div>
                    </div>   
                    <div class="col-lg-4 mb-3" style="display: none;">
                        <div>
                            <label>GST NO</label>
                            <input class="form-control" value="{{$setting->gst}}" type="text" name="gst" id="gst">
                        </div>
                    </div> 
                    <div class="col-lg-4 mb-3" style="display: none;">
                        <div>
                            <label>Tax %</label>
                            <input class="form-control" value="{{$setting->tax}}" type="text" name="tax" id="tax">
                        </div>
                    </div> 
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Logo</label>
                            <input class="form-control" type="file" name="logo" id="logo">
                        </div>
                    </div>   
                    <div class="col-lg-4 mb-3">
                        <img src="{{url('/')}}/{{$setting->logo}}" style="width:80px;">
                    </div>
                    <div class="col-lg-4 mt-3">
                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Update</button>
                    </div>
                </form>
            </div>
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
<script>
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

$('.editbtn').click(function() {
    var id = $(this).data("id"); $("#editid").val(id);
    var name = $(this).data("name"); $("#name").val(name);
    var mobile = $(this).data("mobile"); $("#mobile").val(mobile);
    var email = $(this).data("email"); $("#email").val(email);
    var note = $(this).data("note"); $("#note").val(note);
    $('#createbutton').html('Update');
});
</script>
@stop