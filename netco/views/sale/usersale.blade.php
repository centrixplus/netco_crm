<table class="table">
	<thead class="table-light">
		<tr>
			<th>Name</th>
			<th>Role</th>
			<th>Count (NOS)</th>
			<th>Amount (AED)</th>
		</tr>
	</thead>
	<tbody>
		@foreach($users as $log)
		<tr>
			<td>{{$log->name}}</td>
			<td>{{$log->role}}</td>
			<td>{{$log->sale->where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->count()}}</td>
			<td>{{$log->sale->where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->sum('grand_total')}}</td>
		</tr>
		@endforeach
	</tbody>
</table>