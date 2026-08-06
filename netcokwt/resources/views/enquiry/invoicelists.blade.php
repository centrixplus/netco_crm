<h4 class="text-secondary">Last 5 Customer Invoice List:</h4>
<div class="table-responsive">
    <table id="datatable-buttons" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
        <thead>
            <tr>
                <th>Invoice No</th>
                <th>Store</th>
                <th>Invoice Date</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Created at</th>
                <th>Created by</th>
                <th>View</th>
        </thead>           
        <tbody>
            <?php $x=1; ?>
            @foreach ($invoices as $index)
            <tr class="{{$index->invoicestatus->color}}">
                <td>@if($index->invoice_no!='') {{$index->invoice_no}} @else {{$index->id}} @endif</td>
                <td>@if($index->store){{$index->store->name}}@endif</td>
                <td>{{date('d M, Y',strtotime($index->invoice_date))}}</td>
                <td>{{$index->invoicestatus->name}}</td>
                <td>{{$index->amount_so}}</td>
                <td>{{date('d M, Y H:i:s',strtotime($index->created_at))}}</td>
                <td>{{$index->user->name}}</td>
                <td><a target="_blank" href="{{url('/viewInvoice')}}/{{$index->id}}" class="btn btn-outline-info waves-effect waves-light btn-sm"><i class="mdi mdi-eye"></i></a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>