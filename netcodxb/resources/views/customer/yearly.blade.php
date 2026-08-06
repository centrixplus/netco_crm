
<div class="row">
	<table class="table">
		<thead>
			<tr>
				<th>Customer</th>
				<th>Amount</th>
				<th>Payment Status</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$total=App\Customer::where('delete_status','0')->where('customer_type','Yearly')->sum('amount');
				$paid=0;
			?>
			@foreach($yearly as $log)
			<?php 
				$year_date=date('m-d',strtotime($log->collection_date));
				
				if(date('m-d',strtotime($log->collection_date)) < date('m-d')){
					$fromdate=date('Y').'-'.$year_date;
					$todate=date('Y-m-d',strtotime("+1 year", strtotime($fromdate)));
				}
				else{
					$todate=date('Y').'-'.$year_date;
					$fromdate=date('Y-m-d',strtotime("-1 year", strtotime($todate)));
				}
				
				$payment=App\Payment::where('customer_id',$log->id)->whereBetween('payment_date',[$fromdate, $todate])->first();
				
				if($payment){
					$paid=$paid+$payment->amount;
				}
			 ?>
			 <tr>
			 	<td>{{$log->name}}</td>
			 	<td>@if($payment){{$payment->amount}}@else<input type="number" style="width: 120px;" class="form-control" name="amount_{{$log->id}}" id="amount_{{$log->id}}" value="{{$log->amount}}">@endif</td>
			 	<td>@if($payment)<span style="color: green;">Paid</span>@else<input onchange="checkPayment({{$log->id}})" type="checkbox" name="payment_status_{{$log->id}}" id="payment_status_{{$log->id}}">@endif</td>
			 </tr>
			@endforeach
		</tbody>
	</table>
	<div class="col-4 mt-3">
		<h5 style="color: blue;">Total: {{$total}} kwd</h5>
	</div>
	<div class="col-4 mt-3">
		<h5 style="color: green;">Paid: {{$paid}} kwd</h5>
	</div>
	<div class="col-4 mt-3">
		<h5 style="color: red;">Pending: {{$total-$paid}} kwd</h5>
	</div>
</div>
