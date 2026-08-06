
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
				$total=App\Customer::where('delete_status','0')->where('customer_type','Quaterly')->sum('amount');
				$paid=0;
			 ?> 
			@foreach($quaterly as $log)
			<?php 
				if(date('m') <= 3){

					$payment=App\Payment::where('customer_id',$log->id)->whereBetween('created_at',[date('Y-01-01'), date('Y-03-31')])->first();
				}

				if(date('m') > 3 && date('m') <= 6){

					$payment=App\Payment::where('customer_id',$log->id)->whereBetween('created_at',[date('Y-04-01'), date('Y-06-30')])->first();
				}
				if(date('m') > 6 && date('m') <= 9){

					$payment=App\Payment::where('customer_id',$log->id)->whereBetween('created_at',[date('Y-07-01'), date('Y-09-30')])->first();
				}
				if(date('m') > 9 && date('m') <= 12){

					$payment=App\Payment::where('customer_id',$log->id)->whereBetween('created_at',[date('Y-10-01'), date('Y-12-31')])->first();
				}
				
				
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
