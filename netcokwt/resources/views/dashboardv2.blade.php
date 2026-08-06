@extends('layouts.master')
@section('title','Dashboard V2')
@section('StyleContent')
<style>
.kpi-card { border-left: 4px solid; transition: transform 0.2s; }
.kpi-card:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
.kpi-sales { border-left-color: #556ee6; }
.kpi-orders { border-left-color: #50a5f1; }
.kpi-receipts { border-left-color: #34c38f; }
.kpi-customers { border-left-color: #f1b44c; }
.kpi-pending { border-left-color: #f46a6a; }
.kpi-pr { border-left-color: #74788d; }
.trend-up { color: #34c38f; }
.trend-down { color: #f46a6a; }
.pipeline-stage { text-align: center; padding: 12px 8px; border-radius: 6px; margin-bottom: 8px; }
.pipeline-arrow { display: flex; align-items: center; justify-content: center; color: #adb5bd; font-size: 20px; }
.stage-new { background: rgba(85,110,230,0.1); border: 1px solid rgba(85,110,230,0.3); }
.stage-authorized { background: rgba(80,165,241,0.1); border: 1px solid rgba(80,165,241,0.3); }
.stage-posted { background: rgba(52,195,143,0.1); border: 1px solid rgba(52,195,143,0.3); }
.stage-onhold { background: rgba(241,180,76,0.1); border: 1px solid rgba(241,180,76,0.3); }
.stage-bounce { background: rgba(244,106,106,0.1); border: 1px solid rgba(244,106,106,0.3); }
.stage-reconciled { background: rgba(52,195,143,0.15); border: 1px solid rgba(52,195,143,0.4); }
.stage-pdc { background: rgba(116,120,141,0.1); border: 1px solid rgba(116,120,141,0.3); }
.stage-transit { background: rgba(80,165,241,0.15); border: 1px solid rgba(80,165,241,0.4); }
.stock-critical { background-color: rgba(244,106,106,0.08); }
.stock-warning { background-color: rgba(241,180,76,0.08); }
.card-title-border { border-bottom: 2px solid #556ee6; padding-bottom: 8px; margin-bottom: 16px; }
</style>
@endsection

@section('PageContent')
{{-- Header --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard V2</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- Period Label --}}
<div class="row mb-2">
    <div class="col-12">
        <span class="text-muted font-size-13">
            <i class="bx bx-calendar me-1"></i>
            Current Month: <strong>{{ date('d M', strtotime($currentMonthStart)) }} - {{ date('d M, Y', strtotime($currentMonthEnd)) }}</strong>
            <span class="ms-3 badge bg-soft-info text-info">Auto-refresh: current month data</span>
        </span>
    </div>
</div>

{{-- Row 1: KPI Cards --}}
<div class="row">
    {{-- Total Sales --}}
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card mini-stats-wid kpi-card kpi-sales">
            <div class="card-body">
                <p class="text-muted fw-medium mb-2">Total Sales</p>
                <h5 class="mb-1">{{ number_format($currentSales->total_amount, 3, '.', ',') }}</h5>
                <p class="mb-0 font-size-12">
                    @if($salesTrend >= 0)
                        <span class="trend-up"><i class="mdi mdi-arrow-up-bold"></i> {{ round($salesTrend, 1) }}%</span>
                    @else
                        <span class="trend-down"><i class="mdi mdi-arrow-down-bold"></i> {{ round(abs($salesTrend), 1) }}%</span>
                    @endif
                    <span class="text-muted ms-1">vs last month</span>
                </p>
            </div>
        </div>
    </div>

    {{-- Order Count --}}
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card mini-stats-wid kpi-card kpi-orders">
            <div class="card-body">
                <p class="text-muted fw-medium mb-2">Orders</p>
                <h5 class="mb-1">{{ $currentSales->order_count }}</h5>
                <p class="mb-0 font-size-12">
                    @if($ordersTrend >= 0)
                        <span class="trend-up"><i class="mdi mdi-arrow-up-bold"></i> {{ round($ordersTrend, 1) }}%</span>
                    @else
                        <span class="trend-down"><i class="mdi mdi-arrow-down-bold"></i> {{ round(abs($ordersTrend), 1) }}%</span>
                    @endif
                    <span class="text-muted ms-1">vs last month</span>
                </p>
            </div>
        </div>
    </div>

    {{-- Collections --}}
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card mini-stats-wid kpi-card kpi-receipts">
            <div class="card-body">
                <p class="text-muted fw-medium mb-2">Collections</p>
                <h5 class="mb-1">{{ number_format($currentReceipts->total_receipts, 3, '.', ',') }}</h5>
                <p class="mb-0 font-size-12">
                    @if($receiptsTrend >= 0)
                        <span class="trend-up"><i class="mdi mdi-arrow-up-bold"></i> {{ round($receiptsTrend, 1) }}%</span>
                    @else
                        <span class="trend-down"><i class="mdi mdi-arrow-down-bold"></i> {{ round(abs($receiptsTrend), 1) }}%</span>
                    @endif
                    <span class="text-muted ms-1">vs last month</span>
                </p>
            </div>
        </div>
    </div>

    {{-- Avg Order Value --}}
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card mini-stats-wid kpi-card kpi-customers">
            <div class="card-body">
                <p class="text-muted fw-medium mb-2">Avg Order Value</p>
                <h5 class="mb-1">{{ number_format($avgOrderValue, 3, '.', ',') }}</h5>
                <p class="mb-0 font-size-12">
                    <span class="text-muted">{{ $currentSales->order_count }} orders</span>
                </p>
            </div>
        </div>
    </div>

    {{-- Pending Orders --}}
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card mini-stats-wid kpi-card kpi-pending">
            <div class="card-body">
                <p class="text-muted fw-medium mb-2">Pending Orders</p>
                <h5 class="mb-1">{{ $pendingOrders->cnt }}</h5>
                <p class="mb-0 font-size-12">
                    <span class="text-muted">{{ number_format($pendingOrders->amt, 3) }} value</span>
                </p>
            </div>
        </div>
    </div>

    {{-- Open PRs --}}
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card mini-stats-wid kpi-card kpi-pr">
            <div class="card-body">
                <p class="text-muted fw-medium mb-2">Open PRs</p>
                <h5 class="mb-1">{{ $openPRs }}</h5>
                <p class="mb-0 font-size-12">
                    <span class="text-muted">purchase requests</span>
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Row 2: Charts --}}
<div class="row">
    {{-- Sales Trend --}}
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title card-title-border">Sales Trend (This Month)</h4>
                <div id="salesTrendChart"></div>
            </div>
        </div>
    </div>

    {{-- Order Status Donut --}}
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title card-title-border">Order Status</h4>
                <div id="statusDonutChart"></div>
            </div>
        </div>
    </div>
</div>

{{-- Row 3: Brand Chart + Receipt Pipeline --}}
<div class="row">
    {{-- Brand Sales Bar Chart --}}
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title card-title-border">Brand-wise Sales</h4>
                <div id="brandBarChart"></div>
            </div>
        </div>
    </div>

    {{-- Receipt Pipeline --}}
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title card-title-border">Receipt Pipeline</h4>
                @php
                    $stageClasses = ['stage-new','stage-authorized','stage-posted','stage-onhold','stage-bounce','stage-reconciled','stage-pdc','stage-transit'];
                    $stageIcons = ['bx-plus-circle','bx-check-circle','bx-upload','bx-pause-circle','bx-x-circle','bx-check-double','bx-time-five','bx-transfer-alt'];
                    $totalReceipts = $receiptPipeline->sum('cnt');
                @endphp
                <div class="row g-2">
                    @foreach($receiptPipeline as $i => $stage)
                    <div class="col-6 col-md-3">
                        <div class="pipeline-stage {{ $stageClasses[$i] ?? 'stage-new' }}">
                            <i class="bx {{ $stageIcons[$i] ?? 'bx-circle' }} font-size-20 mb-1 d-block"></i>
                            <div class="font-size-11 text-muted">{{ $stage->stage_name }}</div>
                            <div class="fw-bold font-size-16">{{ $stage->cnt }}</div>
                            <div class="font-size-11 text-muted">{{ number_format($stage->total_amount, 0, '.', ',') }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($totalReceipts > 0)
                <div class="mt-3">
                    <div class="progress" style="height: 8px;">
                        @foreach($receiptPipeline as $i => $stage)
                            @php
                                $pct = ($stage->cnt / $totalReceipts) * 100;
                                $colors = ['#556ee6','#50a5f1','#34c38f','#f1b44c','#f46a6a','#2ca87f','#74788d','#50a5f1'];
                            @endphp
                            <div class="progress-bar" style="width: {{ $pct }}%; background-color: {{ $colors[$i] ?? '#74788d' }}" title="{{ $stage->stage_name }}: {{ $stage->cnt }}"></div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Row 4: Top Customers + Recent Orders --}}
<div class="row">
    {{-- Top 10 Customers --}}
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title card-title-border">Top 10 Customers (This Month)</h4>
                <div class="table-responsive">
                    <table class="table table-hover table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Code</th>
                                <th class="text-end">Orders</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topCustomers as $i => $cust)
                            <tr>
                                <td><span class="badge bg-soft-primary text-primary">{{ $i + 1 }}</span></td>
                                <td>{{ $cust->name }}</td>
                                <td><span class="text-muted">{{ $cust->code }}</span></td>
                                <td class="text-end">{{ $cust->order_count }}</td>
                                <td class="text-end fw-bold">{{ number_format($cust->total_amount, 3) }}</td>
                            </tr>
                            @endforeach
                            @if(count($topCustomers) == 0)
                            <tr><td colspan="5" class="text-center text-muted">No orders this month</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title card-title-border">Recent Orders</h4>
                <div class="table-responsive">
                    <table class="table table-hover table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order No</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th class="text-end">Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td><a href="{{url('/showsales')}}/{{$order->id}}" class="text-primary fw-bold">{{ $order->sale_no }}</a></td>
                                <td>{{ date('d M', strtotime($order->sale_date)) }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td class="text-end">{{ $order->grand_total }}</td>
                                <td><span class="badge {{ $order->status_color }} font-size-11">{{ $order->status_name }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Row 5: Today's Collections + Low Stock --}}
<div class="row">
    {{-- Today's Collections --}}
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title card-title-border mb-0 pb-2">Today's Collections</h4>
                    <span class="badge bg-soft-success text-success font-size-14">{{ number_format($todayCollectionTotal, 3) }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Receipt #</th>
                                <th>Customer</th>
                                <th class="text-end">Amount</th>
                                <th>Stage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todayCollections as $col)
                            <tr>
                                <td>{{ $col->receipt_no }}</td>
                                <td>{{ $col->customer_name }}</td>
                                <td class="text-end">{{ number_format($col->receipt_amount, 3) }}</td>
                                <td><span class="badge bg-soft-info text-info">{{ $col->stage_name }}</span></td>
                            </tr>
                            @endforeach
                            @if(count($todayCollections) == 0)
                            <tr><td colspan="4" class="text-center text-muted">No collections today</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Low Stock Alerts --}}
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title card-title-border mb-0 pb-2">Low Stock Alerts</h4>
                    <span class="badge bg-soft-danger text-danger font-size-14">{{ count($lowStock) }} items</span>
                </div>
                <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                    <table class="table table-hover table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Brand</th>
                                <th class="text-end">Stock</th>
                                <th class="text-end">Transit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStock as $item)
                            <tr class="{{ intval($item->qty) <= 0 ? 'stock-critical' : 'stock-warning' }}">
                                <td>{{ $item->product_name }}</td>
                                <td><span class="badge bg-soft-secondary text-secondary">{{ $item->brand_name }}</span></td>
                                <td class="text-end fw-bold {{ intval($item->qty) <= 0 ? 'text-danger' : 'text-warning' }}">{{ $item->qty }}</td>
                                <td class="text-end">{{ $item->t_qty }}</td>
                            </tr>
                            @endforeach
                            @if(count($lowStock) == 0)
                            <tr><td colspan="4" class="text-center text-muted">All stock levels healthy</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Row 6: User-wise Sales + Brand-wise Sales Table --}}
<div class="row">
    {{-- User-wise Sales --}}
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title card-title-border">User-wise Sales (This Month)</h4>
                <div class="table-responsive">
                    <table class="table table-hover table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th class="text-end">Orders</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userSales as $us)
                            <tr>
                                <td>{{ $us->name }}</td>
                                <td><span class="badge bg-soft-primary text-primary">{{ $us->role }}</span></td>
                                <td class="text-end">{{ $us->order_count }}</td>
                                <td class="text-end fw-bold">{{ number_format($us->total_amount, 3) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Brand-wise Sales Table --}}
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title card-title-border">Brand-wise Sales (This Month)</h4>
                <div class="table-responsive">
                    <table class="table table-hover table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Brand</th>
                                <th class="text-end">Qty Sold</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brandSales as $bs)
                            <tr>
                                <td><span class="fw-bold">{{ $bs->brand_name }}</span></td>
                                <td class="text-end">{{ number_format($bs->total_qty) }}</td>
                                <td class="text-end fw-bold">{{ number_format($bs->total_amount, 3) }}</td>
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
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.44.0/dist/apexcharts.min.js"></script>
<script>
// Sales Trend Area Chart
var salesTrendOptions = {
    chart: { type: 'area', height: 350, toolbar: { show: false }, zoom: { enabled: false } },
    series: [{
        name: 'Sales Amount',
        data: @json($salesTrendValues)
    }, {
        name: 'Order Count',
        data: @json($salesTrendCounts)
    }],
    xaxis: {
        categories: @json($salesTrendLabels),
        labels: { style: { fontSize: '11px' } }
    },
    colors: ['#556ee6', '#f1b44c'],
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: [2, 2] },
    fill: {
        type: 'gradient',
        gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 95, 100] }
    },
    yaxis: [{
        title: { text: 'Amount' },
        labels: { formatter: function(val) { return val.toFixed(0); } }
    }, {
        opposite: true,
        title: { text: 'Orders' },
        labels: { formatter: function(val) { return Math.round(val); } }
    }],
    tooltip: {
        shared: true,
        y: [{
            formatter: function(val) { return val ? val.toFixed(3) : '0.000'; }
        }, {
            formatter: function(val) { return val ? Math.round(val) : 0; }
        }]
    },
    grid: { borderColor: '#f1f1f1' },
    legend: { position: 'top' }
};
new ApexCharts(document.querySelector("#salesTrendChart"), salesTrendOptions).render();

// Order Status Donut
var statusLabels = @json($statusLabels);
var statusValues = @json($statusValues);
var statusColors = @json($statusColors);

var statusDonutOptions = {
    chart: { type: 'donut', height: 350 },
    series: statusValues,
    labels: statusLabels,
    colors: statusColors,
    legend: { position: 'bottom', fontSize: '12px' },
    plotOptions: {
        pie: {
            donut: {
                size: '55%',
                labels: {
                    show: true,
                    total: {
                        show: true,
                        label: 'Total Orders',
                        formatter: function(w) {
                            return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                        }
                    }
                }
            }
        }
    },
    responsive: [{ breakpoint: 480, options: { chart: { height: 300 } } }]
};
new ApexCharts(document.querySelector("#statusDonutChart"), statusDonutOptions).render();

// Brand Sales Bar Chart
var brandBarOptions = {
    chart: { type: 'bar', height: 350, toolbar: { show: false } },
    series: [{
        name: 'Amount',
        data: @json($brandChartValues)
    }],
    xaxis: {
        categories: @json($brandChartLabels),
        labels: { style: { fontSize: '11px' } }
    },
    colors: ['#34c38f'],
    plotOptions: {
        bar: { borderRadius: 4, horizontal: false, columnWidth: '50%', distributed: true }
    },
    dataLabels: { enabled: false },
    yaxis: {
        labels: { formatter: function(val) { return val.toFixed(0); } }
    },
    tooltip: {
        y: { formatter: function(val) { return val.toFixed(3); } }
    },
    legend: { show: false },
    grid: { borderColor: '#f1f1f1' }
};
new ApexCharts(document.querySelector("#brandBarChart"), brandBarOptions).render();
</script>
@endsection
