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
                <h4 class="text-secondary"> {{$title}}</h4>
                <form  action="{{url('/reconciledreceipt')}}" method="post" enctype="multipart/form-data">
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
            <h4 class="text-secondary">{{$title}} List</h4>
            <div class="table-responsive">
                <table id="datatable-buttons" class="table table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Company</th>
                            <th>Branch</th>
                            <th>Customer Code</th>
                            <th>Customer</th>
                            <th>Receipt No</th>
                            <th>Receipt Date</th>
                            <th>Receipt Amount</th>
                            <th>Payment Type</th>
                            <th>Payee Refrence No</th>
                            <th>Particulars</th>
                            <th>Payment Deposit Refrence</th>
                            <th>Deposit Bank</th>
                            <th>Reconciled Amount</th>
                            <th>Reconciled Charge</th>
                            <th>Reconciled Refrence Number</th>
                            <th>Status</th>
                            <th>View</th>
                            <th>Print</th>
                        </tr>
                    </thead>           
                    <tbody>
                        <?php $x=1; ?>
                        @foreach ($indexes as $index)

                        <tr>
                            <td>{{$x++}}</td>
                            <td>{{$index->company->name}}</td>
                            <td>{{$index->branch->name}}</td>
                            <td>@if($index->customer){{$index->customer->code}}@endif</td>
                            <td>@if($index->customer){{$index->customer->name}}@endif</td>
                            <td>{{$index->receipt_no}}</td>
                            <td>{{date('d-m-Y',strtotime($index->receipt_date))}}</td>
                            <td>{{$index->receipt_amount}} {{$index->company->country->currency_unit}}</td>
                            <td>{{$index->paymenttype->name}}</td>
                            <td>{{$index->payment_reference_no}}</td>
                            <td>{{$index->particulars}}</td>
                            <td>{{$index->payment_deposit_refrence_number}}</td>
                            <td>@if($index->paymentbank){{$index->paymentbank->name}}@endif</td>
                            <td>{{$index->receipt_reconcil_amount}} {{$index->company->country->currency_unit}}</td>
                            <td>{{$index->receipt_reconcil_commission}} {{$index->company->country->currency_unit}}</td>
                            <td>{{$index->reconcil_refrence_no}}</td>
                            <td>{{$index->rvstage->name}}</td>
                            <td>
                                <a href="{{url('/receiptview')}}/{{$index->id}}" class="btn btn-outline-info waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-eye"></i></a>
                            </td>
                            <td>
                                <a href="{{url('/receiptprint')}}/{{$index->id}}" class="btn btn-outline-warning waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-cloud-print-outline"></i></a>
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
<script>
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

$('.editbtn').click(function() {
    var id = $(this).data("id"); $("#editid").val(id);
    var company_id = $(this).data("company_id"); $("#company_id").val(company_id);
    var branch_id = $(this).data("branch_id"); $("#branch_id").val(branch_id);
    var customer_id = $(this).data("customer_id"); $("#customer_id").val(customer_id);
    var receipt_no = $(this).data("receipt_no"); $("#receipt_no").val(receipt_no);
    var receipt_date = $(this).data("receipt_date"); $("#receipt_date").val(receipt_date);
    var receipt_amount = $(this).data("receipt_amount"); $("#receipt_amount").val(receipt_amount);
    var paymenttype_id = $(this).data("paymenttype_id"); $("#paymenttype_id").val(paymenttype_id);
    var payment_reference_no = $(this).data("payment_reference_no"); $("#payment_reference_no").val(payment_reference_no);
    var particulars = $(this).data("particulars"); $("#particulars").val(particulars);
    var payment_bank = $(this).data("payment_bank"); $("#payment_bank").val(payment_bank);
    var payment_collect_date = $(this).data("payment_collect_date"); $("#payment_collect_date").val(payment_collect_date);
    
    $('#createbutton').html('Update');
});

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

function getCustomerInfo(){
    var customer_id=$('#customer_id').val();
    if(customer_id!=''){
        $.ajax({ url: "{{url('/customerinforeceipt')}}"+'/'+customer_id,
            type: "get", cache: false, dataType: 'json',
            success: function (data) {
                
                $('#receipt_count').html(data.receipt_count);
                $('#new_count').html(data.new_count);
                $('#authorized_count').html(data.authorized_count);
                $('#posted_count').html(data.posted_count);
                $('#reconciled_count').html(data.reconciled_count);

                $('#receipt_amount_total').html(data.receipt_amount);
                $('#new_amount').html(data.new_amount);
                $('#authorized_amount').html(data.authorized_amount);
                $('#posted_amount').html(data.posted_amount);
                $('#reconciled_amount').html(data.reconciled_amount);

                $('#receipt_date_last').html(data.receipt_date);
            }
        })
    }
   
}
</script>
@stop