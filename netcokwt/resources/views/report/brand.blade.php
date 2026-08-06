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
        
        <tr>
            <td>{{$index->name}}</td>
            <td>{{ $index->salelines->first()->total_quantity ?? 0 }}</td>
            <td>{{ number_format($index->salelines->first()->total_cost_price ?? 0, 3, '.', '') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>