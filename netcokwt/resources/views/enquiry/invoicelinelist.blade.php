<table id="datatable-buttons1" class="table table-bordered table-responsive">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Invoice ID</th>
            <th>Product Name</th>
            <th>Description</th>
            <th>Quantity Requested</th>
            <th>Rate</th>
        </tr>
    </thead>           
    <tbody>
        <?php $x=1; ?>
		@foreach($indexes as $log)
		<tr>
		   <th>{{$x++}}</th>
		   <th>{{$log->invoice_id}}</th>
		   <th>{{$log->product->name}}</th>
		   <th>{{$log->product->note}}</th>
		   <th>{{$log->quantity_so}}</th>
		   <th>{{$log->target_price}}</th>
		</tr>
		@endforeach
	</tbody>
</table>