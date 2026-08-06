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
            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#customer" role="tab">
                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                        <span class="d-none d-sm-block">Customer Details</span> 
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#sales" role="tab">
                        <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                        <span class="d-none d-sm-block">Sales</span> 
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#payments" role="tab">
                        <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                        <span class="d-none d-sm-block">Payments</span>   
                    </a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content p-3 text-muted">
                <div class="tab-pane active" id="customer" role="tabpanel">
                    <div class="row">
                        <div class="col-xl-4"></div>
                        <div class="col-xl-4">
                            <center><h4 class="card-title mb-4">{{$customer->name}} Info</h4></center>
                            <div class="text-center">
                                <p class="font-16 text-muted mb-2"></p>
                                <h5><a href="javascript: void(0);" class="text-dark">Customer ID - <span class="text-muted font-16">{{$customer->id}}</span> </a></h5>
                                <p class="text-muted">{{$customer->note}}.</p>
                                Mobile: <a href="javascript: void(0);" class="text-primary font-16">{{$customer->mobile}}</a>
                                <br> Email: {{$customer->email}}
                            </div>
                            <div class="row mt-4">
                                <div class="col-4">
                                    <div class="social-source text-center mt-3">
                                        <h5 class="font-size-15">Payment</h5>
                                        <p class="text-muted mb-0">{{$customer->sale->sum('grand_total')}}</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="social-source text-center mt-3">
                                        <h5 class="font-size-15">Received</h5>
                                        <p class="text-muted mb-0">{{$customer->payment->sum('amount')}}</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="social-source text-center mt-3">
                                        <h5 class="font-size-15">Pending</h5>
                                        <p class="text-muted mb-0">{{$customer->sale->sum('grand_total') - $customer->payment->sum('amount')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="sales" role="tabpanel">
                    <center><h4 class="card-title mb-4">{{$customer->name}} Sales Info</h4></center>
                    {!!$saletable!!}
                </div>
                <div class="tab-pane" id="payments" role="tabpanel">
                    <h5>Add New Payment</h5>
                    <div class="row">
                        <div class="col-sm-12 pt-3">
                            <form  action="{{url('/payment/store')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}  
                            <div class="row">
                                <div class="col-lg-2 mb-3">
                                    <div>
                                        <label>Amount <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="amount" id="amount" required="">
                                        <input type="hidden" value="{{$customer->id}}" name="customer_id" id="customer_id">
                                    </div>
                                </div>
                                <div class="col-lg-2 mb-3">
                                    <div>
                                        <label>Payment Type</label>
                                        <select class="form-control" name="payment_type" id="payment_type">
                                            <option value="Cash">Cash</option>
                                            <option value="Online">Online</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 mb-3">
                                    <div>
                                        <label>Reference No</label>
                                        <input class="form-control" type="text" name="ref_no" id="ref_no">
                                    </div>
                                </div>
                                <div class="col-lg-2 mb-3">
                                    <div>
                                        <label>Payment Date</label>
                                        <input class="form-control" type="date" value="{{date('Y-m-d')}}" name="payment_date" id="payment_date">
                                    </div>
                                </div>
                                <div class="col-lg-2 mb-3">
                                    <div>
                                        <label>Note</label>
                                        <textarea class="form-control" type="text" name="note" id="note"></textarea>
                                    </div>
                                </div>           
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <center><h4 class="card-title mb-4">{{$customer->name}} Payment Info</h4></center>
                    {!!$paymenttable!!}
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
<script>
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-payment").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-payment_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

$('.editbtn').click(function() {
    var id = $(this).data("id"); $("#editid").val(id);
    var name = $(this).data("name"); $("#name").val(name);
    var mobile = $(this).data("mobile"); $("#mobile").val(mobile);
    var email = $(this).data("email"); $("#email").val(email);
    var note = $(this).data("note"); $("#note").val(note);
    $('#createbutton').html('Update');
});
</script>
@stop