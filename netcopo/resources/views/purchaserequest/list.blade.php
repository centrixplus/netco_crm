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
                <form  action="{{url('/purchaserequest')}}" method="post" enctype="multipart/form-data">
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

                        <div class="col-lg-2">
                            <label>Priority:</label>
                            <select class="form-control" name="priority" id="priority">
                                <option value="">Select</option>
                                <option @if($priority=='Low') selected="" @endif value="Low">Low</option>
                                <option @if($priority=='Medium') selected="" @endif value="Medium">Medium</option>
                                <option @if($priority=='High') selected="" @endif value="High">High</option>
                            </select>
                        </div> 

                        <div class="col-lg-2">
                            <label>Request Type:</label>
                            <select class="form-control" name="requestype_id" id="requestype_id">
                                <option value="">Select</option>
                                @foreach($requesttypes as $rt)
                                    <option @if($requesttype_id==$rt->id) selected="" @endif value="{{$rt->id}}">{{$rt->name}}</option>
                                @endforeach
                            </select>
                        </div> 
                        
                        <div class="col-lg-2">
                            <label>Satus: </label>
                            <select class="form-control" id="status_id" name="status_id">
                                <option value="">Select Status</option>
                                @foreach($statuses as $log)
                                <option @if($log->id==$status_id) selected="" @endif  value="{{$log->id}}">{{$log->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-lg-2">
                            <label>User:</label>
                            <select class="form-control" name="created_by" id="created_by">
                                <option value="">Select</option>
                                @foreach($users as $user)
                                    <option @if($created_by==$user->id) selected="" @endif value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div> 

                        @if(Auth::user()->role!='Customer')
                        <div class="col-lg-3 mt-3">
                            <label>Select Customer: </label>
                            <select class="form-control select2 customer" id="customer_id" name="customer_id">
                                <option value="">Select Customer</option>
                                @foreach($customers as $log)
                                <option @if($log->id==$customer_id) selected="" @endif  value="{{$log->id}}">{{$log->name}} - {{$log->mobile}}  @if(Auth::user()->role=='admin') - {{$log->code}} @endif</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="col-lg-3 mt-3">
                            <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Search</button>
                            <a href="{{url('/purchaserequest')}}" class="btn btn-info waves-effect waves-light me-2 mt-2">Refresh</a>
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
                <table id="datatable-buttons" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>PI No</th>
                            @if(Auth::user()->role!='Customer')<th>Customer</th>@endif
                            <th>Code</th>
                            <th>PR Date</th>
                            <th>Request Type</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Created at</th>
                            <th>Created by</th>
                            <th>View</th>
                            <th>Edit</th>
                            @if(Auth::user()->role=='admin' || Auth::user()->role=='PR')
                            <th>Action</th>
                            @endif
                            <th>Delete</th>
                    </thead>           
                    <tbody>
                        {!!$prlist_table!!}
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
        },lengthChange:!1,buttons:["copy","excel","pdf"],}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm");

    
});

</script>
@else
   <script>
    $(document).ready(function(){

       $("#datatable-buttons").DataTable({order: [[0, 'desc']]});
    });
   </script>
@endif

<script type="text/javascript">
$('.updatebtn').click(function() {
    var id = $(this).data("id"); $("#invoice_id").val(id);
});
</script>
@stop