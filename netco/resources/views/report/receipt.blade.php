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
        <h4 class="text-secondary"><span id="createbutton">Create</span> {{$title}}</h4>
        <div class="row">
            <div class="col-sm-12 pt-3">
                <form  action="{{url('/report/receipt')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}  
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>company<span class="text-danger">*</span></label>
                            <select onchange="loadBranch()" name="company_id" id="company_id" required="" class="form-control" required="">
                                <option value="">Select</option>
                                @foreach($companies as $company)
                                 <option @if(old('company_id')==$company->id) selected="" @endif value="{{$company->id}}">{{$company->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Branch<span class="text-danger">*</span></label>
                            <select onchange="loadCustomer()" name="branch_id" id="branch_id" required="" class="form-control" required="">
                                <option value="">Select</option>
                                @foreach($branches as $branch)
                                 <option class="allb b_{{$branch->company_id}}" @if(old('branch_id')==$branch->id) selected="" @endif value="{{$branch->id}}">{{$branch->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Customer<span class="text-danger">*</span></label>
                            <select name="customer_id" id="customer_id" required="" class="form-control" required="">
                                <option value="">Select</option>
                                @foreach($customers as $customer)
                                 <option class="allc c_{{$customer->branch_id}}" @if(old('customer_id')==$customer->id) selected="" @endif value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">search</button>
                        <a class="btn btn-secondary waves-effect waves-light resetbtn mt-2"></a>
                        <a class="btn btn-secondary waves-effect waves-light resetbtn mt-2"></a>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</div> 
</div>

<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h4 class="text-secondary">{{$title}} List</h4>
            <table id="datatable-buttons" class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Company</th>
                        <th>Branch</th>
                        <th>Customer</th>
                        <th>Receipt No</th>
                        <th>Receipt Date</th>
                        <th>Receipt Amount</th>
                        <th>Payment Type</th>
                        <th>Payment Refrence No</th>
                        <th>Particulars</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>           
                <tbody>
                    <?php $x=1; ?>
                    @foreach ($indexes as $index)
                    <tr>
                        <td>{{$x++}}</td>
                        <td>{{$index->company->name}}</td>
                        <td>{{$index->branch->name}}</td>
                        <td>{{$index->customer->name}}</td>
                        <td>{{$index->receipt_no}}</td>
                        <td>{{date('d-m-Y',strtotime($index->receipt_date))}}</td>
                        <td>{{$index->receipt_amount}}</td>
                        <td>{{$index->payment_type}}</td>
                        <td>{{$index->payment_reference_no}}</td>
                        <td>{{$index->particulars}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
    var payment_type = $(this).data("payment_type"); $("#payment_type").val(payment_type);
    var payment_reference_no = $(this).data("payment_reference_no"); $("#payment_reference_no").val(payment_reference_no);
    var particulars = $(this).data("particulars"); $("#particulars").val(particulars);
    var particular_amount = $(this).data("particular_amount"); $("#particular_amount").val(particular_amount);
    var particular_amount_decimal = $(this).data("particular_amount_decimal"); $("#particular_amount_decimal").val(particular_amount_decimal);
    
    $('#createbutton').html('Update');
});

$('.allb').hide();
$('.allc').hide();

function loadBranch() {
    var company_id=$('#company_id').val();
    if(company_id!=''){
        $('.allb').hide();
        $('.b_'+company_id).show();
    }
}

function loadCustomer() {
    var branch_id=$('#branch_id').val();
    if(branch_id!=''){
        $('.allc').hide();
        $('.c_'+branch_id).show();
    }
}


</script>
@stop