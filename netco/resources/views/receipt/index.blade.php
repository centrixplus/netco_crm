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
                <form  action="{{url('/receipt/store')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}  
                    <div class="row">
                    @if(Auth::user()->role!='Receipt')
                        <div class="col-lg-4 mb-3">
                            <div>
                                <label>company<span class="text-danger">*</span></label>
                                
                                <select onchange="loadBranch()" name="company_id" id="company_id" class="form-control" required="">
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
                                <select onchange="loadCustomer()" name="branch_id" id="branch_id"  class="form-control" required="">
                                    <option value="">Select</option>
                                    @foreach($branches as $branch)
                                     <option class="allb b_{{$branch->company_id}}" @if(old('branch_id')==$branch->id) selected="" @endif value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @else
                    <input type="hidden" name="company_id" id="company_id" value="{{Auth::user()->company_id}}">
                    <input type="hidden" name="branch_id" id="branch_id" value="{{Auth::user()->branch_id}}">
                    @endif
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Customer<span class="text-danger">*</span></label>
                             <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Select Customer" required onclick="selectCustomer();">
                             <input type="hidden" name="customer_id" id="customer_id">
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Receipt Date<span class="text-danger">*</span></label>
                            <input type="hidden" name="editid" id="editid">
                            <input value="{{date('Y-m-d')}}" class="form-control" type="date" name="receipt_date" id="receipt_date" readonly="">
                        </div>
                    </div> 

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Receipt Amount<span class="text-danger">*</span></label>
                            <input min="1" value="{{ old('receipt_amount') }}" class="form-control" type="number" step="0.005" name="receipt_amount" id="receipt_amount" required="">
                        </div>
                    </div> 
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Payment Type<span class="text-danger">*</span></label>
                            <select class="form-control" name="paymenttype_id" id="paymenttype_id" required="">
                                <option value="">Select</option>
                                @foreach($paymenttypes as $paymenttype)
                                 <option @if(old('paymenttype_id')==$paymenttype->id) selected="" @endif value="{{$paymenttype->id}}">{{$paymenttype->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>   

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Payee Reference Number</label>
                            <input  class="form-control" type="text" name="payment_reference_no" id="payment_reference_no" >
                        </div>
                         @error('payment_reference_no')
                            <div class="text-danger">
                                This Payment Reference Number is already there with us
                            </div>
                            @enderror
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Payee Bank</label>
                            <input value="{{ old('payment_bank') }}" class="form-control" type="text" name="payment_bank" id="payment_bank" >
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Payee Collect Date<span class="text-danger">*</span></label>
                            <input @if(old('payment_collect_date')) value="{{ old('payment_collect_date') }}" @else value="{{date('Y-m-d')}}" @endif class="form-control" type="date" name="payment_collect_date" id="payment_collect_date">
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Payee Reference File</label>
                            <input  class="form-control" type="file" name="payment_reference_file" id="payment_reference_file">
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Particulars<span class="text-danger">*</span></label>
                            <textarea class="form-control"  name="particulars" id="particulars" required="">{{ old('particulars') }}</textarea>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Notes</label>
                            <textarea class="form-control"  name="notes" id="notes">{{ old('note') }}</textarea>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Save</button>
                        <a href="{{url('/receipt')}}" class="btn btn-secondary waves-effect waves-light resetbtn mt-2">Cancel</a>
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-312">
        <div class="card">
            <div class="card-body">
                <h4 class="text-secondary">Customer Receipt Info</h4>
                <div class="row">
                    <div class="col-sm-12 pt-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Total</th>
                                    <th>New</th>
                                    <th>Authorized</th>
                                    <th>Bounce</th>
                                    <th>On Hold</th>
                                    <th>In Transit</th>
                                    <th>PDC</th>
                                    <th>Posted</th>
                                    <th>Reconciled</th>
                                    <th>Last Receipt Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Count</td>
                                    <td id="receipt_count"></td>
                                    <td id="new_count"></td>
                                    <td id="authorized_count"></td>
                                    <td id="bounce_count"></td>
                                    <td id="onhold_count"></td>
                                    <td id="transit_count"></td>
                                    <td id="pdc_count"></td>
                                    <td id="posted_count"></td>
                                    <td id="reconciled_count"></td>
                                    <td rowspan="2" id="receipt_date_last"></td>
                                </tr>
                                <tr>
                                    <td>Amount</td>
                                    <td id="receipt_amount_total"></td>
                                    <td id="new_amount"></td>
                                    <td id="authorized_amount"></td>
                                    <td id="bounce_amount"></td>
                                    <td id="onhold_amount"></td>
                                    <td id="transit_amount"></td>
                                    <td id="pdc_amount"></td>
                                    <td id="posted_amount"></td>
                                    <td id="reconciled_amount"></td>
                                    
                                </tr>
                            </tbody>
                        </table>
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
                            <th>Status</th>
                            <th>View</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>           
                    <tbody>
                        <?php $x=1; ?>
                        @foreach ($indexes as $index)

                        <tr class="{{$index->rvstage->color_code}}">
                            <td>{{$x++}}</td>
                            <td>{{$index->company->name}}</td>
                            <td>{{$index->branch->name}}</td>
                            <td>{{$index->customer->code}}</td>
                            <td>{{$index->customer->name}}</td>
                            <td>{{$index->receipt_no}}</td>
                            <td>{{date('d-m-Y',strtotime($index->receipt_date))}}</td>
                            <td>{{$index->receipt_amount}} {{$index->company->country->currency_unit}}</td>
                            <td>{{$index->paymenttype->name}}</td>
                            <td>{{$index->payment_reference_no}}</td>
                            <td>{{$index->particulars}}</td>
                            <td>{{$index->rvstage->name}}</td>
                            <td>
                                <a href="{{url('/receiptview')}}/{{$index->id}}" class="btn btn-outline-info waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-eye"></i></a>
                            </td>
                            <td>
                                <a href="#" data-id="{{$index->id}}" data-company_id="{{$index->company_id}}" data-branch_id="{{$index->branch_id}}" data-customer_id="{{$index->customer_id}}" data-receipt_no="{{$index->receipt_no}}" data-receipt_date="{{$index->receipt_date}}" data-receipt_amount="{{$index->receipt_amount}}" data-paymenttype_id="{{$index->paymenttype_id}}" data-payment_reference_no="{{$index->payment_reference_no}}" data-particulars="{{$index->particulars}}" data-payment_bank="{{$index->payment_bank}}" data-payment_collect_date="{{$index->payment_collect_date}}" class="btn btn-outline-secondary waves-effect waves-light btn-sm font-size-18 editbtn"><i class="mdi mdi-pencil"></i></a>
                            </td>
                            <td>
                                <a onclick="return confirm('Are you sure?')" href="{{url('/receipt')}}/{{$index->id}}/delete" class="btn btn-outline-danger waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-trash-can-outline"></i></a>
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

 <!--  Extra Large modal example -->
<div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myExtraLargeModalLabel">Select Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="customerinfo">
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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

                $('#pdc_count').html(data.pdc_count);
                $('#bounce_count').html(data.bounce_count);
                $('#onhold_count').html(data.onhold_count);
                $('#transit_count').html(data.transit_count);

                $('#receipt_amount_total').html(data.receipt_amount);
                $('#new_amount').html(data.new_amount);
                $('#authorized_amount').html(data.authorized_amount);
                $('#posted_amount').html(data.posted_amount);
                $('#reconciled_amount').html(data.reconciled_amount);

                $('#pdc_amount').html(data.pdc_amount);
                $('#bounce_amount').html(data.bounce_amount);
                $('#onhold_amount').html(data.onhold_amount);
                $('#transit_amount').html(data.transit_amount);

                $('#receipt_date_last').html(data.receipt_date);
            }
        })
    }
   
}

function selectCustomer() {
    var company_id=$('#company_id').val();
    var branch_id=$('#branch_id').val();

    if(company_id==''){
        company_id=0;
    }

    if(branch_id==''){
        branch_id=0;
    }
    $(".modal").modal('show'); 
    $.ajax({ url: "{{url('customersearch')}}/"+company_id+'/'+branch_id,
    type: "get", cache: false, dataType: 'json', 
    success: function (data) { 
        $("#customerinfo").html(data.html);
    },error:function(){ 
        // 
    }
    })
}

function searchcustomers(value){
    var value = value.toLowerCase();
  $(".search-areas table tr").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
  });
}

function customerselect(id,name,code){
    var cus_code=code+'-'+name;
    $('#customer_name').val(cus_code);
    $('#customer_id').val(id);
    getCustomerInfo();
    $(".modal").modal('hide'); 
}
</script>
@stop