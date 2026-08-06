@extends('layouts.master')
@section('title',$title)
@section('PageContent')
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-16">{{$title}}</h4>
        <div class="page-title-right">
            @if($container)
            <a href="{{url('/container')}}/{{$container->id}}/cartons" class="btn btn-secondary btn-sm">Back to Boxes</a>
            @endif
        </div>
    </div>
</div>
</div>

<!-- Upload summary -->
<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2"><strong>Type:</strong>
                    @if($log->type=='crm')<span class="badge bg-warning">CRM</span>@else<span class="badge bg-primary">Received</span>@endif
                </div>
                <div class="col-md-3"><strong>File:</strong> {{$log->file_name}}</div>
                <div class="col-md-2"><strong>Container:</strong> {{optional($container)->container_no}}</div>
                <div class="col-md-2"><strong>Uploaded By:</strong> {{optional($log->creator)->name}}</div>
                <div class="col-md-3"><strong>Uploaded At:</strong> {{ date('d-m-Y H:i:s', strtotime($log->created_at)) }}</div>
            </div>
            <div class="row mt-2">
                <div class="col-md-2"><span class="badge bg-info">Total Rows: {{$log->total_rows}}</span></div>
                <div class="col-md-2"><span class="badge bg-success">Matched: {{$log->matched}}</span></div>
                @if($log->type=='received')
                <div class="col-md-2"><span class="badge bg-secondary">Extra: {{$log->extra}}</span></div>
                @endif
                <div class="col-md-2"><span class="badge bg-danger">Unmatched: {{$log->unmatched}}</span></div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Unmatched -->
<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h5 class="text-secondary">Unmatched ({{$unmatched->count()}})</h5>
            <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0">
                <thead>
                    <tr><th>#</th><th>Row #</th><th>Box (CtnNo)</th><th>Part No</th><th>Qty</th><th>Reason</th></tr>
                </thead>
                <tbody>
                    <?php $n=1; ?>
                    @forelse($unmatched as $u)
                    <tr class="table-danger">
                        <td>{{$n++}}</td>
                        <td>{{$u->row_no}}</td>
                        <td>{{$u->box_no}}</td>
                        <td>{{$u->part_no}}</td>
                        <td>{{$u->qty}}</td>
                        <td>{{$u->reason}}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">No unmatched rows — everything imported cleanly.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
</div>

@if($log->type=='received')
<!-- Matched (Received) -->
<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h5 class="text-secondary">Matched ({{$matched->count()}})</h5>
            <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0">
                <thead>
                    <tr><th>#</th><th>Box (CtnNo)</th><th>Part No</th><th>Received Qty</th><th>By</th><th>At</th></tr>
                </thead>
                <tbody>
                    <?php $m=1; ?>
                    @forelse($matched as $r)
                    <tr>
                        <td>{{$m++}}</td>
                        <td>{{$r->box_no}}</td>
                        <td>{{$r->part_no}}</td>
                        <td>{{$r->received_qty}}</td>
                        <td>{{optional($r->creator)->name}}</td>
                        <td>{{ date('d-m-Y H:i:s', strtotime($r->created_at)) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">No matched rows.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
</div>
@endif
@endsection
