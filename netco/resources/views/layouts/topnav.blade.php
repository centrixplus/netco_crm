@if(Auth::user())
<?php
    $role=App\Models\Role::where('name',Auth::user()->role)->first();
    $role_id=0;
    if($role){
        $role_id=$role->id;
    }
?>
<div class="topnav">
<div class="container-fluid">
    <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

        <div class="collapse navbar-collapse" id="topnav-menu-content">
            <ul class="navbar-nav">
               
                @if(App\Models\Menurole::getMenuRole($role_id,1)==1)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                    >
                        <i class="bx bx-home-circle me-2"></i><span key="t-dashboards">Dashboards</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                        <a href="{{url('/dashboard')}}" class="dropdown-item"><span key="t-chat">Dashboard</span> </a>
                    </div>
                </li>
                @endif

                @if(App\Models\Menurole::getMenuRole($role_id,2)==1)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                    >
                        <i class="bx bxs-grid-alt me-2"></i><span key="t-dashboards">Order</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                        @if(App\Models\Menurole::getMenuRole($role_id,3)==1)
                            <a href="{{url('/sale/create')}}" class="dropdown-item"><span key="t-chat">Create Order</span> </a>
                        @endif
                        @if(App\Models\Menurole::getMenuRole($role_id,4)==1)
                            <a href="{{url('/sale')}}" class="dropdown-item"></i><span key="t-chat">Orders</span> </a>
                        @endif
                        @if(App\Models\Menurole::getMenuRole($role_id,5)==1)
                            <a href="{{url('/salestatus/2')}}" class="dropdown-item"></i><span key="t-chat">Processing</span> </a>
                        @endif
                        @if(App\Models\Menurole::getMenuRole($role_id,6)==1)
                            <a href="{{url('/salestatus/10')}}" class="dropdown-item"></i><span key="t-chat">Invoiced</span> </a>
                        @endif
                        @if(App\Models\Menurole::getMenuRole($role_id,7)==1)
                            <a href="{{url('/salestatus/3')}}" class="dropdown-item"></i><span key="t-chat">Delivered</span> </a>
                        @endif
                    </div>
                </li>
                @endif

                @if(App\Models\Menurole::getMenuRole($role_id,25)==1)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                    >
                        <i class="bx bxs-grid-alt me-2"></i><span key="t-dashboards">Enquiry</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                            @if(App\Models\Menurole::getMenuRole($role_id,26)==1)
                                <a href="{{url('/enquiry')}}" class="dropdown-item"><span key="t-chat">Create Enquiry</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,27)==1)
                                <a href="{{url('/enquiryrequestlist')}}" class="dropdown-item"></i><span key="t-chat">Enquiry List</span> </a>
                            @endif
                        
                    </div>
                </li>
                @endif

                @if(App\Models\Menurole::getMenuRole($role_id,8)==1)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                    >
                        <i class="bx bxs-product-hunt me-2"></i><span key="t-dashboards">Product</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                        @if(App\Models\Menurole::getMenuRole($role_id,9)==1)
                            <a href="{{url('/products')}}" class="dropdown-item"></i><span key="t-chat">Product</span></a>
                        @endif
                        @if(App\Models\Menurole::getMenuRole($role_id,10)==1)
                            <a href="{{url('/stock')}}" class="dropdown-item"></i><span key="t-chat">Stock</span> </a>
                        @endif
                    </div>
                </li>
                @endif
                
                @if(App\Models\Menurole::getMenuRole($role_id,11)==1)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                        >
                            <i class="bx bxs-copy me-2"></i><span key="t-dashboards">Master</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                            @if(App\Models\Menurole::getMenuRole($role_id,12)==1)
                                <a href="{{url('/location')}}" class="dropdown-item"></i><span key="t-chat">Location</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,13)==1)
                                <a href="{{url('/customer')}}" class="dropdown-item"><span key="t-chat">Customer</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,14)==1)
                                <a href="{{url('/brand')}}" class="dropdown-item"></i><span key="t-chat">Brand</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,15)==1)
                                <a href="{{url('/category')}}" class="dropdown-item"></i><span key="t-chat">Category</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,16)==1)
                                <a href="{{url('/subcategory')}}" class="dropdown-item"></i><span key="t-chat">Sub Category</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,17)==1)
                                <a href="{{url('/user')}}" class="dropdown-item"></i><span key="t-chat">Users</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,18)==1)
                                <a href="{{url('/warehousepersion')}}" class="dropdown-item"></i><span key="t-chat">Warehouse Person</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,38)==1)
                                <a href="{{url('/country')}}" class="dropdown-item"></i><span key="t-chat">Country</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,39)==1)
                                <a href="{{url('/company')}}" class="dropdown-item"></i><span key="t-chat">Company</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,40)==1)
                                <a href="{{url('/branch')}}" class="dropdown-item"></i><span key="t-chat">Branch</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,42)==1)
                                <a href="{{url('/paymenttype')}}" class="dropdown-item"></i><span key="t-chat">Payment Type</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,43)==1)
                                <a href="{{url('/paymentbank')}}" class="dropdown-item"></i><span key="t-chat">Payment bank</span> </a>
                            @endif
                        </div>
                    </li>
                @endif

                @if(App\Models\Menurole::getMenuRole($role_id,36)==1)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                        >
                            <i class="bx bx-receipt" me-2"></i><span key="t-dashboards">Receipt</span> <div class="arrow-down"></div>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                            @if(App\Models\Menurole::getMenuRole($role_id,44)==1)
                                <a href="{{url('/receiptdashboard')}}" class="dropdown-item"><span key="t-chat">Dashboard</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,37)==1)
                                <a href="{{url('/receipt')}}" class="dropdown-item"><span key="t-chat">New Receipt</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,46)==1)
                                <a href="{{url('/authorized')}}" class="dropdown-item"><span key="t-chat">Processing Receipt</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,46)==1)
                                <a href="{{url('/authorizedreceipt')}}" class="dropdown-item"><span key="t-chat">Posted Receipt</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,47)==1)
                                <a href="{{url('/reconciledreceipt')}}" class="dropdown-item"><span key="t-chat">Reconciled Receipt</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,48)==1)
                                <a href="{{url('/bouncedreceipt')}}" class="dropdown-item"><span key="t-chat">Bounced Receipt</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,45)==1)
                                <a href="{{url('/receipt/report')}}" class="dropdown-item"><span key="t-chat">Report</span> </a>
                            @endif
                        </div>
                    </li>
                @endif
                
                @if(App\Models\Menurole::getMenuRole($role_id,19)==1)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                        >
                            <i class="bx bxs-file" me-2"></i><span key="t-dashboards">Report</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                            @if(App\Models\Menurole::getMenuRole($role_id,20)==1)
                                <a href="{{url('/report/sales')}}" class="dropdown-item"><span key="t-chat">Order</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,21)==1)
                                <a href="{{url('/report/productsales')}}" class="dropdown-item"></i><span key="t-chat">Part Order</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,22)==1)
                                <a href="{{url('/report/los')}}" class="dropdown-item"></i><span key="t-chat">Loss of Sales</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,23)==1)
                                <a href="{{url('/report/location')}}" class="dropdown-item"><span key="t-chat">Location Wise Sales</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,24)==1)
                                <a href="{{url('/report/status')}}" class="dropdown-item"><span key="t-chat">Order Status Log</span> </a>
                            @endif
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
</div>
</div>
@endif