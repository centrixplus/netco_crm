<table class="table">
	<thead class="table-light">
		<tr>
			<th>Status</th>
			<th>Count (NOS)</th>
			<th>Amount (AED)</th>
		</tr>
	</thead>
	<tbody>
		@foreach($statuses as $log)
		<tr>
			<td>{{$log->name}}</td>
			<td>{{$log->sale->where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->count()}}</td>
			<td>{{$log->sale->where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->sum('grand_total')}}</td>
		</tr>
		@endforeach
	</tbody>
</table>