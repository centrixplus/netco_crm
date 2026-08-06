@if($lastcustomerPIs)
<h4 class="text-secondary">Last Invoice Selected Customer & Item:</h4>
<table id="datatable-buttons" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
    <thead>
        <tr>
            <th>Invoice No</th>
            <th>Invoice Date</th>
            <!-- <th>Cost</th> -->
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td> @if($lastcustomerPIs->invoice->invoice_no!='') {{$lastcustomerPIs->invoice->invoice_no}} @else {{$lastcustomerPIs->invoice->id}} @endif</td>
            <td>{{date('d M, Y',strtotime($lastcustomerPIs->invoice->invoice_date))}}</td>
            <!-- <td>{{$index->cost}}</td> -->
            <td>{{$lastcustomerPIs->target_price}}</td>
        </tr>
    </tbody>
    </table>
</table>
@endif
<h4 class="text-secondary">Last 5 Invoice List:</h4>
<div class="table-responsive">
    <table id="datatable-buttons" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
        <thead>
            <tr>
                <th>Invoice No</th>
                <th>Customer</th>
                <th>Invoice Date</th>
                <!-- <th>Cost</th> -->
                <th>Price</th>
            </tr>
        </thead>           
        <tbody>
            <?php $x=1; ?>
            @foreach ($last5PIs as $index)
            <tr>
                <td>@if($index->invoice->invoice_no!='') {{$index->invoice->invoice_no}} @else {{$index->invoice->id}} @endif</td>
                <td>{{$index->customer->name}}</td>
                <td>{{date('d M, Y',strtotime($index->invoice->invoice_date))}}</td>
                <!-- <td>{{$index->cost}}</td> -->
                <td>{{$index->target_price}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>