@extends('layouts.master')
@section('title',$title)
@section('StyleContent')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<style>
#datatable-buttons th, #datatable-buttons td{border:1px solid #6c757d !important;}
</style>
@endsection
@section('PageContent')
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-16">{{$title}}</h4>
        <div class="page-title-right">
            <a href="{{url('/container/create')}}" class="btn btn-primary btn-sm"><i class="mdi mdi-plus"></i> Create Container</a>
        </div>
    </div>
</div>
</div>

@if(session('alert'))
<div class="alert alert-success">{!! session('alert') !!}</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{url('/container')}}" method="post">
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
                            <label>Mode of Shipment</label>
                            <select class="form-control" name="mode_of_shipment">
                                <option value="">All</option>
                                @foreach(['AIR','LAND','SEA'] as $m)
                                    <option value="{{$m}}" {{$mode==$m?'selected':''}}>{{$m}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 mt-3">
                            <label>Tag</label>
                            <select class="form-control select2" name="tag_id">
                                <option value="">All Tags</option>
                                @foreach($tags as $t)
                                    <option value="{{$t->id}}" {{$tag_id==$t->id?'selected':''}}>{{$t->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 mt-3">
                            <label>Code</label>
                            <input class="form-control" type="text" name="container_code" value="{{$container_code}}" placeholder="Code">
                        </div>
                        <div class="col-lg-3 mt-3">
                            <label>Vendor Doc No</label>
                            <input class="form-control" type="text" name="vendor_doc_no" value="{{$vendor_doc_no}}" placeholder="Vendor Doc No">
                        </div>
                        <div class="col-lg-3 mt-3">
                            <label>Shipment Doc No</label>
                            <input class="form-control" type="text" name="shipment_doc_no" value="{{$shipment_doc_no}}" placeholder="Shipment Doc No">
                        </div>
                        <div class="col-lg-3 mt-3 align-self-end">
                            <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Search</button>
                            <button type="button" class="btn btn-outline-info waves-effect waves-light mt-2">Count - {{$indexes->count()}}</button>
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
            <table id="datatable-buttons" class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Container No</th>
                        <th>Code</th>
                        <th>Vendor</th>
                        <th>Vendor Doc No</th>
                        <th>Mode</th>
                        <th>Shipment Doc No</th>
                        <th>Received Date</th>
                        <th>Lines</th>
                        <th>Completion</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $x=1; ?>
                    @foreach ($indexes as $index)
                    <tr>
                        <td>{{$x++}}</td>
                        <td>{{$index->container_no}}</td>
                        <td>{{$index->container_code}}</td>
                        <td>{{optional($index->vendor)->name}}</td>
                        <td>{{$index->vendor_doc_no}}</td>
                        <td>{{$index->mode_of_shipment}}</td>
                        <td>{{$index->shipment_doc_no}}</td>
                        <td>{{ $index->received_date ? date('d-m-Y', strtotime($index->received_date)) : '-' }}</td>
                        <td>{{$index->lines_count}}</td>
                        @php
                            $st = $stats[$index->id] ?? null;
                            $ordered = $st ? (float)$st->ordered : 0;
                            $capped  = $st ? (float)$st->capped : 0;
                            $pct = $ordered > 0 ? round($capped / $ordered * 100, 2) : 0;
                            $pctColor = $pct >= 100 ? 'success' : 'warning';
                        @endphp
                        <td><span class="badge bg-{{$pctColor}}">{{$pct}}%</span></td>
                        <td><span class="badge bg-{{ ['New'=>'danger','In Progress'=>'warning','Completed'=>'success'][$index->status] ?? 'secondary' }}">{{$index->status}}</span></td>
                        <td>
                            <a href="{{url('/container')}}/{{$index->id}}/cartons" class="btn btn-outline-primary waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-eye"></i></a>
                            <a href="{{url('/container')}}/{{$index->id}}/edit" class="btn btn-outline-secondary waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-pencil"></i></a>
                            @if(auth()->user()->role == 'admin')
                            <a onclick="return confirm('Are you sure?')" href="{{url('/container')}}/{{$index->id}}/delete" class="btn btn-outline-danger waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-trash-can-outline"></i></a>
                            @endif
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
<script>
$(document).ready(function(){$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm"),$(".select2").select2({width:'100%'})});
</script>
@stop
