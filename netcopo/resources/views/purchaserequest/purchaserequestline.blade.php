@foreach($purchaserequestlines as $log)
<tr>
   <th><span id="name{{$log->id}}">{{$log->product->name}}</span>
      @php $rproduct=''; @endphp @if($log->product->id!=$log->product->reference_id) @php $rproduct=App\Models\Product::find($log->product->reference_id);  @endphp
         @if($rproduct) <br><span class="text-success">{{$rproduct->name}}</span> @endif
       @endif
   </th>
   <th>{{$log->product->note}}</th>
   <th id="quantity{{$log->id}}">{{$log->quantity}}</th>
   <th id="target_price{{$log->id}}">{{$log->target_price}}</th>
   <th id="note{{$log->id}}">{{$log->note}}</th>
   <th>
   		<a onclick="editPurchaserequestLine({{$log->id}},{{$log->product_id}})"  class="text-warning"><i class="bx bxs-pencil font-size-18 mb-3"></i></a>
   		<a onclick="deletePurchaserequestLine({{$log->id}})" class="text-danger" style="margin-left: 10px;"><i class="bx bxs-trash font-size-18 mb-3"></i></a>
   </th>
</tr>
@endforeach