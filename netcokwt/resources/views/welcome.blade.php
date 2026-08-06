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
                <form  action="{{url('/dashboard')}}" method="post" enctype="multipart/form-data">
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
                        <div class="col-lg-4">
                            <div class="alert alert-info">
                                The Records Will Refresh In <strong id="countdowntimer">10</strong> Seconds.
                            </div>
                        </div>
                         
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Total Orders</p>
                        <h4 class="mb-0" id="total_count">{{$sales_count->count()}}</h4>
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
    <div class="col-md-3">
        <input type="hidden" name="countdown" id="countdown" value="10">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">New Orders</p>
                        <h4 class="mb-0" id="new_count">{{$new->count()}}</h4>
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
    <div class="col-md-3">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Processing Orders</p>
                        <h4 class="mb-0" id="processing_count">{{$processing->count()}}</h4>
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
    <div class="col-md-3">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Delivered Orders</p>
                        <h4 class="mb-0" id="delivered_count">{{$delivered->count()}}</h4>
                    </div>

                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="mdi mdi-bike-fast font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Total Orders</p>
                        <h4 class="mb-0"><span id="total_amount">{{number_format( $sales_count->sum('grand_total'), 3, '.', '' )}}</span></h4>
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
    <div class="col-md-3">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">New Orders</p>
                        <h4 class="mb-0"><span id="new_amount">{{number_format( $new->sum('grand_total'), 3, '.', '' )}}</span></h4>
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
    <div class="col-md-3">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Processing Orders</p>
                        <h4 class="mb-0"><span id="processing_amount">{{number_format( $processing->sum('grand_total'), 3, '.', '' )}}</span></h4>
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
    <div class="col-md-3">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">Delivered Orders</p>
                        <h4 class="mb-0"><span id="delivered_amount">{{number_format( $delivered->sum('grand_total'), 3, '.', '' )}}</span></h4>
                    </div>

                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="mdi mdi-bike-fast font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@if(Auth::user()->role=='admin')
    <div class="col-md-3">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">PDC LOS</p>
                        <h4 class="mb-0"><span id="pdc_los">{{number_format( $los->sum('l_price'), 3, '.', '' )}}</span></h4>
                    </div>

                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="fas fa-sort-amount-down font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium">NETCO LOS</p>
                        <h4 class="mb-0"><span id="netco_los">{{number_format( $netco_los->sum('l_price'), 3, '.', '' )}}</span></h4>
                    </div>

                    <div class="flex-shrink-0 align-self-center">
                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                            <span class="avatar-title rounded-circle bg-primary">
                                <i class="fas fa-chart-area font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if(Auth::user()->role=='admin')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5>User Wise Sales</h5>
                <div id="user_sales">
                    {!!$user_sales!!}
                </div>
                
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
   <!--              <h5>Status Wise Sales</h5> -->
                <div id="status_sales">
                   
                </div>
                <h5>Brand Wise Sales</h5>
                <div id="brand_sales">
                    {!!$brand_sales!!}
                </div>
                
            </div>
        </div>
    </div>
</div>
@endif
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
$(document).ready(function() {

setInterval(function(){
    $("#countdown").val(10);
    var timeleft = $("#countdown").val(); 
    timeleft = parseInt(timeleft);
    //alert(timeleft);

   setInterval(function(){
    
    timeleft--;
    if(timeleft >= 0){        
    document.getElementById("countdowntimer").textContent = timeleft;
    }
    },1000);

}, 10000);
});

var timeleft = 10; 
var downloadTimer = setInterval(function(){
timeleft--;
document.getElementById("countdowntimer").textContent = timeleft;
if(timeleft <= 0)
    clearInterval(downloadTimer);
},1000);


function refreshDashboard() {
   var fromdate=$('#fromdate').val();
   var todate=$('#todate').val();

    $.ajax({ url: "{{url('/refreshDashboard')}}"+'/'+fromdate+'/'+todate,
        type: "get", cache: false, dataType: 'json',
        success: function (data) {
            $('#total_count').html(data.sales_count);
            $('#new_count').html(data.new_count);
            $('#processing_count').html(data.processing_count);
            $('#delivered_count').html(data.delivered_count);
            $('#total_amount').html(data.sales_amount);
            $('#new_amount').html(data.new_amount);
            $('#processing_amount').html(data.processing_amount);
            $('#delivered_amount').html(data.delivered_amount);

            $('#pdc_los').html(data.los_amount);
            $('#netco_los').html(data.netco_los_amount);
            $('#user_sales').html(data.user_sales);
            $('#status_sales').html(data.status_sales);
            $('#brand_sales').html(data.brand_sales);
            
        }
    })
}
</script>
@endsection