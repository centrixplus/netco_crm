@extends('layouts.master')
@section('title','Enquiry')
@section('StyleContent')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('PageContent')



<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
        <h4 class="card-title">Upload SO Details </h4>
             <form action="{{route('enquirytosostore')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="expirydateInput">Upload File</label>
                            <input type="file" class="form-control" name="sofile" id="sofile">
                            <input type="hidden" name="enquiry_id" id="enquiry_id" value="{{$enquiry->id}}">
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label>Select Priority: </label>
                        <select class="form-control customer" id="priority" name="priority">
                            <option value="Next Day Delivery">Next Day Delivery</option>
                            <option value="Priority">Priority </option>
                        </select>
                    </div>
                    <div class="col-lg-4 mb-3">
                            <label>Order Note: </label>
                            <textarea name="comment" id="comment" class="form-control" placeholder="Enter Order Note"></textarea>
                        </div>  
                    <div class="col-sm-6 mt-4">
                        <div >
                            <button type="submit" class="btn btn-info"> <i class="mdi mdi-cart me-1"></i> Proceed to Cart </button>
                        </div>
                    </div> <!-- end col -->
                </div>
                
            </form>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="card">
        <div class="card-body">
        <h4 class="card-title">Enquiry List</h4>
            <table id="datatable-buttons" class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>id</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>SO Quantity</th>
                    </tr>
                </thead>           
                <tbody>
                    <?php $x=1; ?>
                    @foreach ($enquirylines as $index)
                    <tr>
                        <td>{{$x++}}</td>
                        <td>{{$index->id}}</td>
                        <td>{{$index->product->name}}</td>
                        <td>{{$index->quantity}}</td>
                        <td>{{$index->so_quantity}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="col-md-6">
    <div class="card">
        <div class="card-body">
        <h4 class="card-title">Quotation to SO Request List</h4>
            <table id="datatable" class="table table-bordered table-responsive">
                <thead>
                    
                    <tr>
                        <th>S.No</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>SO Quantity</th>
                    </tr>
                </thead>           
                <tbody>
                    <?php $x=1; ?>
                    @foreach ($so_enquirylines as $index)

                    <tr>
                        <td>{{$x++}}</td>
                        <td>{{$index->product->name}}</td>
                        <td>{{$index->quantity}}</td>
                        <td>{{$index->so_quantity}}</td>
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

$(document).ready(function(){

    $("#datatable-buttons").DataTable({"fixedHeader": {
          header: true,
        },lengthChange:!1,buttons:["copy","excel","pdf"], order: [[0, 'desc']],}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm");

    $("#datatable").DataTable({order: [[0, 'desc']]});
});
</script>
@endsection