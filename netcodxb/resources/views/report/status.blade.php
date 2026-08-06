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
                <form  action="{{url('/report/status')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}  
                    <div class="row">
                        <div class="col-lg-3">
                            <div>
                                <label>From Date</label>
                                <input class="form-control" type="date" value="{{$fromdate}}" name="fromdate" id="fromdate">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div>
                                <label>To Date</label>
                                <input class="form-control" type="date" value="{{$todate}}" name="todate" id="todate">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <label>Select Status: </label>
                            <select class="form-control" id="order_status" name="order_status">
                                <option value="">Select Status</option>
                                @foreach($statuses as $sts)
                                <option @if($sts->name==$status) selected="" @endif  value="{{$sts->name}}">{{$sts->name}}</option>
                                @endforeach
                            </select>
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
                @foreach($statuses as $sts) <span class="{{$sts->color}} btn btn-rounded waves-effect waves-light m-2 p-2"> {{$sts->name}} - {{$indexes->where('status',$sts->name)->count()}} </span> @endforeach
            </div>
        </div>
    </div>
</div>
<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h4 class="text-secondary">{{$title}}  </h4>
            <div class="table-responsive">
                <table id="datatable-buttons" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Code</th>
                            <th>Location</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Reason</th>
                            <th>Document No</th>
                            <th>AWB</th>
                            <th>No.of.Box</th>
                            <th>Updated By</th>
                            <th>Created at</th>
                        </tr>
                    </thead>           
                    <tbody>
                        <?php $x=1; ?>
                        @foreach ($indexes as $index)

                        <tr class="{{$index->orderstatus->color}}">
                            <td>{{$x++}}</td>
                            <td>@if($index->sale->sale_no!='') {{$index->sale->sale_no}} @else {{App\Models\Sale::saleno($index->slae_id)}} @endif</td>
                            <td>{{$index->sale->customer->name}}</td>
                            <td>{{$index->sale->customer->code}}</td>
                            <td>@if($index->sale->customer->location){{$index->sale->customer->location->name}}@endif</td>
                            <td>{{date('d-m-Y',strtotime($index->sale->sale_date))}}</td>
                            <td>{{$index->status}}</td>
                            <td>{{$index->reason}}</td>
                            <td>{{$index->document_no}}</td>
                            <td>{{$index->awb_no}}</td>
                            <td>{{$index->box}}</td>
                            <td>{{App\Models\User::find($index->created_by)->name}}</td>
                            <td>{{date('d-m-Y H:i:s',strtotime($index->created_at))}}<br>
                                <!-- {{$index->created_at->diffForHumans()}} -->
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
<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>
<script src="{{asset('/assets')}}/js/pages/form-advanced.init.js"></script>
<script>
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});


</script>
@stop