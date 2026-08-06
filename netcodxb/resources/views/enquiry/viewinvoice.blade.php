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

<div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Update Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  action="{{url('/updateinvoicestatus')}}" method="post">
                {{csrf_field()}}  
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <input type="hidden" name="invoice_id" id="invoice_id">
                            <div>
                                <label>Status:</label>
                                <select class="form-control"  name="order_status" id="order_status" required=""> 
                                    @foreach($ustatuses as $sts)
                                        <option value="{{$sts->id}}">{{$sts->label}}</option>
                                    @endforeach
                                </select>
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
            <h4 class="text-secondary"><span id="createbutton"></span> {{$title}}</h4>
            @if(!empty(Session::get('alert')))<span class="bg-success p-1">{!!Session::get('alert')!!}</span>@endif
            <div class="row">
                <div class="col-sm-12 pt-3">
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Invoice ID:</label><br>
                                @if($log->invoice_no!='') {{$log->invoice_no}} @else {{$log->id}} @endif
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Name:</label><br>
                                {{$log->customer->name}}
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Code:</label><br>
                                {{$log->customer->code}}
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Mobile:</label><br>
                               {{$log->customer->mobile}}
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Area:</label><br>
                                {{$log->customer->area}}
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Address:</label><br>
                                {{$log->customer->note}}
                            </div>
                        </div>  

                        <div class="col-lg-3 mb-3">
                            <label>Invoice Date:</label><br>
                            {{date('d-m-Y',strtotime($log->invoice_date))}}
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>Invoice Status: </label><br>
                            <span class="{{$log->invoicestatus->color}}">{{$log->invoicestatus->name}}</span>
                        </div>         

                        <div class="col-lg-3 mb-3">
                            <label>Amount: </label><br>
                            {{$log->amount_so}}
                        </div> 

                        <div class="col-lg-3 mb-3">
                            <label>Created Date & Time: </label><br>
                            {{date('d-m-Y H:i:s',strtotime($log->created_at))}}
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>Created By: </label><br>
                            {{$log->user->name}}
                        </div>

                        <h5>Invoice Lists </h5>
                        <table id="datatable-buttons" class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                   <th>Products</th>
                                   <th>Description</th>
                                   <th>RSP</th>
                                   <th>Order QTY</th>
                                   <th>Target Price</th>
                                   <th>SO QTY</th>
                                   <th>Total Price</th>
                                   <th>Los Quantity</th>
                                   <!-- <th>Los Price</th> -->
                                   <!-- <th>Los Reason</th> -->
                                   <th>Invoiced</th>
                                </tr>
                            </thead>           
                            <tbody>
                                @foreach ($log->invoicelines as $index)
                                <tr>
                                   <th>{{$index->product->name}}</th>
                                   <th>{{$index->product->note}}</th>
                                   <th>{{$index->rsp}}</th>
                                   <th>{{$index->quantity_po}}</th>
                                   <th>{{$index->target_price}}</th>
                                   <th>{{$index->quantity_so}}</th>
                                   <th>{{$index->amount}}</th>
                                   <th>{{$index->quantity_los}}</th>
                                   <!-- <th>{{$index->los_price}}</th> -->
                                   <!-- <th>{{$index->reason}}</th> -->
                                   <th>@if($index->is_invoiced==0) No @else Yes @endif</th>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <h5 class="mt-3">Invoice Status Logs 

                        @if($log->invoicestatus->status_order=='1')
                            <a href="{{url('/invoiceapprove')}}/{{$log->id}}" onclick="return confirm('Are you sure?')" class="btn btn-outline-primary waves-effect waves-light btn-sm d-none">Approve</a>

                             <button data-id="{{$log->id}}" type="button" class="btn btn-outline-info waves-effect waves-light btn-sm updatebtn" data-bs-toggle="modal" data-bs-target="#myModal"> <i class="mdi mdi-upload ms-1"></i></button>
                        @elseif($log->invoicestatus->status_order=='2')
                        
                            <a href="{{url('/invoicetoso')}}/{{$log->id}}" onclick="return confirm('Are you sure?')" class="btn btn-outline-success waves-effect waves-light btn-sm">Convert SO</a>
                        @elseif($log->invoicestatus->status_order=='3')
                            <a href="{{url('/showsales')}}/{{$log->sale_id}}" target="_blank" class="btn btn-outline-secondary waves-effect waves-light btn-sm">SO</a>
                        
                        @endif

                        </h5>
                        <table id="datatable" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Status</th>
                                    <th>Updated at</th>
                                    <th>Updated by</th>
                            </thead>           
                            <tbody>
                                <?php $x=1; ?>
                                @foreach($log->invoicelogs as $index)
                                <tr>
                                    <td>{{$x++}}</td>
                                    <td>{{$index->invoicestatus->name}}</td>
                                    <td>{{$index->user->name}}</td>
                                    <td>{{date('d-m-Y h:i:s A',strtotime($index->created_at))}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <h5 class="mt-3">Sales Status Logs @if($log->sale_id!=0){{App\Models\Sale::Find($log->sale_id)->sale_no}} @endif</h5>
                        <table class="table table-bordered table-responsive mt-3">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Status</th>
                                    <th>Document No</th>
                                    <th>Invoice Amount</th>
                                    <th>Reason</th>
                                    <th>Warehouse Assign</th>
                                    <th>AWB</th>
                                    <th>No.of.Boxes</th>
                                    <th>Updated Date & Time</th>
                                    <th>Updated By</th>
                                </tr>
                            </thead>           
                            <tbody>
                                <?php $x=1; ?>
                                @foreach ($statuslogs as $index)

                                <tr>
                                    <td>{{$x++}}</td>
                                    <td>{{$index->status}}</td>
                                    <td>{{$index->document_no}}</td>
                                    <td>{{$index->invoice_amount}}</td>
                                    <td>{{$index->reason}}</td>
                                    <td>@if($index->sale->warehousepersion){{$index->sale->warehousepersion->name}}@endif</td>
                                    <td>{{$index->awb_no}}</td>
                                    <td>{{$index->box}}</td>
                                    <td>{{date('d-m-Y H:i:s',strtotime($index->created_at))}}</td>
                                    <td>{{App\Models\User::find($index->created_by)->name}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>
<script src="{{asset('/assets')}}/js/pages/form-advanced.init.js"></script>
<script>
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

$('.updatebtn').click(function() {
    var id = $(this).data("id"); $("#invoice_id").val(id);
});
</script>
@stop