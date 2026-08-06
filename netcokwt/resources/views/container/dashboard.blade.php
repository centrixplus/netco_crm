@extends('layouts.master')
@section('title',$title)
@section('StyleContent')
<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<style>
.kpi-card .kpi-label{font-size:11px;letter-spacing:.04em;text-transform:uppercase;color:#74788d;}
.kpi-card .kpi-value{font-size:22px;font-weight:700;color:#495057;}
.mini-bar{height:6px;border-radius:4px;background:#eef2f7;overflow:hidden;}
.mini-bar > span{display:block;height:100%;}
</style>
@endsection
@section('PageContent')
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-16">{{$title}}</h4>
        <div class="page-title-right">
            <a href="{{url('/container')}}" class="btn btn-secondary btn-sm">Container List</a>
        </div>
    </div>
</div>
</div>

<!-- Filters -->
<div class="row">
    <div class="col-md-12">
        <div class="card"><div class="card-body">
            <form action="{{url('/container/dashboard')}}" method="post">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-lg-3"><label>From Date</label><input class="form-control" type="date" name="fromdate" value="{{$fromdate}}"></div>
                    <div class="col-lg-3"><label>To Date</label><input class="form-control" type="date" name="todate" value="{{$todate}}"></div>
                    <div class="col-lg-3"><label>Vendor</label>
                        <select class="form-control select2" name="vendor_id">
                            <option value="">All Vendors</option>
                            @foreach($vendors as $v)<option value="{{$v->id}}" {{$vendor_id==$v->id?'selected':''}}>{{$v->name}}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-lg-2"><label>Mode</label>
                        <select class="form-control" name="mode_of_shipment">
                            <option value="">All</option>
                            @foreach(['AIR','LAND','SEA'] as $m)<option value="{{$m}}" {{$mode==$m?'selected':''}}>{{$m}}</option>@endforeach
                        </select>
                    </div>
                    <div class="col-lg-1 align-self-end"><button class="btn btn-primary w-100 mt-2">Go</button></div>
                </div>
            </form>
        </div></div>
    </div>
</div>

<!-- KPI cards -->
<div class="row">
    <div class="col-md-3 col-6"><div class="card kpi-card"><div class="card-body"><div class="kpi-label">Total Containers</div><div class="kpi-value">{{$totalContainers}}</div></div></div></div>
    <div class="col-md-3 col-6"><div class="card kpi-card"><div class="card-body"><div class="kpi-label">Overall Completion</div><div class="kpi-value text-{{$overallPct>=100?'success':'warning'}}">{{$overallPct}}%</div>
        <div class="mini-bar mt-1"><span class="bg-{{$overallPct>=100?'success':'warning'}}" style="width:{{min($overallPct,100)}}%"></span></div></div></div></div>
    <div class="col-md-3 col-6"><div class="card kpi-card"><div class="card-body"><div class="kpi-label">Completed / Not Completed</div><div class="kpi-value"><span class="text-success">{{$completedCount}}</span> / <span class="text-danger">{{$nonCompletedCount}}</span></div></div></div></div>
    <div class="col-md-3 col-6"><div class="card kpi-card"><div class="card-body"><div class="kpi-label">Pending Qty</div><div class="kpi-value text-danger">{{$pendingQty}}</div></div></div></div>

    <div class="col-md-3 col-6"><div class="card kpi-card"><div class="card-body"><div class="kpi-label">Total Lines</div><div class="kpi-value">{{$totalLines}}</div></div></div></div>
    <div class="col-md-3 col-6"><div class="card kpi-card"><div class="card-body"><div class="kpi-label">Ordered Qty</div><div class="kpi-value">{{$orderedQty}}</div></div></div></div>
    <div class="col-md-3 col-6"><div class="card kpi-card"><div class="card-body"><div class="kpi-label">Received Qty</div><div class="kpi-value">{{$receivedQty}}</div></div></div></div>
    <div class="col-md-3 col-6"><div class="card kpi-card"><div class="card-body"><div class="kpi-label">Extra Parts / Over Lines</div><div class="kpi-value"><span class="text-info">{{$extraCount}}</span> / <span class="text-danger">{{$overLines}}</span></div></div></div></div>
</div>

<!-- Charts row 1 -->
<div class="row">
    <div class="col-md-4"><div class="card"><div class="card-body"><h5 class="text-secondary">Completed vs Not Completed</h5><div id="chartCompleted"></div></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body"><h5 class="text-secondary">Containers by Status</h5><div id="chartStatus"></div></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body"><h5 class="text-secondary">Containers by Mode</h5><div id="chartMode"></div></div></div></div>
</div>

<!-- Charts row 2 -->
<div class="row">
    <div class="col-md-6"><div class="card"><div class="card-body"><h5 class="text-secondary">Receiving Trend (Qty / day)</h5><div id="chartTrend"></div></div></div></div>
    <div class="col-md-6"><div class="card"><div class="card-body"><h5 class="text-secondary">Completion Buckets</h5><div id="chartBuckets"></div></div></div></div>
</div>

<!-- Charts row 3 -->
<div class="row">
    <div class="col-md-6"><div class="card"><div class="card-body"><h5 class="text-secondary">Top Vendors (Ordered Qty)</h5><div id="chartVendors"></div></div></div></div>
    <div class="col-md-6"><div class="card"><div class="card-body"><h5 class="text-secondary">Top Brands (Ordered Qty)</h5><div id="chartBrands"></div></div></div></div>
</div>

<!-- Non-completed containers -->
<div class="row">
<div class="col-md-12">
    <div class="card"><div class="card-body">
        <h4 class="text-secondary">Non-Completed Containers ({{count($nonCompleted)}})</h4>
        <div class="table-responsive" style="max-height:500px;overflow-y:auto;">
        <table class="table table-bordered mb-0">
            <thead style="position:sticky;top:0;background:#eef2f7;z-index:2;">
                <tr><th>#</th><th>Container No</th><th>Vendor</th><th>Status</th><th>Ordered</th><th>Received</th><th>Pending</th><th>Completion</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php $x=1; ?>
                @forelse($nonCompleted as $nc)
                <tr>
                    <td>{{$x++}}</td>
                    <td>{{$nc['container_no']}}</td>
                    <td>{{$nc['vendor']}}</td>
                    <td><span class="badge bg-{{ ['New'=>'danger','In Progress'=>'warning','Completed'=>'success'][$nc['status']] ?? 'secondary' }}">{{$nc['status']}}</span></td>
                    <td>{{$nc['ordered']}}</td>
                    <td>{{$nc['received']}}</td>
                    <td class="text-danger fw-bold">{{$nc['pending']}}</td>
                    <td style="min-width:140px;">
                        <span class="badge bg-{{$nc['pct']>=50?'warning':'danger'}}">{{$nc['pct']}}%</span>
                        <div class="mini-bar mt-1"><span class="bg-{{$nc['pct']>=50?'warning':'danger'}}" style="width:{{$nc['pct']}}%"></span></div>
                    </td>
                    <td><a href="{{url('/container')}}/{{$nc['id']}}/cartons" class="btn btn-outline-primary btn-sm"><i class="mdi mdi-eye"></i> Open</a></td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-success">All containers are fully completed 🎉</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div></div>
</div>
</div>

<!-- Recent activity -->
<div class="row">
    <div class="col-md-6">
        <div class="card"><div class="card-body">
            <h5 class="text-secondary">Recent Receiving Activity</h5>
            <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
                <thead><tr><th>Container</th><th>Box</th><th>Part</th><th>Qty</th><th>By</th><th>At</th></tr></thead>
                <tbody>
                    @forelse($recentReceipts as $r)
                    <tr>
                        <td>{{optional($r->container)->container_no}}</td>
                        <td>{{$r->box_no}}</td>
                        <td>{{$r->part_no}}</td>
                        <td>{{$r->received_qty}}</td>
                        <td>{{optional($r->creator)->name}}</td>
                        <td>{{ date('d-m-Y H:i', strtotime($r->created_at)) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">No receiving activity.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div></div>
    </div>
    <div class="col-md-6">
        <div class="card"><div class="card-body">
            <h5 class="text-secondary">Recent Uploads</h5>
            <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
                <thead><tr><th>Container</th><th>Type</th><th>File</th><th>Matched</th><th>Unmatched</th><th>By</th><th>At</th></tr></thead>
                <tbody>
                    @forelse($recentUploads as $u)
                    <tr>
                        <td>{{optional($u->container)->container_no}}</td>
                        <td>@if($u->type=='crm')<span class="badge bg-warning">CRM</span>@else<span class="badge bg-primary">Received</span>@endif</td>
                        <td>{{$u->file_name}}</td>
                        <td>{{$u->matched}}</td>
                        <td>{{$u->unmatched}}</td>
                        <td>{{optional($u->creator)->name}}</td>
                        <td>{{ date('d-m-Y H:i', strtotime($u->created_at)) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted">No uploads.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div></div>
    </div>
</div>
@endsection
@section('ScriptContent')
<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.44.0/dist/apexcharts.min.js"></script>
<script>
$(document).ready(function(){ $(".select2").select2({width:'100%'}); });

function donut(sel, labels, series, colors){
    new ApexCharts(document.querySelector(sel), {
        chart:{type:'donut',height:260}, labels:labels, series:series, colors:colors,
        legend:{position:'bottom'}, dataLabels:{enabled:true}
    }).render();
}
function bar(sel, cats, data, color, horizontal){
    new ApexCharts(document.querySelector(sel), {
        chart:{type:'bar',height:300}, series:[{name:'Qty',data:data}],
        plotOptions:{bar:{horizontal:!!horizontal,borderRadius:4}}, colors:[color||'#556ee6'],
        dataLabels:{enabled:false}, xaxis:{categories:cats}
    }).render();
}

// Completed vs Not Completed
donut('#chartCompleted', ['Completed','Not Completed'], [{{$completedCount}}, {{$nonCompletedCount}}], ['#34c38f','#f46a6a']);

// Status
donut('#chartStatus', ['New','In Progress','Completed'],
    [{{$statusCounts['New'] ?? 0}}, {{$statusCounts['In Progress'] ?? 0}}, {{$statusCounts['Completed'] ?? 0}}],
    ['#f46a6a','#f1b44c','#34c38f']);

// Mode
donut('#chartMode', ['AIR','LAND','SEA'], [{{$byMode['AIR']}}, {{$byMode['LAND']}}, {{$byMode['SEA']}}], ['#50a5f1','#556ee6','#f1b44c']);

// Receiving trend
new ApexCharts(document.querySelector('#chartTrend'), {
    chart:{type:'area',height:300,toolbar:{show:false}}, series:[{name:'Received Qty',data:@json($trendData)}],
    colors:['#556ee6'], stroke:{curve:'smooth',width:2}, dataLabels:{enabled:false},
    xaxis:{categories:@json($trendLabels)}
}).render();

// Completion buckets
bar('#chartBuckets', ['0%','1-49%','50-99%','100%'],
    [{{$buckets['0']}}, {{$buckets['1-49']}}, {{$buckets['50-99']}}, {{$buckets['100']}}], '#556ee6');

// Top vendors / brands (horizontal)
bar('#chartVendors', @json($vendorLabels), @json($vendorData), '#34c38f', true);
bar('#chartBrands', @json($brandLabels), @json($brandData), '#f1b44c', true);
</script>
@stop
