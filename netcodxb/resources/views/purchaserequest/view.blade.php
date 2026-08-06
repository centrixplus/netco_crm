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
        <h4 class="text-secondary">{{$title}} - {{$pr->pr_no}}</h4>
        <div class="row">
            <div class="col-sm-12 pt-3"> 
                <div class="row">

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Customer Code</label><br>
                            @if($pr->customer){{$pr->customer->code}}@endif
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Customer</label><br>
                            @if($pr->customer){{$pr->customer->name}}@endif
                        </div>
                    </div>
 
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Purchase Request Date</label><br>
                            {{date('d-m-Y',strtotime($pr->receipt_date))}}
                        </div>
                    </div>  
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Purchase Request Type</label><br>
                            {{$pr->requesttype->name}}
                        </div>
                    </div>    
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Priority</label><br>
                            {{$pr->priority}}
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Status</label><br>
                            {{$pr->prstatus->name}}
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Created Date</label><br>
                            @if($pr->created_at!='')
                                {{date('d-m-Y',strtotime($pr->created_at))}} 
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Notes</label><br>
                            {{$pr->note}}
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
            <h4 class="text-secondary">Purchase Request List
                @if(Auth::user()->role=='admin' || Auth::user()->role=='PR')

                    @if($pr->prstatus_id=='1')
                    <a href="{{url('status/purchaserequest')}}/{{$pr->id}}/2" onclick="return confirm('Are you sure Approve PR?')" class="btn btn-outline-success waves-effect waves-light btn-sm"><i class="dripicons-thumbs-up"></i></a>

                    <a href="{{url('status/purchaserequest')}}/{{$pr->id}}/3" onclick="return confirm('Are you sure Rejrct PR?')" class="btn btn-outline-warning waves-effect waves-light btn-sm"><i class="dripicons-thumbs-down"></i></a>
                    @endif

                @endif
            </h4>
            <table id="datatable-buttons" class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Target Price</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @php $x=1; @endphp
                    @foreach($pr->purchaserequestlines as $log)
                        <tr>
                            <td>{{$x++}}</td>
                            <td>{{$log->product->name}}</td>
                            <td>{{$log->quantity}}</td>
                            <td>{{$log->target_price}}</td>
                            <td>{{$log->note}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> 
<div class="col-md-12">
        <div class="card">
            <div class="card-body">
            <h4 class="text-secondary">Purchase Request Logs</h4>
            <table id="datatable-buttons" class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Updated By</th>
                    </tr>
                </thead>
                <tbody>
                    @php $x=1; @endphp
                    @foreach($pr->purchaserequestlogs as $log)
                        <tr>
                            <td>{{$x++}}</td>
                            <td>{{$log->prstatus->name}}</td>
                            <td>{{date('d-m-Y H:i:s',strtotime($log->created_at))}}</td>
                            <td>{{App\Models\User::find($log->created_by)->name}}</td>
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
function showPayDep(){
    var rvstage_id=$('#rvstage_id').val();
    if(rvstage_id==3){
        $('.pdep').show();
        $('.rdep').hide();
        $('#payment_deposit_refrence_number').attr('required', 'required');
        $('#paymentbank_id').attr('required', 'required');
    }
    else if(rvstage_id==6){
        $('.rdep').show();
        $('.pdep').hide();
        $('#reconcil_refrence_no').attr('required', 'required');
    }
    else{
        $('.pdep').hide();
        $('.rdep').hide();
        $('#payment_deposit_refrence_number').removeAttr('required');
        $('#paymentbank_id').removeAttr('required');
        $('#reconcil_refrence_no').removeAttr('required');
    }
}


</script>
@stop