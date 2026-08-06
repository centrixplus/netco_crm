<table class="table">
	<thead class="table-light">
		<tr>
			<th>Status</th>
			<th>Count (NOS)</th>
			<th>Amount</th>
		</tr>
	</thead>
	<tbody>
		@php $del=App\Models\Status::find('3'); @endphp
		@foreach($statuses as $log)
		<tr>
			<td>{{$log->name}}</td>
			<td>
				@if($log->id==10)
					{{$log->sale->where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->sum('no_of_qty')+$del->sale->where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->count()}}
				@else
					{{$log->sale->where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->count()}}
				@endif
			</td>
			<td>
				@if($log->id==10)
					{{number_format( $log->sale->where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->sum('grand_total')+$del->sale->where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->sum('grand_total'), 3, '.', '' )}}
				@else
					{{number_format( $log->sale->where('delete_status','0')->whereBetween('sale_date',[$fromdate, $todate])->sum('grand_total'), 3, '.', '' )}}
				@endif
			</td>
		</tr>
		@endforeach
	</tbody>
</table>