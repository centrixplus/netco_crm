@extends('layouts.master')
@section('title',$title)
@section('StyleContent')
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
                <form action="{{url('/container/report/boxline')}}" method="get">
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
                            <label>Mode of Shipment</label>
                            <select class="form-control" name="mode_of_shipment">
                                <option value="">All</option>
                                @foreach(['AIR','LAND','SEA'] as $m)
                                    <option value="{{$m}}" {{$mode==$m?'selected':''}}>{{$m}}</option>
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
                        <div class="col-lg-3 mt-3 align-self-end">
                            <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Search</button>
                            <a href="{{ route('containerboxlineexport', request()->query()) }}" class="btn btn-success waves-effect waves-light mt-2"><i class="mdi mdi-download"></i> Export</a>
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
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                <h4 class="text-secondary mb-0">{{$title}}</h4>
                <span class="badge bg-info font-size-13">Total Lines: {{$indexes->total()}}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Container No</th>
                            <th>Code</th>
                            <th>Vendor</th>
                            <th>Received Date</th>
                            <th>Mode</th>
                            <th>Box (CtnNo)</th>
                            <th>Part No</th>
                            <th>Description</th>
                            <th>Brand</th>
                            <th>Location</th>
                            <th>Qty</th>
                            <th>CRM Qty</th>
                            <th>Received</th>
                            <th>Balance</th>
                            <th>Note</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($indexes as $line)
                        <?php $bal = $line->quantity - $line->received_qty; ?>
                        <tr class="{{$line->received_qty > $line->quantity ? 'table-danger' : ''}}">
                            <td>{{ $indexes->firstItem() + $loop->index }}</td>
                            <td>{{optional($line->container)->container_no}}</td>
                            <td>{{optional($line->container)->container_code}}</td>
                            <td>{{optional(optional($line->container)->vendor)->name}}</td>
                            <td>{{ optional($line->container)->received_date ? date('d-m-Y', strtotime($line->container->received_date)) : '-' }}</td>
                            <td>{{optional($line->container)->mode_of_shipment}}</td>
                            <td>{{$line->box_no}}</td>
                            <td>{{$line->part_no}}</td>
                            <td>{{$line->description}}</td>
                            <td>{{$line->brand}}</td>
                            <td>{{$line->location}}</td>
                            <td>{{$line->quantity}}</td>
                            <td>{{$line->crm_qty}}</td>
                            <td>{{$line->received_qty}}</td>
                            <td>{{$bal}}</td>
                            <td>{{ $line->receiptlogs->pluck('note')->filter()->implode('; ') }}</td>
                            <td>{{ $line->receiptlogs->pluck('remarks')->filter()->implode('; ') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="17" class="text-center text-muted">No records found for the selected filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $indexes->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('ScriptContent')
<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>
<script>
$(document).ready(function(){ $(".select2").select2({width:'100%'}); });
</script>
@stop
