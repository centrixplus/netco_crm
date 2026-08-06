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

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="text-secondary">{{$title}} List </h4>
                <div class="table-responsive">
                    <form  action="{{url('/sale/updatestatus')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}  
                        <table id="datatable-buttons" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th><input type="checkbox" name="checkall" id="checkall"></th>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Area</th>
                                    <th>Date</th>
                                    <th>Total Quantity</th>
                                    <th>Total Amount</th>
                                    <th>Priority</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>           
                            <tbody>
                                <?php $x=1; ?>
                                @foreach ($orders as $index)
                                <tr>
                                    <th><input type="checkbox" class="all" name="order[]" value="{{$index->id}}"></th>
                                    <td>{{App\Models\Sale::saleno($index->id)}}</td>
                                    <td>{{$index->customer->name}}</td>
                                    <td>{{$index->customer->area}}</td>
                                    <td>{{date('d M, Y',strtotime($index->sale_date))}}</td>
                                    <td>{{$index->no_of_qty}}</td>
                                    <td>{{$index->grand_total}}</td>
                                    <td>{{$index->priority}}</td>
                                    <td>{{date('d M, Y H:i:s',strtotime($index->created_at))}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <center>
                            <button type="submit" id="updatest" class="btn btn-primary waves-effect waves-light me-2 mt-2">Save</button>
                        </center>
                    </form>
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
$(document).ready(function(){$("#datatable-buttons").DataTable({ order: [[0, 'desc']]}),$("#datatable-buttons1").DataTable({"fixedHeader": {
      header: true,
    },lengthChange:!1,buttons:["copy","excel","pdf"], order: [[0, 'desc']],}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});


</script>
@stop