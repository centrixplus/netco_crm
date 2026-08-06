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
               
                @if(App\Models\Menurole::getMenuRole($role_id,25)==1)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                    >
                        <i class="bx bxs-user-pin me-2"></i><span key="t-dashboards">UMS</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                        @if(App\Models\Menurole::getMenuRole($role_id,17)==1)
                                <a href="{{url('/user')}}" class="dropdown-item"></i><span key="t-chat">Users</span> </a>
                        @endif
                        
                    </div>
                </li>
                @endif

                @if(App\Models\Menurole::getMenuRole($role_id,11)==1)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                        >
                            <i class="bx bxs-copy me-2"></i><span key="t-dashboards">TMS</span> <div class="arrow-down"></div>
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
                            @if(App\Models\Menurole::getMenuRole($role_id,28)==1)
                                <a href="{{url('/group')}}" class="dropdown-item"></i><span key="t-chat">Group</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,15)==1)
                                <a href="{{url('/category')}}" class="dropdown-item"></i><span key="t-chat">Category</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,16)==1)
                                <a href="{{url('/subcategory')}}" class="dropdown-item"></i><span key="t-chat">Sub Category</span> </a>
                            @endif

                            @if(App\Models\Menurole::getMenuRole($role_id,29)==1)
                                <a href="{{url('/uom')}}" class="dropdown-item"></i><span key="t-chat">UOM</span> </a>
                            @endif

                            @if(App\Models\Menurole::getMenuRole($role_id,29)==1)
                                <a href="{{url('/discount')}}" class="dropdown-item"></i><span key="t-chat">Discount</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,30)==1)
                                <a href="{{url('/color')}}" class="dropdown-item"></i><span key="t-chat">Movement</span> </a>
                            @endif

                            @if(App\Models\Menurole::getMenuRole($role_id,30)==1)
                                <a href="{{url('/size')}}" class="dropdown-item"></i><span key="t-chat">Size</span> </a>
                            @endif

                            @if(App\Models\Menurole::getMenuRole($role_id,31)==1)
                                <a href="{{url('/packagetype')}}" class="dropdown-item"></i><span key="t-chat">PackageType</span> </a>
                            @endif

                            @if(App\Models\Menurole::getMenuRole($role_id,18)==1)
                                <a href="{{url('/warehousepersion')}}" class="dropdown-item"></i><span key="t-chat">Warehouse Person</span> </a>
                            @endif

                            @if(App\Models\Menurole::getMenuRole($role_id,37)==1)
                                <a href="{{url('/country')}}" class="dropdown-item"></i><span key="t-chat">Country</span> </a>
                            @endif

                            @if(App\Models\Menurole::getMenuRole($role_id,38)==1)
                                <a href="{{url('/company')}}" class="dropdown-item"></i><span key="t-chat">Company</span> </a>
                            @endif

                            @if(App\Models\Menurole::getMenuRole($role_id,39)==1)
                                <a href="{{url('/branch')}}" class="dropdown-item"></i><span key="t-chat">Branch</span> </a>
                            @endif

                            @if(App\Models\Menurole::getMenuRole($role_id,55)==1)
                                <a href="{{url('/store')}}" class="dropdown-item"></i><span key="t-chat">Store</span> </a>
                            @endif

                            @if(App\Models\Menurole::getMenuRole($role_id,40)==1)
                                <a href="{{url('/paymenttype')}}" class="dropdown-item"></i><span key="t-chat">Payment Type</span> </a>
                            @endif

                            @if(App\Models\Menurole::getMenuRole($role_id,41)==1)
                                <a href="{{url('/paymentbank')}}" class="dropdown-item"></i><span key="t-chat">Payment Bank</span> </a>
                            @endif

                            @if(App\Models\Menurole::getMenuRole($role_id,35)==1)
                                <a href="{{url('/settings')}}" class="dropdown-item"></i><span key="t-chat">Settings</span> </a>
                            @endif
                        </div>
                    </li>
                @endif

                @if(App\Models\Menurole::getMenuRole($role_id,2)==1)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                    >
                        <i class="fas fa-donate me-2"></i><span key="t-dashboards">SMS</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                        @if(App\Models\Menurole::getMenuRole($role_id,26)==1)
                            <a href="{{url('/enquiry')}}" class="dropdown-item"><span key="t-chat">Create Enquiry</span> </a>
                        @endif
                        @if(App\Models\Menurole::getMenuRole($role_id,27)==1)
                            <a href="{{url('/enquiryrequestlist')}}" class="dropdown-item"></i><span key="t-chat">Enquiry List</span> </a>
                        @endif
                        @if(App\Models\Menurole::getMenuRole($role_id,34)==1)
                            <a href="{{url('/quotationlist')}}" class="dropdown-item"></i><span key="t-chat">Quotation List</span> </a>

                            <a href="{{url('/convertedquote')}}" class="dropdown-item"></i><span key="t-chat">Sale Quotation</span> </a>
                        @endif
                            


                        @if(App\Models\Menurole::getMenuRole($role_id,51)==1)
                            <a href="{{url('/createinvoice')}}" class="dropdown-item"><span key="t-chat">Create Proforma Invoice</span> </a>
                        @endif
                        @if(App\Models\Menurole::getMenuRole($role_id,52)==1)
                            <a href="{{url('/invoice')}}" class="dropdown-item"><span key="t-chat">Proforma Invoice</span> </a>
                        @endif
<!--                         @if(App\Models\Menurole::getMenuRole($role_id,53)==1)
                            <a href="{{url('/approvedinvoice')}}" class="dropdown-item"><span key="t-chat">Approved Proforma Invoice</span> </a>
                        @endif -->


                        @if(App\Models\Menurole::getMenuRole($role_id,3)==1)
                            <a href="{{url('/sale/create')}}" class="dropdown-item"><span key="t-chat">Create Order</span> </a>
                        @endif
                        @if(App\Models\Menurole::getMenuRole($role_id,4)==1)
                            <a href="{{url('/sale')}}" class="dropdown-item"></i><span key="t-chat">Orders</span> </a>
                        @endif
                    </div>
                </li>
                @endif

                @if(App\Models\Menurole::getMenuRole($role_id,67)==1)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                    >
                        <i class="bx bxs-user-pin me-2"></i><span key="t-dashboards">QMS</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                        @if(App\Models\Menurole::getMenuRole($role_id,68)==1)
                                <a href="{{url('/quote')}}" class="dropdown-item"></i><span key="t-chat">Create Quote</span> </a>
                        @endif
                        @if(App\Models\Menurole::getMenuRole($role_id,69)==1)
                                <a href="{{url('/quotelist')}}" class="dropdown-item"></i><span key="t-chat">Quote List</span> </a>
                        @endif
                    </div>
                </li>
                @endif

                @if(App\Models\Menurole::getMenuRole($role_id,59)==1)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                    >
                        <i class="bx bx-store-alt me-2"></i><span key="t-dashboards">WMS</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                        @if(App\Models\Menurole::getMenuRole($role_id,5)==1)
                            <a href="{{url('/salestatus/2')}}" class="dropdown-item"></i><span key="t-chat">Processing</span> </a>
                        @endif
                        
                        @if(App\Models\Menurole::getMenuRole($role_id,57)==1)
                            <a href="{{url('/salestatus/9')}}" class="dropdown-item"></i><span key="t-chat">Ready To Delivery</span> </a>
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

                

                @if(App\Models\Menurole::getMenuRole($role_id,8)==1)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                    >
                        <i class="bx bx-data me-2"></i><span key="t-dashboards">IMS</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                        @if(App\Models\Menurole::getMenuRole($role_id,9)==1)
                            <a href="{{url('/products')}}" class="dropdown-item"></i><span key="t-chat">Product</span></a>
                        @endif
                        @if(App\Models\Menurole::getMenuRole($role_id,10)==1)
                            <a href="{{url('/stock')}}" class="dropdown-item"></i><span key="t-chat">Stock</span> </a>
                        @endif
                        @if(App\Models\Menurole::getMenuRole($role_id,60)==1)
                            <a href="{{url('/productQueryWindow')}}" class="dropdown-item"></i><span key="t-chat">Product Query Window</span> </a>
                        @endif
                    </div>
                </li>
                @endif

                 @if(App\Models\Menurole::getMenuRole($role_id,42)==1)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                        >
                            <i class="bx bx-receipt" me-2"></i><span key="t-dashboards">AMS</span> <div class="arrow-down"></div>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                            @if(App\Models\Menurole::getMenuRole($role_id,43)==1)
                                <a href="{{url('/receiptdashboard')}}" class="dropdown-item"><span key="t-chat">Dashboard</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,44)==1)
                                <a href="{{url('/receipt')}}" class="dropdown-item"><span key="t-chat">New Receipt</span> </a>
                            @endif
                             @if(App\Models\Menurole::getMenuRole($role_id,50)==1)
                                <a href="{{url('/newreceipt')}}" class="dropdown-item"><span key="t-chat">Authorization</span> </a>
                            @endif
                            @if(App\Models\Menurole::getMenuRole($role_id,45)==1)
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
                            @if(App\Models\Menurole::getMenuRole($role_id,49)==1)
                                <a href="{{url('/receipt/report')}}" class="dropdown-item"><span key="t-chat">Report</span> </a>
                            @endif
                        </div>
                    </li>
                @endif

                @if(App\Models\Menurole::getMenuRole($role_id,61)==1)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                    >
                        <i class="bx bxs-cart-alt me-2"></i><span key="t-dashboards">PMS</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                        @if(App\Models\Menurole::getMenuRole($role_id,63)==1)
                            <a href="{{url('/createpurchaserequest')}}" class="dropdown-item"></i><span key="t-chat">Create Purchase Request</span></a>
                        @endif
                        @if(App\Models\Menurole::getMenuRole($role_id,62)==1)
                            <a href="{{url('/purchaserequest')}}" class="dropdown-item"></i><span key="t-chat">Purchase Request List</span></a>
                        @endif
                    </div>
                </li>
                @endif

                @if(App\Models\Menurole::getMenuRole($role_id,19)==1)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button">
                        <i class="bx bx-layout me-2"></i><span key="t-layouts">RMS</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnav-layout">
                        <div class="dropdown">
                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-layout-verti" role="button">
                                <span key="t-vertical">SMS</span> <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="topnav-layout-verti">
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

                                 @if(App\Models\Menurole::getMenuRole($role_id,54)==1)
                                <a href="{{url('/report/pilosreport')}}" class="dropdown-item"><span key="t-chat">Proforma Invoice LOS</span> </a>
                                @endif
                                @if(App\Models\Menurole::getMenuRole($role_id,56)==1)
                                    <a href="{{url('/report/pisalereport')}}" class="dropdown-item"><span key="t-chat">Proforma Invoice Sale</span> </a>
                                @endif
                                @if(App\Models\Menurole::getMenuRole($role_id,58)==1)
                                    <a href="{{url('/report/draftinvoice')}}" class="dropdown-item"><span key="t-chat">Proforma Invoice Draft</span> </a>
                                @endif
                            </div>
                        </div>

                        <div class="dropdown">
                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-layout-hori" role="button">
                                <span key="t-horizontal">PMS</span> <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="topnav-layout-hori">
                                @if(App\Models\Menurole::getMenuRole($role_id,65)==1)
                                <a href="{{url('/report/productrequest')}}" class="dropdown-item"><span key="t-chat">Product Request</span> </a>
                                @endif
                                @if(App\Models\Menurole::getMenuRole($role_id,66)==1)
                                    <a href="{{url('/report/prdraftreport')}}" class="dropdown-item"><span key="t-chat">Draft Purchase Request</span> </a>
                                @endif
                            </div>
                        </div>

                        <div class="dropdown">
                            <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-layout-hori" role="button">
                                <span key="t-horizontal">QMS</span> <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="topnav-layout-hori">
                                @if(App\Models\Menurole::getMenuRole($role_id,70)==1)
                                <a href="{{url('/report/quote')}}" class="dropdown-item"><span key="t-chat">QMS Line Report</span> </a>
                                @endif
                            </div>
                        </div>

                        @if(App\Models\Menurole::getMenuRole($role_id,36)==1)
                            <a href="{{url('/report/uploadlogs')}}" class="dropdown-item"><span key="t-chat">Upload Log</span> </a>
                        @endif
                    </div>
                </li>
                @endif
                @if(App\Models\Menurole::getMenuRole($role_id,1)==1)
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                    >
                        <i class="bx bxs-dashboard me-2"></i><span key="t-dashboards">Dashboards</span> <div class="arrow-down"></div>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="topnav-dashboard">
                        <a href="{{url('/receiptdashboard')}}" class="dropdown-item"><span key="t-chat">Receipt Dashboard</span> </a>
                        <a href="{{url('/')}}" class="dropdown-item"><span key="t-chat">Sales Dashboard</span> </a>
                    </div>
                </li>
                @endif

                
            </ul>
        </div>
    </nav>
</div>
</div>
