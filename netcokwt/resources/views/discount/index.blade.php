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
        <h4 class="text-secondary"><span id="createbutton">Create</span> {{$title}}</h4>
        <div class="row">
            <div class="col-sm-12 pt-3">
                <form  action="{{url('/discount/store')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}  
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="name" id="name" required="">
                            <input type="hidden" name="editid" id="editid">
                        </div>
                    </div>       
                    <div class="col-lg-4 mb-3">
                        <label>Percent <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="percent" id="percent"  required="">
                    </div>
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Save</button>
                        <a href="{{url('/discount')}}" class="btn btn-secondary waves-effect waves-light resetbtn mt-2">Cancel</a>
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
                        <th>Name</th>
                        <th>Percent %</th>
                        <th>Action</th>
                    </tr>
                </thead>           
                <tbody>
                    <?php $x=1; ?>
                    @foreach ($indexes as $index)

                    <tr>
                        <td>{{$x++}}</td>
                        <td>{{$index->name}}</td>
                        <td>{{$index->percent}}</td>
                        <td>
                            <a href="#" data-id="{{$index->id}}" data-name="{{$index->name}}" data-percent="{{$index->percent}}" class="btn btn-outline-secondary waves-effect waves-light btn-sm font-size-18 editbtn"><i class="mdi mdi-pencil"></i></a>

                            <a onclick="return confirm('Are you sure?')" href="{{url('/discount')}}/{{$index->id}}/delete" class="btn btn-outline-danger waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-trash-can-outline"></i></a>
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
    var percent = $(this).data("percent"); $("#percent").val(percent);
    $('#createbutton').html('Update');
});
</script>
@stop