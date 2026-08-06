<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>
                    <li> <a href="{{url('/dashboard')}}" class="waves-effect"><i class="bx bxs-bar-chart-alt-2"></i><span key="t-chat">Dashboard</span> </a> </li>
                    <li> <a href="{{url('/sale/create')}}" class="waves-effect"><i class="bx bx-food-menu"></i><span key="t-chat">Create Order</span> </a> </li>
                    <li> <a href="{{url('/sale')}}" class="waves-effect"><i class="bx bxs-grid-alt"></i><span key="t-chat">Orders</span> </a> </li>
                    @if(Auth::user()->role=='admin')
                    <li class="menu-title" key="t-menu">Master</li>
                        <li> <a href="{{url('/customer')}}" class="waves-effect"><i class="bx bxs-user-detail"></i><span key="t-chat">Customer</span> </a> </li>
                        <li> <a href="{{url('/products')}}" class="waves-effect"><i class="bx bxl-product-hunt"></i><span key="t-chat">Parts</span> </a> </li>
                        <li> <a href="{{url('/user')}}" class="waves-effect"><i class="bx bx-user-circle"></i><span key="t-chat">Users</span> </a> </li>
                    <li class="menu-title" key="t-menu">Report</li>
                        <li> <a href="{{url('/report/sales')}}" class="waves-effect"><i class="bx bxs-file"></i><span key="t-chat">Orders</span> </a> </li>
                        <li> <a href="{{url('/report/productsales')}}" class="waves-effect"><i class="bx bxs-copy"></i><span key="t-chat">Part Order</span> </a> </li>
                     @endif
            </ul>
        </div>
    </div>
</div>