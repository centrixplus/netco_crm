<h5>Last Five Purchase Request List:</h5>
<table class="table">
	<thead>
		<tr>
			<td>Date</td>
			<td>Quantity</td>
			<td>Status</td>
			<td>User</td>
		</tr>
	</thead>
	<tbody>
		@foreach ($old5purchaserequestlines as $index)
			<tr>
				<td>{{date('d-m-Y',strtotime($index->created_at))}}</td>
				<td>{{$index->quantity}}</td>
				<td>{{$index->purchaserequest->prstatus->name}}</td>
				<td>{{$index->purchaserequest->user->name}}</td>
			</tr>
		@endforeach
	</tbody>
</table>

