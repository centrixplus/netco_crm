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
        <h4 class="text-secondary">{{$title}} <a href="{{url('/quotelist')}}"> Quote </a> / {{$quote->quote_no}}</h4>
        <div class="row">
            <div class="col-sm-12 pt-3"> 
                <div class="row">

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Customer Code</label><br>
                            @if($quote->customer){{$quote->customer->code}}@endif
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Customer</label><br>
                            @if($quote->customer){{$quote->customer->name}}@endif
                        </div>
                    </div>
 
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Quote Date</label><br>
                            {{date('d-m-Y',strtotime($quote->quote_date))}}
                        </div>
                    </div>  
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Brand</label><br>
                            {{$quote->brand->name}}
                        </div>
                    </div>    
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Created User</label><br>
                            {{$quote->user->name}}
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Created Date</label><br>
                            @if($quote->created_at!='')
                                {{date('d-m-Y',strtotime($quote->created_at))}} 
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Price Level</label><br>
                            {{$quote->price_level}}
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Currency</label><br>
                            {{$quote->currency->name}}
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Notes</label><br>
                            {{$quote->note}}
                        </div>
                    </div>
            </div>
        </div>
        </div>
    </div>

</div>
<div class="col-md-12">
        <div class="card">
            <div class="card-body">
            <h4 class="text-secondary">Quote List
               
            </h4>
            <table id="datatable-buttons" class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Description</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @php $x=1; @endphp
                    @foreach($quote->quoteline as $log)
                        <tr>
                            <td>{{$x++}}</td>
                            <td>{{$log->product}}</td>
                            <td>{{$log->quantity}}</td>
                            <td>{{$log->description}}</td>
                            <td>{{$log->price}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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