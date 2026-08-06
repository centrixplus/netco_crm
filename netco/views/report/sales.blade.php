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
                <form  action="{{url('/report/sales')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}  
                    <div class="row">
                        <div class="col-lg-3">
                            <div>
                                <label>From Date</label>
                                <input class="form-control" type="date" value="{{$fromdate}}" name="fromdate" id="fromdate">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div>
                                <label>To Date</label>
                                <input class="form-control" type="date" value="{{$todate}}" name="todate" id="todate">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <label>Select Customer: </label>
                            <select class="form-control select2" id="customer_id" name="customer_id">
                                <option value="">Select Customer</option>
                                @foreach($customers as $log)
                                <option @if($log->id==$customer_id) selected="" @endif  value="{{$log->id}}">{{$log->name}}  {{$log->mobile}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Select Status: </label>
                            <select class="form-control" id="order_status" name="order_status">
                                <option value="">Select Status</option>
                                @foreach($statuses as $sts)
                                <option @if($sts->name==$status) selected="" @endif  value="{{$sts->name}}">{{$sts->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 mt-3">
                            <label>Select Priority: </label>
                            <select class="form-control select2" id="order_priority" name="order_priority">
                                <option value="">Select Status</option>
                                <option @if($priority=='Urgent') selected="" @endif  value="Urgent">Urgent</option>
                                <option @if($priority=='Not Urgent') selected="" @endif  value="Not Urgent">Not Urgent</option>
                            </select>
                        </div>
                        <div class="col-lg-3 mt-3">
                            <label>Select Saleman: </label>
                            <select class="form-control" id="user_id" name="user_id">
                                <option value="">Select Saleman</option>
                                @foreach($users as $log)
                                <option @if($log->id==$user_id) selected="" @endif  value="{{$log->id}}">{{$log->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-5 mt-3">
                            <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Search</button>
                            <button type="button" class="btn btn-outline-info waves-effect waves-light me-2 mt-2">Count - {{$indexes->count()}}</button>
                            <button type="button" class="btn btn-outline-success waves-effect waves-light me-2 mt-2">No of Qty - {{$indexes->sum('no_of_qty')}}</button>
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
            <h4 class="text-secondary">{{$title}}</h4>
            <div class="table-responsive">
                <table id="datatable-buttons" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            @if(Auth::user()->role=='admin')<th>Code</th>@endif
                            <th>Location</th>
                            <th>Address</th>
                            <th>Date</th>
                            <th>Priority</th>
                            <th>No.of.Qty</th>
                            <th>Grand Total</th>
                            <th>Note</th>
                            <th>Document No</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Created at</th>
                        </tr>
                    </thead>           
                    <tbody>
                        <?php $x=1; ?>
                        @foreach ($indexes as $index)

                        <tr>
                            <td>{{$x++}}</td>
                            <td>{{App\Models\Sale::saleno($index->id)}}</td>
                            <td>{{$index->customer->name}}</td>
                            @if(Auth::user()->role=='admin')<td>{{$index->customer->code}}</td>@endif
                            <td>@if($index->customer->location){{$index->customer->location->name}}@endif</td>
                            <td>{{$index->customer->note}}</td>
                            <td>{{date('d M, Y',strtotime($index->sale_date))}}</td>
                            <td>{{$index->priority}}</td>
                            <td>{{$index->no_of_qty}}</td>
                            <td>{{$index->grand_total}}</td>
                            <td>{{$index->note}}</td>
                            <td>{{$index->invoice_no}}</td>
                            <td>{{$index->status}}</td>
                            <td>{{App\Models\User::find($index->created_by)->name}}</td>
                            <td>{{date('d M, Y H:i:s',strtotime($index->created_at))}}<br>
                                <!-- {{$index->created_at->diffForHumans()}} -->
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


</script>
@stop