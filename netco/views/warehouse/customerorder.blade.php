<table class="table">
	<thead class="table-light">
		<tr>
			<th>Name</th>
			<th>Location</th>
			<th>Count (NOS)</th>
			<th>Amount (AED)</th>
			<th>View</th>
		</tr>
	</thead>
	<tbody>
		@foreach($customer_sale as $log)
		<tr>
			<td>{{$log->name}}</td>
			<td>@if(!empty($log->location)){{$log->location->name}}@endif</td>
			<td>{{$log->sale->where('delete_status','0')->where('status_id','2')->count()}}</td>
			<td>{{$log->sale->where('delete_status','0')->where('status_id','2')->sum('grand_total')}}</td>
			<td><a href="{{url('/warehouseOrders')}}/{{$log->id}}/0" class="btn btn-outline-warning waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-eye"></i></a></td>
		</tr>
		@endforeach
	</tbody>
</table>