<div class="row">
    <span class="mb-3">
            @php $rproduct=''; @endphp @if($product->id!=$product->reference_id) @php $rproduct=App\Models\Product::find($product->reference_id);  @endphp
            @if($rproduct)<h4>Latest Part Number: <span class="text-success">{{$rproduct->name}}</span></h4> @endif
           @endif
    </span>

    <div class="col-lg-6 mt-2">
        <h5>Delaer Price Info</h5>
        <table class="table table-sm table-bordered">
            <thead >
                <tr class="table-warning">
                    <th>Dealer Price</th>
                    <th>Dealer Price 10 %</th>
                    <th>Dealer Price 20 %</th>
                    <th>Dealer Price 30 %</th>
                    <th>Dealer Price 40 %</th>
                    <th>Dealer Price 45 %</th>
                    <th class="table-danger">Dealer Cost</th>
                </tr>
            </thead>
            <tbody class="table-hover text-warning" style="font-size: 20px;">
                <tr>
                    <td><b>{{$product->dealer_price}}</b></td>
                    <td>{{App\Models\Product::caldiscPrice($product->dealer_price,'10')}}</td>
                    <td>{{App\Models\Product::caldiscPrice($product->dealer_price,'20')}}</td>
                    <td>{{App\Models\Product::caldiscPrice($product->dealer_price,'30')}}</td>
                    <td>{{App\Models\Product::caldiscPrice($product->dealer_price,'40')}}</td>
                    <td>{{App\Models\Product::caldiscPrice($product->dealer_price,'45')}}</td>
                    <td class="text-danger"><b>{{$product->dealer_cost}}</b></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-lg-3 mt-2">
        <h5>Price Info</h5>
        <table class="table table-sm table-bordered">
            <thead >
                <tr class="table-primary">
                    <th>RSP</th>
                    <th>COST</th>    
                </tr>
            </thead>
            <tbody class="table-hover text-primary" style="font-size: 20px;">
                <tr>
                    <td>{{$product->price}}</td>
                    <td>{{$product->min_price}}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-lg-4 mt-2 d-none">
        <h5 class="text-secondary">Dealer Pricelog List</h5>
        <table id="datatable-buttons" class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Price</th>
                    @if(Auth::user()->role=='admin')<th>Updated By</th>@endif
                </tr>
            </thead>           
            <tbody >
                <?php $x=1; ?>
                @foreach ($dealerpricelogs as $index)
                <tr >
                    <td>{{$x++}}</td>
                    <td>{{date('d M, Y H:i:s',strtotime($index->created_at))}}</td>
                    <td>{{$index->price}}</td>
                    @if(Auth::user()->role=='admin')<td>{{App\Models\User::find($index->created_by)->name}}</td>@endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="col-lg-3 mt-2">
        <h5>Stock Info</h5>
        <table class="table table-sm table-bordered">
            <thead >
                <tr class="table-success">
                    <th>Qty</th>
                    <th>Transit Qty</th>
                    <th>PDC</th>
                </tr>
            </thead>
            <tbody class="table-hover text-success" style="font-size: 20px;">
                <tr>
                    <td>{{$product->productstock->qty}}</td>
                    <td>{{$product->productstock->t_qty}}</td>
                    <td>{{$product->productstock->pdc}}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-lg-12 ">
        <table class="table table-striped table-sm table-bordered">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Brand</th>
                    <th>UOM</th>
                    <th>Group</th>
                    <th>Categroy</th>
                    <th>Subcategory</th>
                    <th>Size</th>
                    <th>Movement</th>
                </tr>
            </thead>
            <tbody class="table-hover">
                <tr>
                    <td>{{$product->note}}</td>
                    <td>{{$product->brand->name}}</td>
                    <td>@if(!empty($product->uom)){{$product->uom->name}}@endif</td>
                    <td>@if(!empty($product->group)){{$product->group->name}}@endif</td>
                    <td>@if(!empty($product->group)){{$product->category->name}}@endif</td>
                    <td>@if(!empty($product->group)){{$product->subcategory->name}}@endif</td>
                    <td>@if(!empty($product->size)){{$product->size->name}}@endif</td>
                    <td>@if(!empty($product->color)){{$product->color->name}}@endif</td>
                </tr>
            </tbody>
            <thead>
                <tr>
                    <th>Item Code on Invoice</th>
                    <th>Vendor Item Code</th>
                    <th>HRM Code</th>
                    <th>P/O Unit</th>
                    <th>Package Type</th>
                    <th>Package Quantity</th>
                    <th>Tax Apply</th>
                    <th>Sale Type</th>
                </tr>
            </thead>
            <tbody class="table-hover">
                <tr>
                    <td>{{$product->item_code_invoice}}</td>
                    <td>{{$product->vendor_item_code}}</td>
                    <td>{{$product->hrm_code}}</td>
                    <td>{{$product->po_qty_limit}}</td>
                    <td>@if(!empty($product->packagetype)){{$product->packagetype->name}}@endif</td>
                    <td>{{$product->package_qty}}</td>
                    <td>{{$product->is_tax}}</td>
                    <td>{{$product->sale_type}}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-lg-3 mt-2 ">
        <h5>Suppersession Histrory</h5>
        <table id="datatable-buttons" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
            <thead>
                <tr class="table-info">
                    <th>S.NO</th>
                    <th>Date</th>
                    <th>Product</th>                
                </tr>
            </thead>           
            <tbody>
                <?php $x=1; ?>
                @foreach ($pricehistories as $index)
                <tr>
                    <td>{{$x++}}</td>
                    <td><span class="d-none">{{date('Ymd',strtotime($index->created_at))}}</span>{{date('d-m-Y',strtotime($index->created_at))}}</td>
                    <td>{{$index->name}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="col-lg-9">
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-xs me-3">
                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-16">
                                    {{$product->enquiryline->where('enquiry_id','!=','0')->sum('quantity')}}
                                </span>
                            </div>
                            <h5 class="font-size-14 mb-0">Enquiry</h5>
                        </div>
                        <div class="text-muted mt-4">
                            <div class="d-flex">
                                <center><span class="badge badge-soft-success me-2 font-size-14">
                                {{$product->enquiryline->where('enquiry_id','!=','0')->sum('price')}}</span></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-xs me-3">
                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-16">
                                    {{$product->invoiceline->where('invoice_id','!=','0')->sum('quantity_so')}}
                                </span>
                            </div>
                            <h5 class="font-size-14 mb-0">Proforma Invoice</h5>
                        </div>
                        <div class="text-muted mt-4">
                            <div class="d-flex">
                                <center><span class="badge badge-soft-success me-2 font-size-14">{{$product->invoiceline->where('invoice_id','!=','0')->sum('amount')}}</span></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-xs me-3">
                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-16">
                                    {{$product->saleline->where('slae_id','!=','0')->sum('quantity')}}
                                </span>
                            </div>
                            <h5 class="font-size-14 mb-0">Sales</h5>
                        </div>
                        <div class="text-muted mt-4">
                            <div class="d-flex">
                                <center><span class="badge badge-soft-success me-2 font-size-14">{{$product->saleline->where('slae_id','!=','0')->sum('cost_price')}}</span></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-xs me-3">
                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-16">
                                    {{$invoiceLosPrice}}
                                </span>
                            </div>
                            <h5 class="font-size-14 mb-0">Price LOS</h5>
                        </div>
                        <div class="text-muted mt-4">
                            <div class="d-flex">
                                .
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-xs me-3">
                                <span class="avatar-title rounded-circle bg-primary bg-soft text-primary font-size-16">
                                    {{$invoiceLosStock}}
                                </span>
                            </div>
                            <h5 class="font-size-14 mb-0">Stock LOS</h5>
                        </div>
                        <div class="text-muted mt-4">
                            <div class="d-flex">
                                .
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    
    <div class="col-lg-6 mt-2">
        <h5>Associated Item Number</h5>
        <table class="table table-sm table-bordered">
            <thead>
                <tr class="table-danger">
                    <th>Item No</th>
                    <th>Qty</th>
                    <th>Transit Qty</th>
                    <th>PDC</th>
                    <th>Cost</th>
                    <th>RSP</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="col-lg-6 mt-2 ">
        <h5>Related Item Number</h5>
        <table class="table table-sm table-bordered">
            <thead>
                <tr class="table-warning">
                    <th>Item No</th>
                    <th>Qty</th>
                    <th>Transit Qty</th>
                    <th>PDC</th>
                    <th>Cost</th>
                    <th>RSP</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <div class="col-lg-3 mt-2 d-none">
        <h5>Last 5 Stock Info</h5>
        <table id="datatable-buttons" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
            <thead>
                <tr>
                    <th>S.NO</th>
                    <th>Date</th>
                    <th>Qty</th>
                    <th>Transit Qty</th>
                    <th>PDC</th>
                </tr>
            </thead>           
            <tbody>
                <?php $x=1; ?>
                @foreach ($last5price as $index)
                <tr>
                    <td>{{$x++}}</td>
                    <td>{{date('d M, Y',strtotime($index->created_at))}}</td>
                    <td>{{$index->qty}}</td>
                    <td>{{$index->t_qty}}</td>
                    <td>{{$index->pdc}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="col-lg-3 mt-2 d-none">
        <h5>Last 5 Price Info</h5>
        <table id="datatable-buttons" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
            <thead>
                <tr>
                    <th>S.NO</th>
                    <th>Date</th>
                    <th>RSP</th>
                    <th>Landing Cost</th>
                </tr>
            </thead>           
            <tbody>
                <?php $x=1; ?>
                @foreach ($last5stock as $index)
                <tr>
                    <td>{{$x++}}</td>
                    <td>{{date('d M, Y',strtotime($index->created_at))}}</td>
                    <td>{{$index->price}}</td>
                    <td>{{$index->min_price}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="col-lg-4 mt-2 d-none">
        <h4 class="text-secondary">Last 5 Enquiry List:</h4>
        <div class="table-responsive">
            <table id="datatable-buttons" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Enq No</th>
                        <th>Customer</th>
                        <th>Enq Date</th>
                        <th>Price</th>
                        <th>Qty</th>
                    </tr>
                </thead>           
                <tbody>
                    <?php $x=1; ?>
                    @foreach ($last5enqs as $index)
                    <tr>
                        <td>@if($index->enquiry_no!='') {{$index->enquiry_no}} @else {{$index->id}} @endif</td>
                        <td>{{$index->enquiry->customer->name}}</td>
                        <td>{{date('d M, Y',strtotime($index->enquiry->enquiry_date))}}</td>
                        <td>{{$index->rate}}</td>
                        <td>{{$index->quantity}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-4 mt-2 d-none">
        <h4 class="text-secondary">Last 5 Invoice List:</h4>
        <div class="table-responsive">
            <table id="datatable-buttons" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Invoice No</th>
                        <th>Customer</th>
                        <th>Invoice Date</th>
                        <th>Price</th>
                        <th>SO Qty</th>
                        <th>PO Qty</th>
                        <th>LOS Qty</th>
                    </tr>
                </thead>           
                <tbody>
                    <?php $x=1; ?>
                    @foreach ($last5PIs as $index)
                    <tr>
                        <td>@if($index->invoice->invoice_no!='') {{$index->invoice->invoice_no}} @else {{$index->invoice->id}} @endif</td>
                        <td>{{$index->customer->name}}</td>
                        <td>{{date('d M, Y',strtotime($index->invoice->invoice_date))}}</td>
                        <td>{{$index->target_price}}</td>
                        <td>{{$index->quantity_so}}</td>
                        <td>{{$index->quantity_po}}</td>
                        <td>{{$index->quantity_los}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-8 mt-2 ">
        <h4 class="text-secondary">Sales List:</h4>
        <div class="table-responsive">
            <table id="datatable" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sale No</th>
                        <th>Customer</th>
                        <th>Sale Date</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Status</th>
                    </tr>
                </thead>           
                <tbody>
                    <?php $x=1; ?>
                    @foreach ($lastSales as $index)
                        @if($index->sale->orderstatus->name=='INVOICED')
                            <tr>
                                <td>@if($index->sale->sale_no!='') {{$index->sale->sale_no}} @else {{$index->sale->id}} @endif</td>
                                <td>{{$index->sale->customer->name}}</td>
                                <td><span class="d-none">{{date('Ymd',strtotime($index->sale->sale_date))}}</span>{{date('d M, Y',strtotime($index->sale->sale_date))}}</td>
                                <td>{{$index->price}}</td>
                                <td>{{$index->quantity}}</td>
                                <td class="{{$index->sale->orderstatus->color}}">{{$index->sale->orderstatus->name}}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-4 mt-2 ">
        <h4 class="text-secondary">Sales Draft List:</h4>
        <div class="table-responsive">
            <table style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Price</th>
                        <th>Qty</th>
                    </tr>
                </thead>           
                <tbody>
                    <?php $x=1; ?>
                    @foreach ($lastSalesDraft as $index)
                    <tr>
                        <td>{{$index->customer->name}}</td>
                        <td>{{$index->price}}</td>
                        <td>{{$index->quantity}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>