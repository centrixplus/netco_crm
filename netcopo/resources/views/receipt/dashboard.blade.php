@extends('layouts.master')
@section('title','Home')
@section('StyleContent')
@endsection
@section('PageContent')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Home Page</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form  action="{{url('/receiptdashboard')}}" method="post" enctype="multipart/form-data">
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
                                
                                <select onchange="loadBranch()" name="company_id" id="company_id" required="" class="form-control" required="">
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
                        <div class="col-lg-2 mt-3">
                            <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Search</button>
                            <button type="button" onclick="refreshDashboard()" class="btn btn-info waves-effect waves-light me-2 mt-2">Refresh</button>
                        </div>
                         <div class="col-lg-2">
                            <div class="alert alert-info">
                                The Records Will Refresh In <strong id="countdowntimer">10</strong> Seconds.
                                <input type="hidden" name="countdown" id="countdown" value="10">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<span id="receiptslastfivehtml">
@if($company_id!='')
<div class="row">
    
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-xs me-3">
                        <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-16">
                            {{$indexes->count()}}
                        </span>
                    </div>
                    <h5 class="font-size-14 mb-0">Total Receipts</h5>
                </div>
                <div class="text-muted mt-4">
                    <div class="d-flex">
                        <center><span class="badge badge-soft-success me-2 font-size-14">{{$indexes->sum('receipt_amount')}} {{$company_info->country->currency_unit}}</span></center>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach($rvstages as $rvstage)
    <div class="col-sm-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-xs me-3">
                        <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-16">
                            {{$indexes->where('rvstage_id',$rvstage->id)->count()}}
                        </span>
                    </div>
                    <h5 class="font-size-14 mb-0">{{$rvstage->name}} Receipts</h5>
                </div>
                <div class="text-muted mt-4">
                    <div class="d-flex">
                        @if($rvstage->id==6)
                            <center><span class="badge badge-soft-success me-2 font-size-14">{{$indexes->where('rvstage_id',$rvstage->id)->sum('receipt_reconcil_amount')}} {{$company_info->country->currency_unit}}</span></center>
                        @else
                            <center><span class="badge badge-soft-success me-2 font-size-14">{{$indexes->where('rvstage_id',$rvstage->id)->sum('receipt_amount')}} {{$company_info->country->currency_unit}}</span></center>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endforeach
    <div class="col-sm-12"></div>
    @if(Auth::user()->role=='admin' || Auth::user()->role=='KO-02' || Auth::user()->role=='TL-01')
    <div class="col-sm-4">
       
        <div class="card">
            <div class="card-body">
                 <h5>Payment Type Wiese Receipt:</h5>
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle">Payment Type</th>
                                <th class="align-right">Count</th>
                                <th class="align-right">Amount in {{$company_info->country->currency_unit}}</th>
                                <th class="align-right">Charges {{$company_info->country->currency_unit}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paymenttypes as $paymenttype)
                                <tr>
                                    <td class="align-middle">{{$paymenttype->name}}</td>
                                    <td class="align-right">{{$indexes->where('paymenttype_id',$paymenttype->id)->count()}}</td>
                                    <td class="align-right">{{$indexes->where('paymenttype_id',$paymenttype->id)->sum('receipt_amount')}}</td>
                                    <td class="align-right">{{$indexes->where('paymenttype_id',$paymenttype->id)->sum('receipt_reconcil_commission')}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-8">
        <div class="card">
            <div class="card-body">
                <h5>Today Collect List:</h5>
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="align-middle">Customer Code</th>
                                    <th class="align-middle">Customer</th>
                                    <th class="align-right">Receipt No</th>
                                    <th class="align-right">Amount in {{$company_info->country->currency_unit}}</th>
                                    <th class="align-middle">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @foreach($indexes->where('payment_collect_date',date('Y-m-d')) as $log)
                                 <tr>
                                    <td>@if($log->customer){{$log->customer->code}}@endif</td>
                                    <td>@if($log->customer){{$log->customer->name}}@endif</td>
                                    <td>{{$log->receipt_no}}</td>
                                    <td>{{$log->receipt_amount}}</td>
                                    <td>
                                        <a href="{{url('/receiptview')}}/{{$log->id}}" class="btn btn-outline-info waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-eye"></i></a>
                                    </td>
                                 </tr>
                                 @endforeach
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>
    @endif
</div>
@else
<div class="row">
    <center><h4>Select Company to load Dashboard</h4></center>
</div>
@endif
</span>
@endsection
@section('ScriptContent')
<script type="text/javascript">
    $('.allb').hide();
    loadBranch();
    function loadBranch() {
        var company_id=$('#company_id').val();
        if(company_id!=''){
            $('.allb').hide();
            $('.b_'+company_id).show();
        }
    }

    $(document).ready(function() {

setInterval(function(){
    $("#countdown").val(10);
    var timeleft = $("#countdown").val(); 
    timeleft = parseInt(timeleft);
    //alert(timeleft);

   setInterval(function(){
    //refreshDashboard();
    timeleft--;
    if(timeleft >= 0){        
    document.getElementById("countdowntimer").textContent = timeleft;
    }
    },1000);

}, 10000);
});

var timeleft = 10; 
var downloadTimer = setInterval(function(){
timeleft--;
document.getElementById("countdowntimer").textContent = timeleft;
if(timeleft <= 0)
    clearInterval(downloadTimer);
},1000);


function refreshDashboard() {
   var fromdate=$('#fromdate').val();
   var todate=$('#todate').val();
   var company_id=$('#company_id').val();
   var branch_id=$('#branch_id').val();

   $.ajax({ url: "{{url('/refreshDashboard')}}"+'/'+fromdate+'/'+todate+'/'+company_id+'/'+branch_id,
        type: "get", cache: false, dataType: 'json',
        success: function (data) {
            $('#receiptslastfivehtml').html(data.html);
        }
    })
}
</script>
@endsection