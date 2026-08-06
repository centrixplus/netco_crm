@extends('layouts.master')
@section('title','PO Dashboard')
@section('StyleContent')
<style>
* { box-sizing: border-box; }

.dashboard-container {
    padding: 30px;
    background: #f8fafc;
    min-height: calc(100vh - 70px);
}

/* Filter Section */
.filter-section {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.filter-title {
    font-size: 14px;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 16px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.quick-filters {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.quick-filter-btn {
    padding: 10px 18px;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    color: #475569;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}
.quick-filter-btn:hover {
    background: #e2e8f0;
    color: #1e293b;
}
.quick-filter-btn.active {
    background: #3b82f6;
    border-color: #3b82f6;
    color: #fff;
}
.date-range-row {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.date-input-group {
    display: flex;
    align-items: center;
    gap: 8px;
}
.date-input-group label {
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
}
.date-input-group input[type="date"] {
    padding: 10px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    color: #334155;
    background: #fff;
    min-width: 160px;
}
.date-input-group input[type="date"]:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
}
.filter-actions {
    display: flex;
    gap: 10px;
    margin-left: auto;
}
.btn-apply {
    padding: 10px 24px;
    background: #3b82f6;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
}
.btn-apply:hover {
    background: #2563eb;
}
.btn-clear {
    padding: 10px 20px;
    background: #fff;
    color: #64748b;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
}
.btn-clear:hover {
    background: #f8fafc;
    color: #334155;
}
.current-range {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    background: #eff6ff;
    border-radius: 6px;
    font-size: 13px;
    color: #1e40af;
    margin-left: 16px;
}
.current-range i {
    font-size: 16px;
}

/* Top Summary Bar */
.summary-bar {
    display: flex;
    gap: 24px;
    margin-bottom: 30px;
}
.summary-item {
    flex: 1;
    background: #fff;
    border-radius: 16px;
    padding: 28px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.summary-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}
.summary-item.vendors::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
.summary-item.orders::before { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
.summary-item.received::before { background: linear-gradient(90deg, #10b981, #34d399); }

.summary-number {
    font-size: 42px;
    font-weight: 800;
    color: #1e293b;
    line-height: 1;
    margin-bottom: 8px;
}
.summary-label {
    font-size: 14px;
    color: #64748b;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.summary-icon {
    position: absolute;
    right: 24px;
    top: 50%;
    transform: translateY(-50%);
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
}
.summary-item.vendors .summary-icon { background: #ede9fe; color: #7c3aed; }
.summary-item.orders .summary-icon { background: #dbeafe; color: #2563eb; }
.summary-item.received .summary-icon { background: #d1fae5; color: #059669; }

/* Main Grid Layout */
.main-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 24px;
}
@media (max-width: 1200px) {
    .main-grid { grid-template-columns: 1fr; }
    .summary-bar { flex-direction: column; }
}

/* Card Base */
.card-modern {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    overflow: hidden;
}
.card-header-modern {
    padding: 20px 24px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.card-title-modern {
    font-size: 16px;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}
.card-body-modern {
    padding: 24px;
}

/* Fulfillment Meter */
.fulfillment-section {
    margin-bottom: 24px;
}
.meter-container {
    position: relative;
    padding: 40px;
    text-align: center;
}
.meter-circle {
    position: relative;
    width: 200px;
    height: 200px;
    margin: 0 auto 24px;
}
.meter-circle svg {
    transform: rotate(-90deg);
    width: 200px;
    height: 200px;
}
.meter-circle circle {
    fill: none;
    stroke-width: 12;
}
.meter-circle .bg {
    stroke: #e2e8f0;
}
.meter-circle .progress {
    stroke: #3b82f6;
    stroke-linecap: round;
    transition: stroke-dashoffset 1s ease;
}
.meter-value {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}
.meter-percent {
    font-size: 48px;
    font-weight: 800;
    color: #1e293b;
    line-height: 1;
}
.meter-percent span {
    font-size: 24px;
    color: #64748b;
}
.meter-label {
    font-size: 13px;
    color: #64748b;
    margin-top: 4px;
}

.qty-stats {
    display: flex;
    justify-content: center;
    gap: 48px;
}
.qty-stat {
    text-align: center;
}
.qty-stat-value {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 4px;
}
.qty-stat-value.ordered { color: #3b82f6; }
.qty-stat-value.received { color: #10b981; }
.qty-stat-value.pending { color: #f59e0b; }
.qty-stat-label {
    font-size: 12px;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Age Distribution */
.age-distribution {
    padding: 24px;
    border-top: 1px solid #f1f5f9;
}
.age-title {
    font-size: 14px;
    font-weight: 600;
    color: #475569;
    margin-bottom: 20px;
}
.age-bars {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.age-bar-row {
    display: flex;
    align-items: center;
    gap: 12px;
}
.age-bar-label {
    width: 80px;
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
}
.age-bar-track {
    flex: 1;
    height: 28px;
    background: #f1f5f9;
    border-radius: 6px;
    overflow: hidden;
    position: relative;
}
.age-bar-fill {
    height: 100%;
    border-radius: 6px;
    display: flex;
    align-items: center;
    padding-left: 12px;
    font-size: 12px;
    font-weight: 600;
    color: #fff;
    min-width: fit-content;
    transition: width 0.5s ease;
}
.age-bar-fill.green { background: #10b981; }
.age-bar-fill.yellow { background: #f59e0b; }
.age-bar-fill.orange { background: #f97316; }
.age-bar-fill.red { background: #ef4444; }
.age-bar-count {
    width: 40px;
    text-align: right;
    font-size: 14px;
    font-weight: 600;
    color: #334155;
}

/* Recent POs Table */
.table-section {
    margin-top: 0;
}
.po-table {
    width: 100%;
    border-collapse: collapse;
}
.po-table thead th {
    padding: 14px 20px;
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}
.po-table tbody td {
    padding: 16px 20px;
    font-size: 14px;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
}
.po-table tbody tr:hover {
    background: #f8fafc;
}
.po-table tbody tr:last-child td {
    border-bottom: none;
}
.po-id {
    font-weight: 600;
    color: #1e293b;
}
.vendor-name {
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.status-pill.on-track { background: #d1fae5; color: #065f46; }
.status-pill.delayed { background: #fef3c7; color: #92400e; }
.status-pill.at-risk { background: #ffedd5; color: #9a3412; }
.status-pill.critical { background: #fee2e2; color: #991b1b; }
.status-pill::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
}
.status-pill.on-track::before { background: #10b981; }
.status-pill.delayed::before { background: #f59e0b; }
.status-pill.at-risk::before { background: #f97316; }
.status-pill.critical::before { background: #ef4444; }

/* Right Sidebar */
.sidebar-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    margin-bottom: 24px;
}
.sidebar-header {
    padding: 20px;
    border-bottom: 1px solid #f1f5f9;
}
.sidebar-title {
    font-size: 15px;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}
.sidebar-body {
    padding: 20px;
}

/* Top Vendors List */
.vendor-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.vendor-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8fafc;
    border-radius: 10px;
}
.vendor-avatar {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 600;
    font-size: 14px;
}
.vendor-info {
    flex: 1;
}
.vendor-info-name {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 2px;
}
.vendor-info-orders {
    font-size: 12px;
    color: #64748b;
}
.vendor-amount {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
}

/* Quick Actions */
.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.quick-action {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    background: #f8fafc;
    border-radius: 10px;
    text-decoration: none;
    color: #334155;
    transition: all 0.2s;
}
.quick-action:hover {
    background: #e2e8f0;
    color: #1e293b;
}
.quick-action i {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}
.quick-action.blue i { background: #dbeafe; color: #2563eb; }
.quick-action.green i { background: #d1fae5; color: #059669; }
.quick-action.purple i { background: #ede9fe; color: #7c3aed; }
.quick-action span {
    font-size: 14px;
    font-weight: 500;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 24px;
    color: #94a3b8;
}
.empty-state i {
    font-size: 56px;
    margin-bottom: 16px;
    opacity: 0.5;
}
.empty-state p {
    font-size: 15px;
    margin: 0;
}

@media (max-width: 768px) {
    .quick-filters { gap: 8px; }
    .quick-filter-btn { padding: 8px 12px; font-size: 12px; }
    .date-range-row { flex-direction: column; align-items: stretch; }
    .filter-actions { margin-left: 0; margin-top: 16px; }
    .current-range { margin-left: 0; margin-top: 10px; }
}
</style>

<!-- PO Details Modal -->
<div id="poDetailModal" class="po-modal-overlay" onclick="closePoModal(event)">
    <div class="po-modal-container" onclick="event.stopPropagation()">
        <div class="po-modal-header">
            <h4 id="modalPoNumber">PO Details</h4>
            <button class="po-modal-close" onclick="closePoModal()">&times;</button>
        </div>
        <div class="po-modal-body" id="modalContent">
            <div class="modal-loading">
                <div class="spinner"></div>
                <p>Loading PO details...</p>
            </div>
        </div>
    </div>
</div>

<style>
/* Modal Styles */
.po-modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    animation: fadeIn 0.2s ease;
}

.po-modal-overlay.active {
    display: flex;
}

.po-modal-container {
    background: #fff;
    border-radius: 16px;
    width: 90%;
    max-width: 800px;
    max-height: 85vh;
    overflow: hidden;
    box-shadow: 0 25px 50px rgba(0,0,0,0.25);
    animation: slideUp 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { transform: translateY(30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.po-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc; border-bottom: 1px solid #e2e8f0;
}

.po-modal-header h4 {
    margin: 0;
    color: #1e293b;
    font-size: 1.25rem;
}

.po-modal-close {
    background: #e2e8f0;
    border: none;
    color: #64748b;
    font-size: 24px;
    cursor: pointer;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.po-modal-close:hover {
    background: #cbd5e1;
    color: #334155;
}

.po-modal-body {
    padding: 25px;
    overflow-y: auto;
    max-height: calc(85vh - 80px);
}

.modal-loading {
    text-align: center;
    padding: 40px;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #e2e8f0;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 15px;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.po-detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.detail-card {
    background: #f8fafc;
    padding: 18px;
    border-radius: 12px;
    border-left: 4px solid #667eea;
}

.detail-label {
    font-size: 0.75rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
}

.detail-value {
    font-size: 1.1rem;
    color: #1e293b;
    font-weight: 600;
}

.line-items-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.line-items-table th {
    background: #f1f5f9;
    padding: 12px;
    text-align: left;
    font-size: 0.8rem;
    color: #475569;
    text-transform: uppercase;
    font-weight: 600;
}

.line-items-table td {
    padding: 12px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
}

.line-items-table tr:hover {
    background: #f8fafc;
}

.section-title {
    font-size: 1rem;
    color: #334155;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e2e8f0;
}

.error-message {
    text-align: center;
    padding: 30px;
    color: #dc2626;
}

.error-message i {
    font-size: 48px;
    margin-bottom: 10px;
}

.line-items-search {
    margin-bottom: 15px;
}

.line-items-search input {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.9rem;
    outline: none;
    transition: border-color 0.2s;
}

.line-items-search input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.line-items-search input::placeholder {
    color: #94a3b8;
}

.line-items-table tr.hidden {
    display: none;
}

.no-results {
    text-align: center;
    padding: 20px;
    color: #94a3b8;
}


.clickable-box {
    transition: all 0.2s ease;
    border: 2px solid transparent;
}

.clickable-box:hover {
    background: #f1f5f9;
    border-color: #667eea;
    border-radius: 10px;
    transform: translateX(5px);
}

/* Vendor Orders Modal */
.vendor-modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    animation: fadeIn 0.2s ease;
}

.vendor-modal-overlay.active {
    display: flex;
}

.vendor-orders-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.vendor-orders-table th {
    background: #f1f5f9;
    padding: 12px;
    text-align: left;
    font-size: 0.8rem;
    color: #475569;
    text-transform: uppercase;
    font-weight: 600;
}

.vendor-orders-table td {
    padding: 12px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
}

.vendor-orders-table tr:hover {
    background: #f8fafc;
}

.vendor-orders-table .po-link {
    color: #667eea;
    cursor: pointer;
    text-decoration: underline;
}

.vendor-orders-table .po-link:hover {
    color: #4f46e5;
}

</style>

<!-- Vendor Orders Modal -->
<div id="vendorOrdersModal" class="vendor-modal-overlay" onclick="closeVendorModal(event)">
    <div class="po-modal-container" onclick="event.stopPropagation()">
        <div class="po-modal-header">
            <h4 id="vendorModalTitle">Vendor Orders</h4>
            <button class="po-modal-close" onclick="closeVendorModal()">&times;</button>
        </div>
        <div class="po-modal-body" id="vendorModalContent">
            <div class="modal-loading">
                <div class="spinner"></div>
                <p>Loading vendor orders...</p>
            </div>
        </div>
    </div>
</div>

@endsection
@section('PageContent')
@php
    $qty = $po->flatMap->polines->sum('quantity');
    $rqty = $po->flatMap->polines->sum('received_quantity');
    $bqty = $po->flatMap->polines->sum('balance_quantity');
    $per = $qty > 0 ? round(($rqty / $qty) * 100, 1) : 0;

    // Calculate age distribution
    $age0_30 = collect($poReport)->where('age_bucket', '0-30')->count();
    $age31_60 = collect($poReport)->where('age_bucket', '31-60')->count();
    $age61_90 = collect($poReport)->where('age_bucket', '61-90')->count();
    $age90plus = collect($poReport)->where('age_bucket', '90+')->count();
    $totalPending = count($poReport);
    $maxAge = max($age0_30, $age31_60, $age61_90, $age90plus, 1);

    // Top vendors by PO count
    $topVendors = $po->groupBy('vendor_id')->map(function($items, $vendorId) {
        return [
            'vendor_id' => $vendorId,
            'name' => $items->first()->vendor->name ?? 'Unknown',
            'count' => $items->count(),
            'total' => $items->flatMap->polines->sum('total_price')
        ];
    })->sortByDesc('count')->take(5);

    // SVG circle calculation
    $radius = 88;
    $circumference = 2 * 3.14159 * $radius;
    $offset = $circumference - ($per / 100) * $circumference;

    // Date presets
    $today = date('Y-m-d');
    $thisWeekStart = date('Y-m-d', strtotime('monday this week'));
    $thisWeekEnd = date('Y-m-d', strtotime('sunday this week'));
    $thisMonthStart = date('Y-m-01');
    $thisMonthEnd = date('Y-m-t');
    $lastMonthStart = date('Y-m-01', strtotime('first day of last month'));
    $lastMonthEnd = date('Y-m-t', strtotime('last day of last month'));
    $thisQuarterStart = date('Y-m-01', strtotime('first day of ' . ceil(date('n')/3)*3-2 . ' month'));
    $thisYearStart = date('Y-01-01');

    // Determine active filter
    $activeFilter = 'custom';
    if ($fromdate == $today && $todate == $today) $activeFilter = 'today';
    elseif ($fromdate == $thisWeekStart && $todate == $thisWeekEnd) $activeFilter = 'week';
    elseif ($fromdate == $thisMonthStart && $todate == $thisMonthEnd) $activeFilter = 'month';
    elseif ($fromdate == $lastMonthStart && $todate == $lastMonthEnd) $activeFilter = 'lastmonth';
    elseif ($fromdate == $thisYearStart && $todate == $today) $activeFilter = 'year';
@endphp

<div class="dashboard-container">
    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-title"><i class="bx bx-filter-alt" style="margin-right: 6px;"></i> Filter by Date</div>

        <!-- Quick Filter Buttons -->
        <div class="quick-filters">
            <a href="{{url('/dashboard/po/new')}}?from={{$today}}&to={{$today}}"
               class="quick-filter-btn {{$activeFilter == 'today' ? 'active' : ''}}">
                Today
            </a>
            <a href="{{url('/dashboard/po/new')}}?from={{$thisWeekStart}}&to={{$thisWeekEnd}}"
               class="quick-filter-btn {{$activeFilter == 'week' ? 'active' : ''}}">
                This Week
            </a>
            <a href="{{url('/dashboard/po/new')}}?from={{$thisMonthStart}}&to={{$thisMonthEnd}}"
               class="quick-filter-btn {{$activeFilter == 'month' ? 'active' : ''}}">
                This Month
            </a>
            <a href="{{url('/dashboard/po/new')}}?from={{$lastMonthStart}}&to={{$lastMonthEnd}}"
               class="quick-filter-btn {{$activeFilter == 'lastmonth' ? 'active' : ''}}">
                Last Month
            </a>
            <a href="{{url('/dashboard/po/new')}}?from={{$thisYearStart}}&to={{$today}}"
               class="quick-filter-btn {{$activeFilter == 'year' ? 'active' : ''}}">
                This Year
            </a>
        </div>

        <!-- Custom Date Range -->
        <form action="{{url('/dashboard/po/new')}}" method="post" id="dateFilterForm">
            {{csrf_field()}}
            <div class="date-range-row">
                <div class="date-input-group">
                    <label>From</label>
                    <input type="date" name="fromdate" id="fromdate" value="{{$fromdate}}">
                </div>
                <div class="date-input-group">
                    <label>To</label>
                    <input type="date" name="todate" id="todate" value="{{$todate}}">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn-apply">
                        <i class="bx bx-search"></i> Apply
                    </button>
                    <a href="{{url('/dashboard/po/new')}}" class="btn-clear">
                        <i class="bx bx-refresh"></i> Reset
                    </a>
                </div>
                <div class="current-range">
                    <i class="bx bx-calendar"></i>
                    <span>{{date('d M Y', strtotime($fromdate))}} - {{date('d M Y', strtotime($todate))}}</span>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="summary-bar">
        <div class="summary-item vendors">
            <div class="summary-number">{{$vendor}}</div>
            <div class="summary-label">Active Vendors</div>
            <div class="summary-icon"><i class="bx bx-store-alt"></i></div>
        </div>
        <div class="summary-item orders">
            <div class="summary-number">{{$po->count()}}</div>
            <div class="summary-label">Purchase Orders</div>
            <div class="summary-icon"><i class="bx bx-file"></i></div>
        </div>
        <div class="summary-item received">
            <div class="summary-number">{{$grn->count()}}</div>
            <div class="summary-label">Goods Received</div>
            <div class="summary-icon"><i class="bx bx-check-circle"></i></div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="main-grid">
        <!-- Left Column -->
        <div>
            <!-- Fulfillment Meter -->
            <div class="card-modern fulfillment-section">
                <div class="meter-container">
                    <div class="meter-circle">
                        <svg viewBox="0 0 200 200">
                            <circle class="bg" cx="100" cy="100" r="{{$radius}}"/>
                            <circle class="progress" cx="100" cy="100" r="{{$radius}}"
                                stroke-dasharray="{{$circumference}}"
                                stroke-dashoffset="{{$offset}}"/>
                        </svg>
                        <div class="meter-value">
                            <div class="meter-percent">{{$per}}<span>%</span></div>
                            <div class="meter-label">Fulfilled</div>
                        </div>
                    </div>
                    <div class="qty-stats">
                        <div class="qty-stat">
                            <div class="qty-stat-value ordered">{{number_format($qty)}}</div>
                            <div class="qty-stat-label">Ordered</div>
                        </div>
                        <div class="qty-stat">
                            <div class="qty-stat-value received">{{number_format($rqty)}}</div>
                            <div class="qty-stat-label">Received</div>
                        </div>
                        <div class="qty-stat">
                            <div class="qty-stat-value pending">{{number_format($bqty)}}</div>
                            <div class="qty-stat-label">Pending</div>
                        </div>
                    </div>
                </div>

                <!-- Age Distribution -->
                <div class="age-distribution">
                    <div class="age-title">Pending Orders by Age</div>
                    <div class="age-bars">
                        <div class="age-bar-row">
                            <div class="age-bar-label">0-30 days</div>
                            <div class="age-bar-track">
                                <div class="age-bar-fill green" style="width: {{$totalPending > 0 ? ($age0_30/$maxAge)*100 : 0}}%">
                                    @if($age0_30 > 0) On Track @endif
                                </div>
                            </div>
                            <div class="age-bar-count">{{$age0_30}}</div>
                        </div>
                        <div class="age-bar-row">
                            <div class="age-bar-label">31-60 days</div>
                            <div class="age-bar-track">
                                <div class="age-bar-fill yellow" style="width: {{$totalPending > 0 ? ($age31_60/$maxAge)*100 : 0}}%">
                                    @if($age31_60 > 0) Delayed @endif
                                </div>
                            </div>
                            <div class="age-bar-count">{{$age31_60}}</div>
                        </div>
                        <div class="age-bar-row">
                            <div class="age-bar-label">61-90 days</div>
                            <div class="age-bar-track">
                                <div class="age-bar-fill orange" style="width: {{$totalPending > 0 ? ($age61_90/$maxAge)*100 : 0}}%">
                                    @if($age61_90 > 0) At Risk @endif
                                </div>
                            </div>
                            <div class="age-bar-count">{{$age61_90}}</div>
                        </div>
                        <div class="age-bar-row">
                            <div class="age-bar-label">90+ days</div>
                            <div class="age-bar-track">
                                <div class="age-bar-fill red" style="width: {{$totalPending > 0 ? ($age90plus/$maxAge)*100 : 0}}%">
                                    @if($age90plus > 0) Critical @endif
                                </div>
                            </div>
                            <div class="age-bar-count">{{$age90plus}}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Orders Table -->
            <div class="card-modern table-section">
                <div class="card-header-modern">
                    <h5 class="card-title-modern">Pending Purchase Orders</h5>
                    <span style="font-size: 13px; color: #64748b;">{{$totalPending}} orders awaiting delivery</span>
                </div>
                <table class="po-table">
                    <thead>
                        <tr>
                            <th>PO #</th>
                            <th>Date</th>
                            <th>Vendor</th>
                            <th>Pending Qty</th>
                            <th>Days</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(collect($poReport)->take(10) as $item)
                            @php
                                $statusClass = match($item['age_bucket']) {
                                    '0-30' => 'on-track',
                                    '31-60' => 'delayed',
                                    '61-90' => 'at-risk',
                                    default => 'critical'
                                };
                                $statusText = match($item['age_bucket']) {
                                    '0-30' => 'On Track',
                                    '31-60' => 'Delayed',
                                    '61-90' => 'At Risk',
                                    default => 'Critical'
                                };
                            @endphp
                            <tr>
                                <td><span class="po-id clickable" onclick="openPoDetails('{{$item['po_no']}}')" style="cursor:pointer;text-decoration:underline;">{{$item['po_no']}}</span></td>
                                <td>{{date('d M Y', strtotime($item['po_date']))}}</td>
                                <td><span class="vendor-name">{{$item['vendor']}}</span></td>
                                <td>{{number_format($item['balance_quantity'])}}</td>
                                <td>{{$item['age_days']}}</td>
                                <td><span class="status-pill {{$statusClass}}">{{$statusText}}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="bx bx-check-double"></i>
                                        <p>All purchase orders are fulfilled!</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div>
            <!-- Top Vendors -->
            <div class="sidebar-card">
                <div class="sidebar-header">
                    <h5 class="sidebar-title">Top Vendors</h5>
                </div>
                <div class="sidebar-body">
                    <div class="vendor-list">
                        @forelse($topVendors as $v)
                            <div class="vendor-item clickable-box" onclick="openVendorOrders('{{$v['name']}}', '{{$v['vendor_id']}}', '{{$fromdate}}', '{{$todate}}')" style="cursor:pointer;">
                                <div class="vendor-avatar">{{strtoupper(substr($v['name'], 0, 2))}}</div>
                                <div class="vendor-info">
                                    <div class="vendor-info-name">{{$v['name']}}</div>
                                    <div class="vendor-info-orders">{{$v['count']}} orders</div>
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 20px; color: #94a3b8;">
                                No vendor data
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="sidebar-card">
                <div class="sidebar-header">
                    <h5 class="sidebar-title">Quick Actions</h5>
                </div>
                <div class="sidebar-body">
                    <div class="quick-actions">
                        <a href="{{url('/addpo')}}" class="quick-action blue">
                            <i class="bx bx-plus"></i>
                            <span>Create New PO</span>
                        </a>
                        <a href="{{url('/addgrn')}}" class="quick-action green">
                            <i class="bx bx-package"></i>
                            <span>Record GRN</span>
                        </a>
                        <a href="{{url('/report/po')}}" class="quick-action purple">
                            <i class="bx bx-file"></i>
                            <span>View PO Report</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PO Details Modal -->
<div id="poDetailModal" class="po-modal-overlay" onclick="closePoModal(event)">
    <div class="po-modal-container" onclick="event.stopPropagation()">
        <div class="po-modal-header">
            <h4 id="modalPoNumber">PO Details</h4>
            <button class="po-modal-close" onclick="closePoModal()">&times;</button>
        </div>
        <div class="po-modal-body" id="modalContent">
            <div class="modal-loading">
                <div class="spinner"></div>
                <p>Loading PO details...</p>
            </div>
        </div>
    </div>
</div>

<style>
/* Modal Styles */
.po-modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    animation: fadeIn 0.2s ease;
}

.po-modal-overlay.active {
    display: flex;
}

.po-modal-container {
    background: #fff;
    border-radius: 16px;
    width: 90%;
    max-width: 800px;
    max-height: 85vh;
    overflow: hidden;
    box-shadow: 0 25px 50px rgba(0,0,0,0.25);
    animation: slideUp 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { transform: translateY(30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.po-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc; border-bottom: 1px solid #e2e8f0;
}

.po-modal-header h4 {
    margin: 0;
    color: #1e293b;
    font-size: 1.25rem;
}

.po-modal-close {
    background: #e2e8f0;
    border: none;
    color: #64748b;
    font-size: 24px;
    cursor: pointer;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.po-modal-close:hover {
    background: #cbd5e1;
    color: #334155;
}

.po-modal-body {
    padding: 25px;
    overflow-y: auto;
    max-height: calc(85vh - 80px);
}

.modal-loading {
    text-align: center;
    padding: 40px;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #e2e8f0;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 15px;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.po-detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.detail-card {
    background: #f8fafc;
    padding: 18px;
    border-radius: 12px;
    border-left: 4px solid #667eea;
}

.detail-label {
    font-size: 0.75rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
}

.detail-value {
    font-size: 1.1rem;
    color: #1e293b;
    font-weight: 600;
}

.line-items-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.line-items-table th {
    background: #f1f5f9;
    padding: 12px;
    text-align: left;
    font-size: 0.8rem;
    color: #475569;
    text-transform: uppercase;
    font-weight: 600;
}

.line-items-table td {
    padding: 12px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
}

.line-items-table tr:hover {
    background: #f8fafc;
}

.section-title {
    font-size: 1rem;
    color: #334155;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e2e8f0;
}

.error-message {
    text-align: center;
    padding: 30px;
    color: #dc2626;
}

.error-message i {
    font-size: 48px;
    margin-bottom: 10px;
}

.line-items-search {
    margin-bottom: 15px;
}

.line-items-search input {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.9rem;
    outline: none;
    transition: border-color 0.2s;
}

.line-items-search input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.line-items-search input::placeholder {
    color: #94a3b8;
}

.line-items-table tr.hidden {
    display: none;
}

.no-results {
    text-align: center;
    padding: 20px;
    color: #94a3b8;
}


.clickable-box {
    transition: all 0.2s ease;
    border: 2px solid transparent;
}

.clickable-box:hover {
    background: #f1f5f9;
    border-color: #667eea;
    border-radius: 10px;
    transform: translateX(5px);
}

/* Vendor Orders Modal */
.vendor-modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    animation: fadeIn 0.2s ease;
}

.vendor-modal-overlay.active {
    display: flex;
}

.vendor-orders-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.vendor-orders-table th {
    background: #f1f5f9;
    padding: 12px;
    text-align: left;
    font-size: 0.8rem;
    color: #475569;
    text-transform: uppercase;
    font-weight: 600;
}

.vendor-orders-table td {
    padding: 12px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
}

.vendor-orders-table tr:hover {
    background: #f8fafc;
}

.vendor-orders-table .po-link {
    color: #667eea;
    cursor: pointer;
    text-decoration: underline;
}

.vendor-orders-table .po-link:hover {
    color: #4f46e5;
}

</style>

<!-- Vendor Orders Modal -->
<div id="vendorOrdersModal" class="vendor-modal-overlay" onclick="closeVendorModal(event)">
    <div class="po-modal-container" onclick="event.stopPropagation()">
        <div class="po-modal-header">
            <h4 id="vendorModalTitle">Vendor Orders</h4>
            <button class="po-modal-close" onclick="closeVendorModal()">&times;</button>
        </div>
        <div class="po-modal-body" id="vendorModalContent">
            <div class="modal-loading">
                <div class="spinner"></div>
                <p>Loading vendor orders...</p>
            </div>
        </div>
    </div>
</div>

@endsection
@section('ScriptContent')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate the progress circle on load
    const progressCircle = document.querySelector('.meter-circle .progress');
    if (progressCircle) {
        progressCircle.style.strokeDashoffset = '{{$circumference}}';
        setTimeout(() => {
            progressCircle.style.strokeDashoffset = '{{$offset}}';
        }, 100);
    }

    // Auto-submit when both dates are filled
    const fromDate = document.getElementById('fromdate');
    const toDate = document.getElementById('todate');

    if (fromDate && toDate) {
        toDate.addEventListener('change', function() {
            if (fromDate.value && toDate.value) {
                // Optional: auto-submit form
                // document.getElementById('dateFilterForm').submit();
            }
        });
    }
});

function openPoDetails(poNo) {
    const modal = document.getElementById('poDetailModal');
    const modalContent = document.getElementById('modalContent');
    const modalTitle = document.getElementById('modalPoNumber');

    modal.classList.add('active');
    modalTitle.textContent = 'PO: ' + poNo;
    modalContent.innerHTML = '<div class="modal-loading"><div class="spinner"></div><p>Loading PO details...</p></div>';

    // Fetch PO details
    fetch('{{url("/api/po/details")}}/' + poNo)
        .then(response => {
            if (!response.ok) throw new Error('PO not found');
            return response.json();
        })
        .then(data => {
            renderPoDetails(data);
        })
        .catch(error => {
            modalContent.innerHTML = '<div class="error-message"><i class="bx bx-error-circle"></i><p>' + error.message + '</p></div>';
        });
}

function renderPoDetails(data) {
    const modalContent = document.getElementById('modalContent');

    // Helper function to safely display values
    function safeValue(val) {
        if (val === null || val === undefined) return '-';
        if (typeof val === 'object') return JSON.stringify(val);
        return String(val);
    }

    function safeNumber(val) {
        let num = parseFloat(val);
        return isNaN(num) ? '0.00' : num.toFixed(2);
    }

    let lineItemsHtml = '';
    if (data.line_items && data.line_items.length > 0) {
        lineItemsHtml = '<h5 class="section-title">Line Items</h5>' +
            '<div class="line-items-search"><input type="text" id="productSearch" placeholder="Search products..." onkeyup="filterProducts()"></div>' +
            '<table class="line-items-table" id="lineItemsTable"><thead><tr><th>Product</th><th>Qty</th><th>Received</th><th>Balance</th><th>Unit Price</th><th>Total</th></tr></thead><tbody>';
        data.line_items.forEach(function(item) {
            lineItemsHtml += '<tr>' +
                '<td>' + safeValue(item.product) + '</td>' +
                '<td>' + safeValue(item.quantity) + '</td>' +
                '<td>' + safeValue(item.received) + '</td>' +
                '<td>' + safeValue(item.balance) + '</td>' +
                '<td>' + safeNumber(item.unit_price) + '</td>' +
                '<td>' + safeNumber(item.total) + '</td>' +
                '</tr>';
        });
        lineItemsHtml += '</tbody></table>';
    } else {
        lineItemsHtml = '<p style="text-align:center;color:#94a3b8;">No line items found</p>';
    }

    modalContent.innerHTML =
        '<div class="po-detail-grid">' +
            '<div class="detail-card"><div class="detail-label">PO Number</div><div class="detail-value">' + safeValue(data.po_no) + '</div></div>' +
            '<div class="detail-card"><div class="detail-label">PO Date</div><div class="detail-value">' + safeValue(data.po_date) + '</div></div>' +
            '<div class="detail-card"><div class="detail-label">Vendor</div><div class="detail-value">' + safeValue(data.vendor) + '</div></div>' +
            '<div class="detail-card"><div class="detail-label">Status</div><div class="detail-value">' + safeValue(data.status) + '</div></div>' +
            '<div class="detail-card"><div class="detail-label">Delivery Date</div><div class="detail-value">' + safeValue(data.delivery_date) + '</div></div>' +
            '<div class="detail-card"><div class="detail-label">Total Amount</div><div class="detail-value">' + safeValue(data.currency || 'AED') + ' ' + safeNumber(data.total_amount) + '</div></div>' +
        '</div>' +
        lineItemsHtml;
}

function closePoModal(event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById('poDetailModal').classList.remove('active');
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePoModal();
        closeVendorModal();
    }
});


function openVendorOrders(vendorName, vendorId, fromDate, toDate) {
    const modal = document.getElementById('vendorOrdersModal');
    const modalContent = document.getElementById('vendorModalContent');
    const modalTitle = document.getElementById('vendorModalTitle');

    modal.classList.add('active');
    modalTitle.textContent = 'Orders: ' + vendorName;
    modalContent.innerHTML = '<div class="modal-loading"><div class="spinner"></div><p>Loading vendor orders...</p></div>';

    // Fetch vendor orders
    fetch('{{url("/api/vendor/orders")}}/' + vendorId + '?from=' + fromDate + '&to=' + toDate)
        .then(response => {
            if (!response.ok) throw new Error('No orders found');
            return response.json();
        })
        .then(data => {
            renderVendorOrders(data, vendorName);
        })
        .catch(error => {
            modalContent.innerHTML = '<div class="error-message"><i class="bx bx-error-circle"></i><p>' + error.message + '</p></div>';
        });
}

function renderVendorOrders(data, vendorName) {
    const modalContent = document.getElementById('vendorModalContent');

    if (!data.orders || data.orders.length === 0) {
        modalContent.innerHTML = '<div class="error-message"><i class="bx bx-package"></i><p>No orders found for this vendor</p></div>';
        return;
    }

    let tableHtml = '<table class="vendor-orders-table"><thead><tr><th>PO Number</th><th>Date</th><th>Items</th><th>Total Qty</th><th>Balance</th><th>Status</th></tr></thead><tbody>';

    data.orders.forEach(function(order) {
        let statusClass = order.balance_qty > 0 ? 'color:#f59e0b;' : 'color:#10b981;';
        let statusText = order.balance_qty > 0 ? 'Pending' : 'Completed';
        tableHtml += '<tr>' +
            '<td><span class="po-link" onclick="closeVendorModal(); setTimeout(function(){ openPoDetails(\'' + order.po_no + '\'); }, 300);">' + order.po_no + '</span></td>' +
            '<td>' + order.po_date + '</td>' +
            '<td>' + order.item_count + '</td>' +
            '<td>' + order.total_qty + '</td>' +
            '<td>' + order.balance_qty + '</td>' +
            '<td style="' + statusClass + 'font-weight:600;">' + statusText + '</td>' +
            '</tr>';
    });

    tableHtml += '</tbody></table>';

    modalContent.innerHTML = '<div style="margin-bottom:15px;"><strong>Total Orders:</strong> ' + data.orders.length + '</div>' + tableHtml;
}


function filterProducts() {
    const searchInput = document.getElementById('productSearch');
    const filter = searchInput.value.toLowerCase();
    const table = document.getElementById('lineItemsTable');
    const tbody = table.getElementsByTagName('tbody')[0];
    const rows = tbody.getElementsByTagName('tr');

    let visibleCount = 0;

    for (let i = 0; i < rows.length; i++) {
        const productCell = rows[i].getElementsByTagName('td')[0];
        if (productCell) {
            const productText = productCell.textContent || productCell.innerText;
            if (productText.toLowerCase().indexOf(filter) > -1) {
                rows[i].classList.remove('hidden');
                visibleCount++;
            } else {
                rows[i].classList.add('hidden');
            }
        }
    }

    // Show no results message if needed
    let noResults = document.getElementById('noResultsRow');
    if (visibleCount === 0 && filter !== '') {
        if (!noResults) {
            const newRow = tbody.insertRow();
            newRow.id = 'noResultsRow';
            newRow.innerHTML = '<td colspan="6" class="no-results">No products found matching "' + filter + '"</td>';
        }
    } else if (noResults) {
        noResults.remove();
    }
}

function closeVendorModal(event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById('vendorOrdersModal').classList.remove('active');
}

</script>
@endsection
