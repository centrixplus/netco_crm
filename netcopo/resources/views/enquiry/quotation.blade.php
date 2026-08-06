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

<div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myExtraLargeModalLabel">ReCreate Quotation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form  action="{{url('/createquote')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}} 
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <input type="hidden" name="enquiry_id" id="enquiry_id">
                        <label>Select Price Master<span class="text-danger">*</span></label>
                       <select id="discount_id" name="discount_id" class="form-control" onchange="showFileUpload()" required="">
                           <option value="">Select</option>
                           @foreach($discounts as $discount)
                            <option value="{{$discount->id}}">{{$discount->name}}</option>
                           @endforeach
                       </select>
                    </div>
                    <div class="col-lg-4 mb-3" id="manualupload" style="display: none;">
                        <label>Upload Price Format<span class="text-danger">*</span></label>
                        <input type="file" name="pricefile" id="pricefile" class="form-control">
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label>No.Of Days for Expiry<span class="text-danger">*</span></label>
                        <input type="number" name="no_of_days_expiry" class="form-control" id="no_of_days_expiry" min="1" required="">
                    </div>
                </div>
                <hr>
                <div class="row" id="quoteView">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
            </div>
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
                <form  action="{{url('/quotationlist')}}" method="post" enctype="multipart/form-data">
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
            {!!Session::get('alert')!!}
            <h4 class="text-secondary">{{$title}} List</h4>
            {!!$quotationtable!!}
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
                    },lengthChange:!1,buttons:["copy","excel","pdf"], order: [[0, 'asc']],}).buttons().container().appendTo("#datatable-buttons1_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm");
                }
            })
    }
</script>
@stop