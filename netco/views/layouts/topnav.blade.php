<div class="topnav">
<div class="container-fluid">
    <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

        <div class="collapse navbar-collapse" id="topnav-menu-content">
            <ul class="navbar-nav">
               
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                    >
                        <i class="bx bx-home-circle me-2"></i><span key="t-dashboards">Dashboards</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                        <a href="{{url('/dashboard')}}" class="dropdown-item"><span key="t-chat">Dashboard</span> </a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                    >
                        <i class="bx bxs-grid-alt me-2"></i><span key="t-dashboards">Order</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                        @if(Auth::user()->role!='warehouse')
                            <a href="{{url('/sale/create')}}" class="dropdown-item"><span key="t-chat">Create Order</span> </a>
                            <a href="{{url('/sale')}}" class="dropdown-item"></i><span key="t-chat">Orders</span> </a>
                        @endif
                        @if(Auth::user()->role=='warehouse')
                            <a href="{{url('/salestatus/2')}}" class="dropdown-item"></i><span key="t-chat">Processing</span> </a>
                            <a href="{{url('/salestatus/10')}}" class="dropdown-item"></i><span key="t-chat">Invoiced</span> </a>
                            <a href="{{url('/salestatus/3')}}" class="dropdown-item"></i><span key="t-chat">Delivered</span> </a>
                        @endif
                        
                    </div>
                </li>

                @if(Auth::user()->role=='admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                    >
                        <i class="bx bxs-product-hunt me-2"></i><span key="t-dashboards">Product</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                        <a href="{{url('/products')}}" class="dropdown-item"></i><span key="t-chat">Product</span> </a>
                        <a href="{{url('/stock')}}" class="dropdown-item"></i><span key="t-chat">Stock</span> </a>
                    </div>
                </li>

                
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                        >
                            <i class="bx bxs-copy me-2"></i><span key="t-dashboards">Master</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                            <a href="{{url('/location')}}" class="dropdown-item"></i><span key="t-chat">Location</span> </a>
                            <a href="{{url('/customer')}}" class="dropdown-item"><span key="t-chat">Customer</span> </a>
                            <a href="{{url('/brand')}}" class="dropdown-item"></i><span key="t-chat">Brand</span> </a>
                            <a href="{{url('/category')}}" class="dropdown-item"></i><span key="t-chat">Category</span> </a>
                            <a href="{{url('/subcategory')}}" class="dropdown-item"></i><span key="t-chat">Sub Category</span> </a>
                            <a href="{{url('/user')}}" class="dropdown-item"></i><span key="t-chat">Users</span> </a>
                            <a href="{{url('/warehousepersion')}}" class="dropdown-item"></i><span key="t-chat">Warehouse Person</span> </a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                        >
                            <i class="bx bxs-file" me-2"></i><span key="t-dashboards">Report</span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                            <a href="{{url('/report/sales')}}" class="dropdown-item"><span key="t-chat">Order</span> </a>
                            <a href="{{url('/report/productsales')}}" class="dropdown-item"></i><span key="t-chat">Part Order</span> </a>
                            <a href="{{url('/report/los')}}" class="dropdown-item"></i><span key="t-chat">Loss of Sales</span> </a>
                            <a href="{{url('/report/location')}}" class="dropdown-item"><span key="t-chat">Location Wise Sales</span> </a>
                            <a href="{{url('/report/status')}}" class="dropdown-item"><span key="t-chat">Order Status Log</span> </a>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
</div>
</div>
