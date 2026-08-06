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

<div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Update Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  action="{{url('/updatestatus')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}  
                <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <input type="hidden" name="sale_id" id="sale_id">
                                <input type="hidden" name="user_role" id="user_role" value="{{Auth::user()->role}}">
                                
                            </div>
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
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
            <h4 class="text-secondary"><span id="createbutton"></span> {{$title}}</h4>
            @if(!empty(Session::get('alert')))<span class="bg-success p-1">{!!Session::get('alert')!!}</span>@endif
            <div class="row">
                <div class="col-sm-12 pt-3">
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Request ID:</label>
                                #{{$enquiry->id}}
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Name:</label>
                                {{$enquiry->customer->name}}
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Code:</label>
                                {{$enquiry->customer->code}}
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Mobile:</label>
                               {{$enquiry->customer->mobile}}
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Area:</label>
                                {{$enquiry->customer->area}}
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Address:</label>
                                {{$enquiry->customer->note}}
                            </div>
                        </div>  

                        <div class="col-lg-3 mb-3">
                            <label>Enquiry Date:</label>
                            {{date('d-m-Y',strtotime($enquiry->enquiry_date))}}
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>Enquiry Stage: </label>
                            <span>{{$enquiry->stage->name}}</span>
                        </div>         

                        <div class="col-lg-3 mb-3">
                            <label>Total No of  Quantity: </label>
                            {{$enquiry->enquirylines->sum('quantity')}}
                        </div> 

                        @if($enquiry->stage_id > 1)
                        <div class="col-lg-3 mb-3">
                            <label>Grand Total: </label>
                            {{$enquiry->enquirylines->sum('price')}}
                        </div> 
                        @endif
                        <div class="col-lg-5 mb-3">
                            <label>Created Date & Time: </label>
                            {{date('d-m-Y H:i:s',strtotime($enquiry->created_at))}}
                        </div>

                
                        <h5>Enquiry List </h5>
                        <table id="datatable-buttons" class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    @if(Auth::user()->role=='admin')<th>id</th>@endif
                                    <th>Product Name</th>
                                    <th>Description</th>
                                    <th>Quantity Requested</th>
                                    @if(Auth::user()->role!='Customer')<th>Quantity Available</th>@endif
                                    <th>SO Quantity</th>
                                    @if($enquiry->stage_id > 1)
                                        <th>Rate</th>
                                        <th>Price</th>
                                    @endif
                                </tr>
                            </thead>           
                            <tbody>
                                <?php $x=1; ?>
                                @foreach ($enquirylines as $index)

                                <tr>
                                    <td>{{$x++}}</td>
                                    @if(Auth::user()->role=='admin')<td>{{$index->id}}</td>@endif
                                    <td>{{$index->product->name}}</td>
                                    <td>{{$index->product->note}}</td>
                                    <td>{{$index->quantity}}</td>
                                    @if(Auth::user()->role!='Customer')<td>{{$index->product->productstock->qty+$index->product->productstock->t_qty}}</td>@endif
                                    <td>{{$index->so_quantity}}</td>
                                    @if($enquiry->stage_id > 1)
                                        <td>{{$index->rate}}</td>
                                        <td>{{$index->price}}</td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>

                <h5>Stage Log List</h5>

                <table id="datatable" class="table table-bordered table-responsive mt-3">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Stage</th>
                            <th>Updated Date & Time</th>
                            @if(Auth::user()->role!='Customer')<th>Updated By</th>@endif
                        </tr>
                    </thead>           
                    <tbody>
                        <?php $x=1; ?>
                        @foreach ($enquirystagelogs as $index)

                        <tr>
                            <td>{{$x++}}</td>
                            <td>{{$index->stage->name}}</td>
                            <td>{{date('d-m-Y H:i:s',strtotime($index->created_at))}}</td>
                            @if(Auth::user()->role!='Customer')<td>{{App\Models\User::find($index->created_by)->name}}</td>@endif
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