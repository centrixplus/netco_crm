@extends('layouts.master')
@section('title',$title)
@section('StyleContent')
    <link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <script src="https://unpkg.com/htmx.org@1.9.2"></script>
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
                <h4 class="text-secondary">Active Users List</h4>
                <div hx-get="{{url('/activelist')}}" hx-trigger="load, every 60s" hx-target="#active_list"><div id="active_list">Loading content</div></div>
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
        $('.editbtn').click(function() {
            var id = $(this).data("id"); $("#editid").val(id);
            var name = $(this).data("name"); $("#name").val(name);
            var currency_unit = $(this).data("currency_unit"); $("#currency_unit").val(currency_unit);
            var currency_unit_decimal = $(this).data("currency_unit_decimal"); $("#currency_unit_decimal").val(currency_unit_decimal);
            $('#createbutton').html('Update');
        });
    </script>
@stop