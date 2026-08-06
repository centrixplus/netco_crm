<table id="datatable-buttons1" class="table table-bordered table-responsive">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Quote ID</th>
            <th>Product Name</th>
            <th>Description</th>
            <th>Quantity Requested</th>
            <th>Rate</th>
        </tr>
    </thead>           
    <tbody>
        <?php $x=1; ?>
        @foreach ($indexes as $index)
        <tr>
            <td>{{$x++}}</td>
            <td>{{$index->enquiry_id}}</td>
            <td>{{$index->product->name}}</td>
            <td>{{$index->product->note}}</td>
            <td>{{$index->quantity}}</td>
            <td>{{$index->rate}}</td>
        </tr>
        @endforeach
    </tbody>
</table>