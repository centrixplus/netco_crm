@extends('layouts.master')
@section('title',$index->receipt_no)
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
        <h4 class="mb-sm-0 font-size-16">{{$title}} <a onclick="printPage()" class="btn btn-success waves-effect waves-light me-1"><i class="fa fa-print"></i></a></h4>
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Details</a></li>
                    <li class="breadcrumb-item active">{{$title}}</li>
            </ol>
        </div>

    </div>
</div>
</div>
<?php $authorize= App\Models\Receiptlog::where('rvstage_id','2')->where('receipt_id',$index->id)->first(); ?>
<div class="row" id="printdiv">
    <div class="col-lg-2"></div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body" style="padding: 20px;">
                <div class="row">
                    <div class="col-sm-8">
                        <h5>{{$index->branch->company->name}}</h5>
                        <h6>{{$index->branch->company->quote}}</h6>
                    </div>
                    <div class="col-sm-4 text-sm-end"><img src="{{ asset($index->branch->logo) }}" style="width: 100px;" alt="logo"></div>
                </div>
                <center><h3>سند القبض<br><u>Receipt Voucher</u></h3></center>
                <div class="invoice-title">
                    <h4 class="float-end font-size-16"><a style="color: #495057 !important;" href="{{url('/receiptprint')}}/{{$index->id}}"> # {{$index->receipt_no}} </a></h4>
                    <div class="mb-4">
                        <address>
                            <strong>Date:</strong>
                            {{date('M d, Y',strtotime($index->created_at))}}
                        </address>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-6">
                        <address>
                            {{$index->branch->name}}<br>
                            <i class="bx bx-map"></i> {{$index->branch->address}}<br>
                            <i class="bx bx-phone-incoming"></i> {{$index->branch->phone}}<br>
                            <i class="bx bx-mail-send"></i> {{$index->branch->email}}<br>
                        </address>
                    </div>
         <!--            <div class="col-sm-4">
                        
                    </div> -->
                    <!-- <div class="col-sm-5 text-sm-end">
                        
                    </div> -->
                </div>
                <div class="row">
                    <div class="col-sm-6 mt-3">
                        <address>
                            <strong>Customer Name:</strong><br>
                            @if($index->customer){{$index->customer->code}}-{{$index->customer->name}}<br>@endif
                           
                        </address>
                    </div>
                    <div class="col-sm-6 mt-3 text-sm-end">
                        <address>
                            <strong>Payment Info:</strong><br>
                            {{$index->paymenttype->name}}<br>
                            @if($index->payment_bank!='')
                            {{$index->payment_bank}}<br>
                            @endif
                            @if($index->payment_reference_no!='')
                            {{$index->payment_reference_no}}<br>
                            @endif
                            @if($index->paymenttype->name!='CASH')
                            {{date('M d, Y',strtotime($index->payment_collect_date))}}
                            @endif
                        </address>
                    </div>
                </div>
                <div class="py-2 mt-3">
                    <h3 class="font-size-15 fw-bold">Receipt summary</h3>
                </div>
                    <table class="table">
                        <thead>
                            <tr class="table-dark">
                                <th>Particulars</th>
                                <th style="width: 30px;" class="text-end">{{$index->company->country->currency_unit}}</th>
                                <th style="width: 30px;"  class="text-end">{{$index->company->country->currency_unit_decimal}}</th>
                            </tr>
                        </thead>
                        @php $arr = explode(".", $index->receipt_amount, 2); @endphp
                        <tbody>
                            <tr>
                                <td>{{$index->particulars}}<br><br><br></td>
                                <td class="text-end">{{$arr[0]}}</td>
                                <td class="text-end">{{$arr[1]}}</td>
                            </tr>
                            <tr>
                                <td class="border-0 text-end">
                                    <strong>Total:</strong></td>
                                <td class="border-0 text-end">{{$arr[0]}}/ -</td>
                                <td class="border-0 text-end">{{$arr[1]}}</td>
                            </tr>
                        </tbody>
                    </table><br>
                    <span class="mb-2"><strong>Total in Words: </strong>{{strtoupper($amount)}}.</span><br><br><br>
                    <div class="row mt-3">
                        <div class="col-sm-6 mt-3">
                            <address>
                                <strong>Receiver`s______________</strong><br>
                            </address>
                        </div>
                        <div class="col-sm-6 mt-3 text-sm-end">
                            <address>
                                <strong>Debit Account______________</strong><br>
                            </address>
                        </div>
                        <div class="col-sm-6">
                            <p class="mt-5">This Receipt Voucher is computer generated.</p>
                        </div>
                        <div class="col-sm-6 text-end">
                            <p class="mt-5">Created By: {{$index->user->name}} @if($authorize){{date('d M, Y H:i:s',strtotime($authorize->created_at))}}@endif</p>
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


function printPage() {
        var tableDiv = document.getElementById("printdiv").innerHTML;

             printContents = '';
             printContents += tableDiv;
             var originalContents = document.body.innerHTML;
             document.body.innerHTML = printContents;
             window.print();
             document.body.innerHTML = originalContents;
}
</script>
@stop