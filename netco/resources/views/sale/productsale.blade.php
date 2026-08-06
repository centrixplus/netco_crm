<table class="table">
	<thead>
		<tr>
	 	<th>Date</th>
	 	<th>Qty</th>
	 	<th>Rate</th>
	 </tr>
	</thead>
	<tbody>
		@foreach($sales as $sale)
		<tr>
			<td>{{date('d M, Y',strtotime($sale->created_at))}}</td>
			<td>{{$sale->quantity}}</td>
			<td>{{$sale->rate}}</td>
		</tr>
		@endforeach
	</tbody>
</table>