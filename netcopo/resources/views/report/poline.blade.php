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
                <form  action="{{url('/report/poline')}}" method="post" enctype="multipart/form-data">
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
                            <label>Select PO: </label>
                            <select class="form-control select2 vendor" id="po_id" name="po_id">
                                <option value="">All PO</option>
                                @foreach($po as $log)
                                <option @if($log->id==$po_id) selected="" @endif  value="{{$log->id}}">{{$log->po_no}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label>Select Product: </label>
                            <select class="form-control select2 vendor" id="product_id" name="product_id">
                                <option value="">All Product</option>
                                @foreach($products as $log)
                                <option @if($log->id==$product_id) selected="" @endif  value="{{$log->id}}">{{$log->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 mt-3">
                            <label>Select Vendor: </label>
                            <select class="form-control select2 vendor" id="vendor_id" name="vendor_id">
                                <option value="">All Vendor</option>
                                @foreach($vendors as $log)
                                <option @if($log->id==$vendor_id) selected="" @endif  value="{{$log->id}}">{{$log->name}} - {{$log->mobile}}  @if(Auth::user()->role=='admin') - {{$log->code}} @endif</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 mt-3">
                            <label>Select Status: </label>
                            <select class="form-control" id="status_id" name="status_id">
                                <option value="">All Status</option>
                                @foreach($status as $log)
                                <option @if($log->id==$status_id) selected="" @endif  value="{{$log->id}}">{{$log->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 mt-3">
                            <div class="form-group">
                                <label for="cardnameInput">Priority</label>
                                <select class="form-control" id="priority" name="priority">
                                    <option value="">Select</option>
                                    <option @if($priority=="Low") selected="" @endif value="Low">Low</option>
                                    <option @if($priority=="Medium") selected="" @endif value="Medium">Medium</option>
                                    <option @if($priority=="High") selected="" @endif value="High">High</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-8"><br><br>
                            <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Search</button>

                            <a href="#" class="btn btn-outline-info waves-effect waves-light me-2 mt-2">Count - {{$indexes->count()}}</a>

                            <a href="{{url('/polist')}}" class="btn btn-outline-warning waves-effect waves-light me-2 mt-2">Clear Filter</a>
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
            <h4 class="text-secondary">{{$title}} List 
            <div class="table-responsive">
    <table id="datatable-buttons" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
        <thead>
            <tr>
                <th>PO ID</th>
                <th>Vendor</th>
                <th>Code</th>
                <th>Vendor Invoice No</th>
                <th>PO Date</th>
                <th>Customer</th>
                <th>Priority</th>
                <th>Note</th>
                <th>Status</th>
                <th>Product</th>
                <th>Description</th>
                <th>PO Quantity</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Currency</th>
                <th>Received</th>
                <th>Balance</th>

            </tr>
        </thead>           
        <tbody>
            <?php $x=1; ?>
            @foreach ($indexes as $index)
            <tr class="{{$index->po->status->color_code}}">
                <td>{{$index->po->po_no}}</td>
                <td>{{$index->po->vendor->name ?? 'N/A'}}</td>
                <td>{{$index->po->vendor->code ?? 'N/A'}}</td>
                <td>{{$index->po->vendor_invoice_no ?? 'N/A'}}</td>
                <td>{{date('d-m-Y',strtotime($index->po->po_date))}}</td>
                <td>{{$index->po->customer}}</td>
                <td>{{$index->po->priority}}</td>
                <td>{{$index->po->note}}</td>
                <td>{{$index->po->status->name}}</td>
                <td>{{$index->product->name}}</td>
                <td>{{$index->description}}</td>
                <td>{{$index->quantity}}</td>
                <td>{{$index->price}}</td>
                <td>{{$index->total_price}}</td>
                <td>{{$index->po->vendor->currency->name ?? 'N/A'}}</td>
                <td>{{$index->received_quantity}}</td>
                <td>{{$index->balance_quantity}}</td>
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
@if(Auth::user()->role=='admin')
<script>

$(document).ready(function(){

    $("#datatable-buttons").DataTable({"fixedHeader": {
          header: true,
        },lengthChange:!1,buttons:["copy","excel","pdf"], order: [[0, 'desc']],}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm");

    
});

</script>
@else
   <script>
    $(document).ready(function(){

       $("#datatable-buttons").DataTable({order: [[0, 'desc']]});
    });
   </script>
@endif

<script>

</script>
@stop