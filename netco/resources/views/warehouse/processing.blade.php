@extends('layouts.master')
@section('title',$title)
@section('StyleContent')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('PageContent')
<style type="text/css">
tr.ex2:hover, a.ex2:active {cursor: pointer; background-color:#adf7a9  ! important;font-size: 115%;}
tr.selected {background-color:#adf7a9  ! important;}
</style>

<div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Update Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  action="{{url('/updatestatus')}}" method="post">
                {{csrf_field()}}  
                <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <input type="hidden" name="sale_id" id="sale_id">
                                <div>
                                    <label>Status:</label>
                                    <select class="form-control"  name="order_status" id="order_status" required="" onchange="changeStatus()"> 
                                        @foreach($statuses as $sts)
                                            <option docs="{{$sts->is_document}}" reasons="{{$sts->is_reason}}" value="{{$sts->name}}">{{$sts->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="inv_no" class="mt-3" style="display: none;">
                                    <label>Document Number:</label>
                                    <input class="form-control" type="text" value="0" placeholder="Enter Document Number" name="invoice_no" id="invoice_no" required="">
                                </div>

                                <div id="reason_tab" class="mt-3" style="display: none;">
                                    <label>Reason:</label>
                                    <textarea class="form-control" name="reason" id="reason"></textarea>
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
                <form  action="{{url('/sale/processing')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}  
                    <div class="row">
                        <div class="col-lg-2">
                            <div>
                                <label>From Date</label>
                                <input class="form-control" type="date" value="{{$fromdate}}" name="fromdate" id="fromdate">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div>
                                <label>To Date</label>
                                <input class="form-control" type="date" value="{{$todate}}" name="todate" id="todate">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <label>Select Customer: </label>
                            <select class="form-control select2 customer" id="customer_id" name="customer_id" onchange="getCustomerInfo()">
                                <option value="">Select Customer</option>
                                @foreach($customers as $log)
                                <option @if($log->id==$customer_id) selected="" @endif  value="{{$log->id}}">{{$log->name}} - {{$log->mobile}}  @if(Auth::user()->role=='admin') - {{$log->code}} @endif</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2" style="display: none;">
                            <label>Select Status: </label>
                            <select class="form-control" id="status_id" name="status_id">
                                <option value="">Select Status</option>
                                @foreach($statuses as $sts)
                                <option @if($sts->name==$status_id) selected="" @endif  value="{{$sts->name}}">{{$sts->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label>Select Priority: </label>
                            <select class="form-control" id="order_priority" name="order_priority">
                                <option value="">Select Status</option>
                                <option @if($priority=='Next Day Delivery') selected="" @endif  value="Next Day Delivery">Next Day Delivery</option>
                                <option @if($priority=='Priority') selected="" @endif  value="Priority">Priority</option>
                            </select>
                        </div>
                        @if(Auth::user()->role=='admin')
                        <div class="col-lg-1">
                            <label>Deleted Sales:</label>
                            <input type="checkbox" name="deleted_sales" id="deleted_sales" @if($del_sales=='on') checked="" @endif>
                        </div>
                        @endif
                        <div class="col-lg-2">
                            <div>
                                <label>Location</label>
                                <select name="location_id" id="location_id" class="form-control select2">
                                    <option value="">Select</option>
                                    @foreach($locations as $location)
                                     <option @if($location_id==$location->id) selected="" @endif value="{{$location->id}}">{{$location->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if(Auth::user()->role=='admin')
                        <div class="col-lg-2 mt-3">
                            <label>Select Saleman: </label>
                            <select class="form-control" id="user_id" name="user_id">
                                <option value="">Select Saleman</option>
                                @foreach($users as $log)
                                <option @if($log->id==$user_id) selected="" @endif  value="{{$log->id}}">{{$log->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-lg-2 mt-3">
                            <div>
                                <label>Filter</label>
                                <select name="filter" id="filter" class="form-control">
                                    <option value="">Select</option>
                                    <option @if($filter=='customers') selected="" @endif value="customers">Customers</option>
                                    <option @if($filter=='statuses') selected="" @endif value="statuses">Status</option>
                                    <option @if($filter=='locations') selected="" @endif value="locations">Locations</option>
                                    <option @if($filter=='users') selected="" @endif value="users">Salesman</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-8 mt-3">
                            <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Search</button>
                            <button type="button" class="btn btn-outline-info waves-effect waves-light me-2 mt-2">Count - {{$indexes->count()}}</button>
                            <button type="button" class="btn btn-outline-secondary waves-effect waves-light me-2 mt-2">Total Quantity- {{$indexes->sum('no_of_qty')}}</button>
                            <button type="button" class="btn btn-outline-success waves-effect waves-light me-2 mt-2">Total Amount- {{$indexes->sum('grand_total')}} AED</button>
                            <a href="{{url('/sale')}}" class="btn btn-outline-warning waves-effect waves-light me-2 mt-2">Clear Filter</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h4 class="text-secondary">{{$title}} List <!-- <a href="{{url('/sale')}}/create" class="btn-sm btn-primary waves-effect waves-light me-2 mt-2">Create</a> --> </h4>
            {!!$saletable!!}
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
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({"fixedHeader": {
      header: true,
    },lengthChange:!1,buttons:["copy","excel","pdf"], order: [[0, 'desc']],}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

$('.updatebtn').click(function() {
    var id = $(this).data("id"); $("#sale_id").val(id);
    var status = $(this).data("status"); $("#order_status").val(status);
    var invoice_no = $(this).data("invoice_no"); $("#invoice_no").val(invoice_no);
});

function changeStatus() {
    var order_status=$('#order_status').val();
    var docs=$('#order_status').find('option:selected').attr('docs');
    var reasons=$('#order_status').find('option:selected').attr('reasons');
   
    
    if(docs=='1'){
        $('#inv_no').show();
    }
    else{
        $('#inv_no').hide();
    }

    if(reasons=='1'){
        $('#reason_tab').show();
    }
    else{
        $('#reason_tab').hide();
        $('#reason').val('');
    }
}
</script>
@stop