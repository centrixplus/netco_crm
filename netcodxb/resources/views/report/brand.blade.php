<table id="datatable-buttons" class="table table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Quantity (NOS)</th>
            <th>Amount</th>
        </tr>
    </thead>           
    <tbody>
        <?php $x=1; ?>
        @foreach ($brands as $index)
        @php $todate = date('Y-m-d', strtotime("+1 day", strtotime($todate))); @endphp
        <tr>
            <td>{{$index->name}}</td>
            <td>{{$index->salelines->whereBetween('created_at',[$fromdate, $todate])->sum('quantity')}}</td>
            <td>{{number_format($index->salelines->whereBetween('created_at',[$fromdate, $todate])->sum('cost_price'), 3, '.', '' )}}</td>
        </tr>
        @endforeach
    </tbody>
</table>