@extends('layouts.master')
@section('title',$title)
@section('StyleContent')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('PageContent')
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-16">{{$title}}</h4>
        <div class="page-title-right">
            <a href="{{url('/container')}}" class="btn btn-secondary btn-sm">Back to List</a>
        </div>
    </div>
</div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{url('/container/report/extra')}}" method="post">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-lg-3">
                            <label>From Date</label>
                            <input class="form-control" type="date" value="{{$fromdate}}" name="fromdate">
                        </div>
                        <div class="col-lg-3">
                            <label>To Date</label>
                            <input class="form-control" type="date" value="{{$todate}}" name="todate">
                        </div>
                        <div class="col-lg-3">
                            <label>Vendor</label>
                            <select class="form-control select2" name="vendor_id">
                                <option value="">All Vendors</option>
                                @foreach($vendors as $v)
                                    <option value="{{$v->id}}" {{$vendor_id==$v->id?'selected':''}}>{{$v->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Container</label>
                            <select class="form-control select2" name="container_id">
                                <option value="">All Containers</option>
                                @foreach($containers as $c)
                                    <option value="{{$c->id}}" {{$container_id==$c->id?'selected':''}}>{{$c->container_no}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 mt-3">
                            <label>Box No (CtnNo)</label>
                            <input class="form-control" type="text" name="box_no" value="{{$box_no}}" placeholder="Box No">
                        </div>
                        <div class="col-lg-3 mt-3">
                            <label>Part No</label>
                            <input class="form-control" type="text" name="part_no" value="{{$part_no}}" placeholder="Part No">
                        </div>
                        <div class="col-lg-3 mt-3">
                            <label>Brand</label>
                            <input class="form-control" type="text" name="brand" value="{{$brand}}" placeholder="Brand">
                        </div>
                        <div class="col-lg-3 mt-3 align-self-end">
                            <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Search</button>
                            <button type="button" class="btn btn-outline-info waves-effect waves-light mt-2">Lines - {{$indexes->count()}}</button>
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
            <div class="table-responsive">
                <table id="datatable-buttons" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Container No</th>
                            <th>Vendor</th>
                            <th>Box (CtnNo)</th>
                            <th>Part No</th>
                            <th>Description</th>
                            <th>Brand</th>
                            <th>Location</th>
                            <th>Received Qty</th>
                            <th>Source</th>
                            <th>Note</th>
                            <th>Received By</th>
                            <th>Received At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $x=1; ?>
                        @foreach ($indexes as $index)
                        <tr>
                            <td>{{$x++}}</td>
                            <td>{{optional($index->container)->container_no}}</td>
                            <td>{{optional(optional($index->container)->vendor)->name}}</td>
                            <td>{{$index->box_no}}</td>
                            <td>{{$index->part_no}}</td>
                            <td>{{$index->description}}</td>
                            <td>{{$index->brand}}</td>
                            <td>{{$index->location}}</td>
                            <td>{{$index->received_qty}}</td>
                            <td>{{$index->source}}</td>
                            <td>{{$index->note}}</td>
                            <td>{{optional($index->creator)->name}}</td>
                            <td>{{ date('d-m-Y H:i:s', strtotime($index->created_at)) }}</td>
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
<script>
$(document).ready(function(){$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf","print"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm"),$(".select2").select2({width:'100%'})});
</script>
@stop
