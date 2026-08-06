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
        <h4 class="text-secondary">{{$title}} - {{$index->receipt_no}} @if($index->rvstage_id >= 2) <a href="{{url('/receiptprint')}}/{{$index->id}}" class="btn btn-outline-warning waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-cloud-print-outline"></i></a> @endif</h4>
        <div class="row">
            <div class="col-sm-12 pt-3"> 
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>company</label><br>
                            {{$index->company->name}}
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Branch</label><br>
                            {{$index->branch->name}}
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Customer Code</label><br>
                            {{$index->customer->code}}
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Customer</label><br>
                            {{$index->customer->name}}
                        </div>
                    </div>
 
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Receipt Date</label><br>
                            {{date('d-m-Y',strtotime($index->receipt_date))}}
                        </div>
                    </div> 

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Receipt Amount</label><br>
                            {{$index->receipt_amount}} {{$index->company->country->currency_unit}}
                        </div>
                    </div> 
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Payment Type</label><br>
                            {{$index->paymenttype->name}}
                        </div>
                    </div>    
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Payee Reference Number</label><br>
                            {{$index->payment_reference_no}}
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Payee Bank</label><br>
                            {{$index->payment_bank}}
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Payee Collect Date</label><br>
                            @if($index->payment_collect_date!='')
                                {{date('d-m-Y',strtotime($index->payment_collect_date))}} 
                            @endif
                        </div>
                    </div>


                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Payee Reference File</label><br>
                            @if(!empty($index->payment_reference_file))<a target="_blank" href="{{url('/')}}{{$index->payment_reference_file}}">File</a>@endif
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Particulars</label><br>
                            {{$index->particulars}}
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Status</label><br>
                            {{$index->rvstage->name}}
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Notes</label><br>
                            {{$index->notes}}
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Payment Deposit Referance Number</label><br>
                            {{$index->payment_deposit_refrence_number}}<br>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Payment Deposit Bank</label><br>
                            @if($index->paymentbank){{$index->paymentbank->name}}@endif<br>
                        </div>
                    </div>
            </div>
        </div>
        </div>
    </div>

</div> 

@if(Auth::user()->role!='SM-01')
<div class="col-md-12">
        <div class="card">
            <div class="card-body">
                @if($index->rvstage_id!='6')
                <form  action="{{url('/receiptlog/store')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <label>Status<span class="text-danger">*</span></label>
                            @if(Auth::user()->role=='SC-01')
                            <select onchange="showPayDep()" class="form-control" name="rvstage_id" id="rvstage_id" required="">
                                <option value="">Select</option>
                                @foreach($rvstages as $rvstage)
                                    @if($rvstage->stage <= $index->rvstage->stage+1 && $rvstage->stage >= $index->rvstage->stage)
                                        @if($rvstage->id!=$index->rvstage_id && $rvstage->id!=3)
                                            <option value="{{$rvstage->id}}">{{$rvstage->name}}</option>
                                        @endif
                                    @endif
                                @endforeach
                            </select>
                            @else
                            <select onchange="showPayDep()" class="form-control" name="rvstage_id" id="rvstage_id" required="">
                                <option value="">Select</option>
                                @foreach($rvstages as $rvstage)
                                    @if($rvstage->stage <= $index->rvstage->stage+1 && $rvstage->stage >= $index->rvstage->stage)
                                        @if($rvstage->id!=$index->rvstage_id)
                                            <option value="{{$rvstage->id}}">{{$rvstage->name}}</option>
                                        @endif
                                    @endif
                                @endforeach
                            </select>
                            @endif
                        </div>
                        <div class="col-lg-3 mb-3 pdep" style="display: none;">
                            <label>Payment Deposit Referance Number <span class="text-danger">*</span></label>
                            <input type="text" name="payment_deposit_refrence_number" id="payment_deposit_refrence_number" value="{{$index->payment_deposit_refrence_number}}" class="form-control">
                        </div>
                        <div class="col-lg-3 mb-3 pdep" style="display: none;">
                            <label>Payment Deposit Bank <span class="text-danger">*</span></label>
                            <select  class="form-control" name="paymentbank_id" id="paymentbank_id">
                                <option value="">Select</option>
                                @foreach($paymentbanks as $paymentbank)
                                 <option @if($paymentbank->id==$index->paymentbank_id) selected="" @endif value="{{$paymentbank->id}}">{{$paymentbank->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 mb-3 rdep" style="display: none;">
                            <label>Reconcil Referance Number <span class="text-danger">*</span></label>
                            <input type="text" name="reconcil_refrence_no" id="reconcil_refrence_no" value="{{$index->reconcil_refrence_no}}" class="form-control">
                        </div>
                        
                        <div class="col-lg-3 mb-3">
                            <label>Note</label>
                            <input type="hidden" name="receipt_id" value="{{$index->id}}">
                            <textarea class="form-control" id="notes" name="notes"></textarea>
                        </div>
                        <div class="col-lg-3"><br>
                            <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Save</button>
                        </div>
                    </div>  
                 </form>
                 @endif
            <h4 class="text-secondary">Receipt Logs</h4>
            <table id="datatable-buttons" class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Date</th>
                        <th>Updated By</th>
                    </tr>
                </thead>
                <tbody>
                    @php $x=1; @endphp
                    @foreach($logs as $log)
                        <tr>
                            <td>{{$x++}}</td>
                            <td>{{$log->rvstage->name}}</td>
                            <td>{{$log->notes}}</td>
                            <td>{{date('d-m-Y H:i:s',strtotime($log->created_at))}}</td>
                            <td>{{App\Models\User::find($log->created_by)->name}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

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