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
    <div class="col-lg-12">
        <div class="card" style="padding: 5px;">
            <div class="card-body">
                <div class="invoice-title">
                    <div class="row">
                        <div class="col-sm-3">
                            <img src="{{url('/')}}/{{$setting->logo}}" alt="logo" height="60"/>
                        </div>
                        <div class="col-sm-6">
                            <center>
                                <h4>{{$setting->name}}</h4>
                                <p>{{$setting->address}}, {{$setting->address2}} {{$setting->city}} - {{$setting->pincode}}</p>
                                <p>Mobile: {{$setting->mobile}}, {{$setting->mobile2}}</p>
                            </center>
                        </div>
                        <div class="col-sm-3">
                            <h5 class="float-end font-size-16">Order # {{$sale->id}}</h5><br>
                            
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-6">
                        <address>
                            <strong>Billed To:</strong><br>
                            {{$sale->customer->name}}<br>
                            @if($sale->customer->note!=''){{$sale->customer->note}}<br>@endif
                            {{$sale->customer->mobile}}<br>
                        </address>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <address>
                            <strong>Order Date:</strong><br>
                            {{date('d-m-Y',strtotime($sale->sale_date))}}
                        </address>
                    </div>
                </div>
                <div class="py-2">
                    <h3 class="font-size-15 fw-bold">Order summary</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 70px;">No.</th>
                                <th>Product</th>
                                <th class="text-end">Rate</th>
                                <th class="text-end">Quantity</th>
                                <th class="text-end">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $No=1; ?>
                            @foreach($sale_lines as $log)
                            <tr>
                                <td>{{$No++}}</td>
                                <td>{{$log->product->name}}</td>
                                <td class="text-end">{{$log->rate}}</td>
                                <td class="text-end">{{$log->quantity}}</td>
                                <td class="text-end">{{$log->price}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" rowspan="3">Amount in Words:<br>
                                    {{App\Sale::getAmountInwords($sale->grand_total)}}.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1"  class="text-end"> No.of.Qty:</td>
                                <td colspan="1"  class="text-end">{{$sale->no_of_qty}}</td>
                            </tr>
                            <tr>
                                <td colspan="1"  class="text-end"> Grand Total:</td>
                                <td colspan="1"  class="text-end">{{$sale->grand_total}} {{$setting->currency}}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="d-print-none">
                    <div class="float-end">
                        <a href="javascript:window.print()" type="button" class="btn btn-success waves-effect waves-light me-1"><i class="fa fa-print"></i></a>
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
<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>
<script src="{{asset('/assets')}}/js/pages/form-advanced.init.js"></script>
<script>
var pro="<?php echo $process; ?>";
if(pro=='print'){
    window.print();
}
</script>
@stop