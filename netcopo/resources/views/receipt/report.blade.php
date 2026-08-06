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
                <h4 class="text-secondary"> {{$title}}</h4>
                <form  action="{{url('/receipt/report')}}" method="post" enctype="multipart/form-data">
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
                        @if(Auth::user()->role!='Receipt')
                        <div class="col-lg-2 mb-3">
                            <div>
                                <label>company<span class="text-danger">*</span></label>
                                
                                <select onchange="loadBranch()" name="company_id" id="company_id" required="" class="form-control">
                                    <option value="">Select</option>
                                    @foreach($companies as $company)
                                     <option @if($company_id==$company->id || Auth::user()->company_id==$company->id) selected="" @endif value="{{$company->id}}">{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 mb-3">
                            <div>
                                <label>Branch</label>
                                <select name="branch_id" id="branch_id"  class="form-control" >
                                    <option value="">Select</option>
                                    @foreach($branches as $branch)
                                     <option class="allb b_{{$branch->company_id}}" @if($branch_id==$branch->id || Auth::user()->branch_id==$branch->id) selected="" @endif value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                    <div class="col-lg-2 mb-3">
                        <div>
                            <label>Customer</label>
                            <select name="customer_id" id="customer_id"  class="form-control select2" >
                                <option value="">Select</option>
                                @foreach($customers as $customer)
                                 <option class="allc c_{{$customer->branch_id}}" @if($customer_id==$customer->id) selected="" @endif value="{{$customer->id}}">{{$customer->code}} - {{$customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-2 mb-3">
                        <div>
                            <label>Payment Type</label>
                            <select class="form-control" name="paymenttype_id" id="paymenttype_id">
                                <option value="">Select</option>
                                @foreach($paymenttypes as $paymenttype)
                                 <option @if($paymenttype_id==$paymenttype->id) selected="" @endif value="{{$paymenttype->id}}">{{$paymenttype->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>  

                    <div class="col-lg-2 mb-3">
                        <div>
                            <label>Status</label>
                            <select class="form-control" name="rvstage_id" id="rvstage_id">
                                <option value="">Select</option>
                                @foreach($rvstages as $rvstage)
                                 <option @if($rvstage_id==$rvstage->id) selected="" @endif value="{{$rvstage->id}}">{{$rvstage->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>  

                    <div class="col-lg-2 mb-3">
                        <div>
                            <label>Sale User</label>
                            <select class="form-control" name="user_id" id="user_id">
                                <option value="">Select</option>
                                @foreach($users as $user)
                                 <option @if($user_id==$user->id) selected="" @endif value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>   

                    <div class="col-lg-2 mb-3">
                        <div>
                            <label>Payee Collect Date</label>
                            <input value="{{$payment_collect_date}}" class="form-control" type="date" name="payment_collect_date" id="payment_collect_date">
                        </div>
                    </div>

                    <div class="col-lg-6 mt-3">
                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Search</button>
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
            @if($company_id!='')
            <div class="table-responsive">
                <table id="datatable-buttons" class="table table-bordered table-responsive" style="font-size: 10px !important;border-collapse: collapse !important;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>View</th>
                            <th>Print</th>
                            <th>Company</th>
                            <th>Branch</th>
                            <th>Customer Code</th>
                            <th>Customer</th>
                            <th>Receipt No</th>
                            <th>Receipt Date</th>
                            <th>Receipt Amount in {{$company_info->country->currency_unit}}</th>
                            <th>Payment Type</th>
                            <th>Payee Refrence No</th>
                            <th>Payee Bank</th>
                            <th>Payee Collect Date</th>
                            <th>RV Notes</th>
                            <th>Particulars</th>
                            <th>Status</th>
                            <th>Note</th>
                            <th>Payment Deposit Bank</th>
                            <th>Payment Deposit Ref No</th>
                            <th>Reconciled Amount</th>
                            <th>Reconciled Charge</th>
                            <th>Reconciled Refrence Number</th>
                            <th>Created By</th>
                            
                        </tr>
                    </thead>           
                    <tbody>
                        <?php $x=1; ?>
                        @foreach ($indexes as $index)
                        <?php $receiptlog=App\Models\Receiptlog::where('receipt_id',$index->id) ->orderBy('id','desc')->first(); ?>
                        <tr class="{{$index->rvstage->color_code}}">
                            <td>{{$x++}}</td>
                            <td><a href="{{url('/receiptviewreport')}}/{{$index->id}}" class="btn btn-outline-info waves-effect waves-light btn-sm font-size-10"><i class="mdi mdi-eye"></i></a></td>
                            <td>@if($index->rvstage_id!='1')<a href="{{url('/receiptprint')}}/{{$index->id}}" class="btn btn-outline-warning waves-effect waves-light btn-sm font-size-10"><i class="mdi mdi-cloud-print-outline"></i></a>@endif</td>
                            <td>{{$index->company->name}}</td>
                            <td>{{$index->branch->name}}</td>
                            <td>@if($index->customer){{$index->customer->code}}@endif</td>
                            <td>@if($index->customer){{$index->customer->name}}@endif</td>
                            <td>{{$index->receipt_no}}</td>
                            <td>{{date('d-m-Y',strtotime($index->receipt_date))}}</td>
                            <td>{{$index->receipt_amount}}</td>
                            <td>{{$index->paymenttype->name}}</td>
                            <td>{{$index->payment_reference_no}}</td>
                            <td>{{$index->payment_bank}}</td>
                            <td>{{date('d-m-Y',strtotime($index->payment_collect_date))}}</td>
                            <td>{{$index->notes}}</td>
                            <td>{{$index->particulars}}</td>
                            <td>{{$index->rvstage->name}}</td>
                            <td>@if($receiptlog){{$receiptlog->notes}} @endif</td>
                            <td>@if($index->paymentbank){{$index->paymentbank->name}}@endif</td>
                            <td>{{$index->payment_deposit_refrence_number}}</td>
                            <td>{{$index->receipt_reconcil_amount}}</td>
                            <td>{{$index->receipt_reconcil_commission}}</td>
                            <td>{{$index->reconcil_refrence_no}}</td>
                            <td>{{$index->user->name}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <center><h4>Select Company to load Report</h4></center>
            @endif
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

$('.allb').hide();
$('.allc').hide();
loadBranch();
function loadBranch() {
    var company_id=$('#company_id').val();
    if(company_id!=''){
        $('.allb').hide();
        $('.b_'+company_id).show();
    }
}
loadCustomer();
function loadCustomer() {
    var branch_id=$('#branch_id').val();
    if(branch_id!=''){
        $('.allc').hide();
        $('.c_'+branch_id).show();
    }
}

</script>
@stop