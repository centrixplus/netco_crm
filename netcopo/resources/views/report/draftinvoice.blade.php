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
                <form  action="{{url('/report/draftinvoice')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}  
                    <div class="row">

                        <div class="col-lg-3">
                            <label>Select Brand: </label>
                            <select class="form-control" id="brand_id" name="brand_id">
                                <option value="">Select Brand</option>
                                @foreach($brands as $log)
                                <option @if($log->id==$brand_id) selected="" @endif  value="{{$log->id}}">{{$log->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label>Enter Product: </label>
                            <input type="text" name="product_id" id="product_id" class="form-control" value="{{$product_id}}">
                        </div>
                        <div class="col-lg-5 mt-3">
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
            <table id="datatable-buttons" class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Date</th>
                        <th>Customer Name</th>
                        <th>Customer Code</th>
                        <th>Product Name</th>
                        <th>Description</th>
                        <th>Rate</th>
                        <th>Target Rate</th>
                        <th>Quantity Requested</th>
                        <th>So Quantity</th>
                        <th>PI Amount</th>
                        <th>SO Amount</th>
                        <th>Invoiced</th>
                        <th>Salesman</th>
                        <th>Ageing</th>
                    </tr>
                </thead>           
                <tbody>
                    <?php $x=1; ?>
                    @foreach ($indexes as $index)
                    @php
                    $datetime1 = strtotime(date('Y-m-d',strtotime($index->created_at))); // convert to timestamps
                    $datetime2 = strtotime(date('Y-m-d')); // convert to timestamps
                    $days = (int)(($datetime2 - $datetime1)/86400);
                    @endphp
                    <tr>
                        <td>{{$x++}}</td>
                        <td>{{date('d-m-Y',strtotime($index->created_at))}}</td>
                        <td>{{$index->customer->name}}</td>
                        <td>{{$index->customer->code}}</td>
                        <td>{{$index->product->name}}</td>
                        <td>{{$index->product->note}}</td>
                        <td>{{$index->rsp}}</td>
                        <td>{{$index->target_price}}</td>
                        <td>{{$index->quantity_po}}</td>
                        <td>{{$index->quantity_so}}</td>
                        <td>{{$index->quantity_po*$index->target_price}}</td>
                        <td>{{$index->quantity_so*$index->target_price}}</td>
                        <td>@if($index->is_invoiced==0) No @else Yes @endif</td>
                        <td>{{App\Models\User::find($index->created_by)->name}}</td>
                        <td>{{$days}}</td>
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
<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>
<script src="{{asset('/assets')}}/js/pages/form-advanced.init.js"></script>
<script>
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});
</script>
@stop