
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
				$total=App\Customer::where('delete_status','0')->where('customer_type','Monthly')->sum('amount');
				$paid=0;
			?>
			@foreach($monthly as $log)
			<?php 
			$payment=App\Payment::where('customer_id',$log->id)->whereYear('payment_date',date('Y'))->whereMonth('payment_date',date('m'))->first(); 
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
