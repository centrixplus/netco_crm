@extends('layouts.master')
@section('title',$title)
@section('PageContent')
<style>
.info-card .info-item{padding:.35rem 0;}
.info-card .info-label{font-size:11px;letter-spacing:.04em;text-transform:uppercase;color:#74788d;margin-bottom:2px;}
.info-card .info-value{font-size:14px;font-weight:600;color:#495057;margin:0;}
.box-scroll{max-height:600px;overflow-y:auto;}
.box-scroll table thead th{position:sticky;top:0;z-index:2;background:#eef2f7;}
.table-bordered th, .table-bordered td{border:1px solid #6c757d !important;}
#boxTable th.sortable{cursor:pointer;user-select:none;white-space:nowrap;}
#boxTable th.sortable::after{content:"\2195";opacity:.4;margin-left:4px;font-size:11px;}
#boxTable th.sort-asc::after{content:"\2191";opacity:1;}
#boxTable th.sort-desc::after{content:"\2193";opacity:1;}
</style>
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

@if(session('alert'))
<div class="alert alert-success">{!! session('alert') !!}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{!! session('error') !!}</div>
@endif

<!-- Container info header -->
<div class="row">
<div class="col-md-12">
    <div class="card info-card">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-start border-bottom pb-3 mb-3">
                <div>
                    <h4 class="mb-1">Container <span class="text-primary">#{{$container->container_no}}</span> @if($container->container_code)<span class="badge bg-dark align-middle">{{$container->container_code}}</span>@endif</h4>
                    <p class="text-muted mb-0"><i class="bx bx-buildings me-1"></i>{{optional($container->vendor)->name}}</p>
                </div>
                <div class="text-end">
                    @if($container->mode_of_shipment)<span class="badge bg-info font-size-12">{{$container->mode_of_shipment}}</span>@endif
                    <span class="badge bg-{{ ['New'=>'danger','In Progress'=>'warning','Completed'=>'success'][$container->status] ?? 'secondary' }} font-size-12">{{$container->status}}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-6 info-item"><div class="info-label">Vendor Doc No</div><p class="info-value">{{$container->vendor_doc_no ?: '-'}}</p></div>
                <div class="col-md-3 col-6 info-item"><div class="info-label">Shipment Doc No</div><p class="info-value">{{$container->shipment_doc_no ?: '-'}}</p></div>
                <div class="col-md-3 col-6 info-item"><div class="info-label">Received Date</div><p class="info-value">{{ $container->received_date ? date('d-m-Y', strtotime($container->received_date)) : '-' }}</p></div>
                <div class="col-md-3 col-6 info-item"><div class="info-label">PO No</div><p class="info-value">{{$container->po_no ?: '-'}}</p></div>
                <div class="col-md-3 col-6 info-item"><div class="info-label">SA No</div><p class="info-value">{{$container->sa_no ?: '-'}}</p></div>
                <div class="col-md-3 col-6 info-item"><div class="info-label">Tags</div><p class="info-value">{{$container->tag_names ?: '-'}}</p></div>
                <div class="col-md-6 info-item"><div class="info-label">Remarks</div><p class="info-value">{{$container->remarks ?: '-'}}</p></div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Bulk upload tools -->
<div class="row">
<div class="col-md-6">
    <div class="card"><div class="card-body">
        <h5 class="text-secondary">Received Qty — Bulk Upload</h5>
        <a href="{{url('/container')}}/{{$container->id}}/receipt/format" class="btn btn-outline-info btn-sm mb-2"><i class="mdi mdi-download"></i> Download Format</a>
        <form action="{{route('containerreceiptupload')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" name="container_id" value="{{$container->id}}">
            <div class="row">
                <div class="col-8"><input class="form-control form-control-sm" type="file" name="import_file" accept=".xlsx,.xls,.csv" required></div>
                <div class="col-4"><button class="btn btn-primary btn-sm">Upload</button></div>
            </div>
        </form>
    </div></div>
</div>
<div class="col-md-6">
    <div class="card"><div class="card-body">
        <h5 class="text-secondary">CRM Qty — Bulk Upload</h5>
        <a href="{{url('/container')}}/{{$container->id}}/crm/format" class="btn btn-outline-info btn-sm mb-2"><i class="mdi mdi-download"></i> Download Format</a>
        <form action="{{route('containercrmupload')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" name="container_id" value="{{$container->id}}">
            <div class="row">
                <div class="col-8"><input class="form-control form-control-sm" type="file" name="import_file" accept=".xlsx,.xls,.csv" required></div>
                <div class="col-4"><button class="btn btn-primary btn-sm">Upload</button></div>
            </div>
        </form>
    </div></div>
</div>
</div>

<!-- Upload logs -->
<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h5 class="text-secondary">Upload Logs (Received / CRM)</h5>
            <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>File</th>
                        <th>Total Rows</th>
                        <th>Matched</th>
                        <th>Extra</th>
                        <th>Unmatched</th>
                        <th>Uploaded By</th>
                        <th>Uploaded At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $u=1; ?>
                    @forelse($uploadlogs as $log)
                    <tr>
                        <td>{{$u++}}</td>
                        <td>
                            @if($log->type=='crm')<span class="badge bg-warning">CRM</span>
                            @else<span class="badge bg-primary">Received</span>@endif
                        </td>
                        <td>{{$log->file_name}}</td>
                        <td>{{$log->total_rows}}</td>
                        <td>{{$log->matched}}</td>
                        <td>{{$log->extra}}</td>
                        <td>{{$log->unmatched}}</td>
                        <td>{{optional($log->creator)->name}}</td>
                        <td>{{ date('d-m-Y H:i:s', strtotime($log->created_at)) }}</td>
                        <td><a href="{{url('/container/uploadlog')}}/{{$log->id}}" class="btn btn-outline-primary btn-sm"><i class="mdi mdi-eye"></i> View</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center text-muted">No uploads yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Boxes -->
<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h4 class="text-secondary mb-3">Boxes (CtnNo)</h4>
            @php
                $totalBoxes = $boxes->count(); $completedBoxes = 0; $inProgressBoxes = 0; $pendingBoxes = 0;
                foreach($boxes as $sb){
                    $sp = $sb->total_qty > 0 ? $sb->capped_received / $sb->total_qty * 100 : 0;
                    if($sp >= 100){ $completedBoxes++; }
                    elseif(($sb->capped_received ?? 0) > 0){ $inProgressBoxes++; }
                    else { $pendingBoxes++; }
                }
            @endphp
            <div class="row g-2 mb-3">
                <div class="col-md-3 col-6">
                    <div class="border rounded p-2 text-center bg-light">
                        <div class="text-muted small text-uppercase">Total Boxes</div>
                        <div class="fs-4 fw-bold">{{$totalBoxes}}</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="border rounded p-2 text-center bg-light">
                        <div class="text-muted small text-uppercase">Completed</div>
                        <div class="fs-4 fw-bold text-success">{{$completedBoxes}} <span class="fs-6">({{ $totalBoxes>0 ? round($completedBoxes/$totalBoxes*100,1) : 0 }}%)</span></div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="border rounded p-2 text-center bg-light">
                        <div class="text-muted small text-uppercase">In Progress</div>
                        <div class="fs-4 fw-bold text-warning">{{$inProgressBoxes}} <span class="fs-6">({{ $totalBoxes>0 ? round($inProgressBoxes/$totalBoxes*100,1) : 0 }}%)</span></div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="border rounded p-2 text-center bg-light">
                        <div class="text-muted small text-uppercase">Pending</div>
                        <div class="fs-4 fw-bold text-danger">{{$pendingBoxes}} <span class="fs-6">({{ $totalBoxes>0 ? round($pendingBoxes/$totalBoxes*100,1) : 0 }}%)</span></div>
                    </div>
                </div>
            </div>
            <form action="{{url('/container')}}/{{$container->id}}/cartons" method="get" class="row g-2 mb-3">
                <div class="col-lg-3">
                    <input type="text" name="box_no" value="{{$box_no}}" class="form-control form-control-sm" placeholder="Search by Box No (CtnNo)">
                </div>
                <div class="col-lg-3">
                    <input type="text" name="part_no" value="{{$part_no}}" class="form-control form-control-sm" placeholder="Search by Part No">
                </div>
                <div class="col-lg-4 align-self-center">
                    <button type="submit" class="btn btn-primary btn-sm">Search</button>
                    <a href="{{url('/container')}}/{{$container->id}}/cartons" class="btn btn-light btn-sm">Clear</a>
                    <span class="text-muted ms-2">Boxes: {{$boxes->count()}}</span>
                </div>
            </form>
            @if($part_no !== '')
            <div class="alert alert-info py-2">Showing CtnNo (boxes) that contain part: <strong>{{$part_no}}</strong></div>
            @endif
            <div class="table-responsive box-scroll">
            <table class="table table-bordered" id="boxTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="sortable" data-type="text">CtnNo (Box)</th>
                        <th class="sortable" data-type="num">Lines</th>
                        <th class="sortable" data-type="num">Total Qty</th>
                        <th class="sortable" data-type="num">Total Received</th>
                        <th class="sortable" data-type="num">Completion</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $x=1; ?>
                    @forelse($boxes as $b)
                    <tr>
                        <td>{{$x++}}</td>
                        <td class="box-cell">{{$b->box_no}}</td>
                        <td>{{$b->line_count}}</td>
                        <td>{{(int)$b->total_qty}}</td>
                        <td>{{(int)$b->total_received}}</td>
                        @php
                            $bpct = $b->total_qty > 0 ? round($b->capped_received / $b->total_qty * 100, 2) : 0;
                            $bColor = $bpct >= 100 ? 'success' : 'warning';
                        @endphp
                        <td><span class="badge bg-{{$bColor}}">{{$bpct}}%</span></td>
                        <td>
                            <a href="{{url('/container')}}/{{$container->id}}/carton/{{urlencode($b->box_no)}}" class="btn btn-outline-primary btn-sm"><i class="mdi mdi-eye"></i> Open</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted">No boxes found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Status workflow -->
@php
    $statusColors = ['New'=>'danger','In Progress'=>'warning','Completed'=>'success'];
    $curColor = $statusColors[$container->status] ?? 'secondary';
    $curIdx = array_search($container->status, $statuses);
    if($curIdx === false){ $curIdx = 0; }
@endphp
<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h5 class="text-secondary">Status</h5>
            <div class="d-flex flex-wrap align-items-center mb-3">
                @foreach($statuses as $i => $s)
                    <span class="badge {{ $i <= $curIdx ? 'bg-'.($statusColors[$s] ?? 'secondary') : 'bg-light text-muted' }} font-size-13 me-1">{{$s}}</span>
                    @if(!$loop->last)<span class="text-muted me-1">&rarr;</span>@endif
                @endforeach
            </div>

            @if($curIdx < count($statuses)-1)
            <form action="{{route('containerstatusupdate')}}" method="post" class="row g-2 align-items-end">
                {{csrf_field()}}
                <input type="hidden" name="container_id" value="{{$container->id}}">
                <div class="col-lg-3">
                    <label>Move to</label>
                    <select class="form-control" name="status" required>
                        @foreach($statuses as $i => $s)
                            @if($i > $curIdx)
                                <option value="{{$s}}">{{$s}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-5">
                    <label>Note</label>
                    <input class="form-control" type="text" name="note" placeholder="Optional note">
                </div>
                <div class="col-lg-3">
                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-arrow-right-bold"></i> Update Status</button>
                </div>
            </form>
            @else
            <span class="text-success"><i class="mdi mdi-check-circle"></i> Container is <strong>Completed</strong> — no further status.</span>
            @endif

            @if($statuslogs->count())
            <h6 class="text-secondary mt-4">Status History</h6>
            <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0">
                <thead>
                    <tr><th>#</th><th>From</th><th>To</th><th>Note</th><th>Changed By</th><th>Changed At</th></tr>
                </thead>
                <tbody>
                    <?php $s=1; ?>
                    @foreach($statuslogs as $slog)
                    <tr>
                        <td>{{$s++}}</td>
                        <td>{{$slog->from_status}}</td>
                        <td><span class="badge bg-{{$statusColors[$slog->to_status] ?? 'secondary'}}">{{$slog->to_status}}</span></td>
                        <td>{{$slog->note}}</td>
                        <td>{{optional($slog->creator)->name}}</td>
                        <td>{{ date('d-m-Y H:i:s', strtotime($slog->created_at)) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @endif
        </div>
    </div>
</div>
</div>
@endsection
@section('ScriptContent')
<script>
$(function(){
    $('#boxTable thead th.sortable').on('click', function(){
        var $th = $(this), idx = $th.index(), type = $th.data('type') || 'text';
        var asc = !$th.hasClass('sort-asc');
        $('#boxTable thead th').removeClass('sort-asc sort-desc');
        $th.addClass(asc ? 'sort-asc' : 'sort-desc');
        var $tb = $('#boxTable tbody');
        var rows = $tb.find('tr').get().filter(function(r){ return $(r).children('td').length > 1; });
        rows.sort(function(a,b){
            var av = $(a).children('td').eq(idx).text().trim();
            var bv = $(b).children('td').eq(idx).text().trim();
            if(type === 'num'){
                av = parseFloat(av.replace(/[^0-9.\-]/g,'')) || 0;
                bv = parseFloat(bv.replace(/[^0-9.\-]/g,'')) || 0;
                return asc ? av - bv : bv - av;
            }
            return asc ? av.localeCompare(bv) : bv.localeCompare(av);
        });
        $.each(rows, function(i,r){ $tb.append(r); $(r).children('td').eq(0).text(i+1); });
    });
});
</script>
@stop
