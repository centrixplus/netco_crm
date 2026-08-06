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
                <form  action="{{url('/convertedquote')}}" method="post" enctype="multipart/form-data">
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
                        @if(Auth::user()->role!='Customer')
                        <div class="col-lg-3">
                            <label>Select Customer: </label>
                            <select class="form-control select2 customer" id="customer_id" name="customer_id" onchange="getCustomerInfo()">
                                <option value="">Select Customer</option>
                                @foreach($customers as $log)
                                <option @if($log->id==$customer_id) selected="" @endif  value="{{$log->id}}">{{$log->name}} - {{$log->mobile}}  @if(Auth::user()->role=='admin') - {{$log->code}} @endif</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-lg-3 mt-3">
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
                    <table id="datatable-buttons" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                @if(Auth::user()->role!='Customer')<th>Customer</th>@endif
                                @if(Auth::user()->role=='admin')<th>Code</th>@endif
                                @if(Auth::user()->role!='Customer')<th>Location</th>@endif
                                <th>Enquiry Date</th>
                                <th>Quote Date</th>
                                <th>Sale Date</th>
                                <th>Sale No</th>
                                <th>Stage</th>
                                <th>Created at</th>
                                <th>View</th>
                                <th>Quote</th>
                                <th>SO</th>
                        </thead>           
                        <tbody>
                            <?php $x=1; ?>
                            @foreach ($indexes as $index)
                            <tr>
                                <td>{{$index->id}}</td>
                                @if(Auth::user()->role!='Customer')<td>{{$index->customer->name}}</td>@endif
                                @if(Auth::user()->role=='admin')<td>{{$index->customer->code}}</td>@endif
                                @if(Auth::user()->role!='Customer')<td>@if($index->customer->location){{$index->customer->location->name}}@endif</td>@endif
                                <td>{{date('d M, Y',strtotime($index->enquiry_date))}}</td>
                                <td>{{date('d M, Y H:i:s',strtotime($index->quote_date))}}</td>
                                <td>{{date('d M, Y',strtotime($index->sale_date))}}</td>
                                <td>{{App\Models\Sale::saleno($index->sale_id)}}</td>
                                <td>{{$index->stage->name}}</td>
                                <td>{{date('d M, Y H:i:s',strtotime($index->created_at))}}</td>
                                <td><a href="{{url('/showenquiryrequest')}}/{{$index->id}}/" class="btn btn-outline-warning waves-effect waves-light btn-sm"><i class="mdi mdi-eye"></i></a></td>
                                <td><a href="{{url('/quote')}}/{{$index->id}}" class="btn btn-outline-info waves-effect waves-light btn-sm"><i class="mdi mdi-file"></i></a></td>
                                <td><a href="{{url('/showsales')}}/{{$index->sale_id}}/" class="btn btn-outline-success waves-effect waves-light btn-sm">SO</a></td>
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

<script type="text/javascript">
    $('.updatebtn').click(function() {
        var id = $(this).data("id"); $("#enquiry_id").val(id);
        loadSaleList(id);
    });

    function showFileUpload() {
        var discount_id=$('#discount_id').val();
        if(discount_id=='2'){
            $('#manualupload').show();
            $('#pricefile').prop('required',true);
        }
        else{
            $('#manualupload').hide();
            $('#pricefile').prop('required',false);
        }
    }

    function loadSaleList(id) {

        $.ajax({ url: "{{url('/loadEnqlist')}}"+'/'+id,
                type: "get", cache: false, dataType: 'json',
                success: function (data) {
                    $('#quoteView').html(data.enqlist);  
                    $("#datatable-buttons1").DataTable({"fixedHeader": {
                      header: true,
                    },lengthChange:!1,buttons:["copy","excel","pdf"], order: [[0, 'desc']],}).buttons().container().appendTo("#datatable-buttons1_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm");
                }
            })
    }
</script>
@stop