@extends('layouts.master')
@section('title','Home')
@section('StyleContent')
@endsection
@section('PageContent')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Home Page</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form  action="{{url('/dashboard/po')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}  
                    <div class="row">
                        <div class="col-lg-3">
                            <div>
                                <label>From Date</label>
                                <input class="form-control" type="date" value="{{$fromdate}}" name="fromdate" id="fromdate">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div>
                                <label>To Date</label>
                                <input class="form-control" type="date" value="{{$todate}}" name="todate" id="todate">
                            </div>
                        </div>
                        <div class="col-lg-2 mt-3">
                            <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Search</button>
                            <button type="button" onclick="refreshDashboard()" class="btn btn-info waves-effect waves-light me-2 mt-2">Refresh</button>
                        </div>
                         
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Total Vendors</p>
                        <h4 class="mb-0" id="total_count">{{$vendor}}</h4>
                    </div>

                    <div class="flex-shrink-0 align-self-center">
                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                            <span class="avatar-title">
                                <i class="bx bxs-grid-alt font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <input type="hidden" name="countdown" id="countdown" value="10">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Total No of PO</p>
                        <h4 class="mb-0" id="new_count">{{$po->count()}}</h4>
                    </div>

                    <div class="flex-shrink-0 align-self-center ">
                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="fas fa-hand-holding-usd font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Total No of GRN</p>
                        <h4 class="mb-0" id="processing_count">{{$grn->count()}}</h4>
                    </div>

                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="mdi mdi-cart-arrow-down font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card overflow-hidden">
            <div class="accordion-item">
            <h2 class="accordion-header" id="flush-aftercadmissed_summary">
                <button class="accordion-button font-size-16 ps-3" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseaftercadmissed_summary" aria-expanded="true" aria-controls="flush-collapseaftercadmissed_summary">
                PO - GRN Quantity
                </button>
            </h2>
                <div id="flush-collapseaftercadmissed_summary" class="accordion-collapse collapse show" aria-labelledby="flush-aftercadmissed_summary">
                    <div class="card-body">
                        @php
                            $qty=$po->flatMap->polines->sum('quantity');
                            $rqty=$po->flatMap->polines->sum('received_quantity');
                            $bqty=$po->flatMap->polines->sum('balance_quantity');
                            $per = $qty > 0 ? ($rqty / $qty) * 100 : 0;
                        @endphp
                        <div class="row">
                            <div class="col-4">
                                <h5 class="font-size-15 text-truncate">{{$qty}}</h5>
                                <p class="text-muted mb-0 text-truncate">Order Quantity</p>
                            </div>
                            <div class="col-4">
                                <h5 class="font-size-15 text-truncate">{{$rqty}}</h5>
                                <p class="text-muted mb-0 text-truncate">Received Quantity</p>
                            </div>
                            <div class="col-4">
                                <h5 class="font-size-15 text-truncate">{{$bqty}}</h5>
                                <p class="text-muted mb-0 text-truncate">Balance Quantity</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="progress progress-xl">
                                <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: {{$per}}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <h4 class="mt-2">PO-GRN Ageing Report</h4>
        <div class="card-body">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th>PO No</th>
                        <th>PO Date</th>
                        <th>Vendor</th>
                        <th>Balance Qty</th>
                        <th>Age (Days)</th>
                        <th>Age Bucket</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($poReport as $po)
                        <tr>
                            <td>{{ $po['po_no'] }}</td>
                            <td>{{ date('d-m-Y',strtotime($po['po_date'])) }}</td>
                            <td>{{ $po['vendor'] }}</td>
                            <td>{{ $po['balance_quantity'] }}</td>
                            <td>{{ $po['age_days'] }}</td>
                            <td>{{ $po['age_bucket'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
</div>

</div>
<!-- <div class="row">
<div class="card">
        <div class="card-body">

        </div>
    </div>
</div> -->
@endsection
@section('ScriptContent')
<script type="text/javascript">

function refreshDashboard() {

}
</script>
@endsection