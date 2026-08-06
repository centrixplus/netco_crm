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
                <form  action="{{url('/quotelist')}}" method="post" enctype="multipart/form-data">
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
                        <div class="col-lg-3">
                            <label>Select Customer: </label>
                            <select class="form-control select2 customer" id="customer_id" name="customer_id" onchange="getCustomerInfo()">
                                <option value="">Select Customer</option>
                                @foreach($customers as $log)
                                <option @if($log->id==$customer_id) selected="" @endif  value="{{$log->id}}">{{$log->name}} - {{$log->mobile}}  @if(Auth::user()->role=='admin') - {{$log->code}} @endif</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2">
                            <label>Select Brand: </label>
                            <select class="form-control" id="brand_id" name="brand_id">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                <option @if($brand->id==$brand_id) selected="" @endif  value="{{$brand->id}}">{{$brand->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="cardnameInput">Currency</label>
                                <select class="form-control" id="currency" name="currency" required>
                                    @foreach($currencies as $crr)
                                    <option @if($currency_id==$crr->id) selected="" @endif value="{{$crr->id}}">{{$crr->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if(Auth::user()->role=='admin')
                        <div class="col-lg-2">
                            <label>Select User: </label>
                            <select class="form-control" id="user_id" name="user_id">
                                <option value="">Select User</option>
                                @foreach($users as $log)
                                <option @if($log->id==$user_id) selected="" @endif  value="{{$log->id}}">{{$log->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="col-lg-8 mt-3">
                            <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Search</button>
                            <a href="{{url('/quote/list')}}" class="btn btn-outline-warning waves-effect waves-light me-2 mt-2">Clear Filter</a>
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
            <h4 class="text-secondary">{{$title}} List 
            <div class="table-responsive">
    <table id="datatable-buttons" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
        <thead>
            <tr>
                <th>Quote ID</th>
                <th>Customer</th>
                <th>Code</th>
                <th>Date</th>
                <th>Brand</th>
                <th>User</th>
                <th>Amount</th>
                <th>Price Level</th>
                <th>Currency</th>
                <th>Note</th>
                <th>View</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>           
        <tbody>
            <?php $x=1; ?>
            @foreach ($indexes as $index)
            <tr>
                <td>@if($index->quote_no!='') {{$index->quote_no}} @endif</td>
                <td>@if($index->customer){{$index->customer->name}} @endif</td>
                <td>@if($index->customer){{$index->customer->code}} @endif</td>
                <td>{{date('d-m-Y',strtotime($index->quote_date))}}</td>
                <td>{{$index->brand->name}}</td>
                <td>{{$index->user->name}}</td>
                <td>{{$index->quoteline->sum('price')}}</td>
                <td>{{$index->price_level}}</td>
                <td>{{$index->currency->name}}</td>
                <td>{{$index->note}}</td>
                <td><a href="{{url('/view/quote')}}/{{$index->id}}/" class="btn btn-outline-info waves-effect waves-light btn-sm"><i class="mdi mdi-eye"></i></a></td>
                <td><a href="{{url('/edit/quote')}}/{{$index->id}}/" class="btn btn-outline-warning waves-effect waves-light btn-sm"><i class="mdi mdi-pencil"></i></a></td>
                <td>
                    <a href="{{url('/delete/quote')}}/{{$index->id}}" onclick="return confirm('Are you sure?')" class="btn btn-outline-danger waves-effect waves-light btn-sm"><i class="mdi mdi-trash-can-outline"></i></a>
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
@if(Auth::user()->role=='admin')
<script>

$(document).ready(function(){

    $("#datatable-buttons").DataTable({"fixedHeader": {
          header: true,
        },lengthChange:!1,buttons:["copy","excel","pdf"], order: [[0, 'desc']],}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm");

    
});

</script>
@else
   <script>
    $(document).ready(function(){

       $("#datatable-buttons").DataTable({order: [[0, 'desc']]});
    });
   </script>
@endif

<script>

</script>
@stop