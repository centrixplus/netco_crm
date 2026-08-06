@foreach($invoicelines as $log)
<tr>
   <th><span id="name{{$log->id}}">{{$log->product->name}}</span>
      @php $rproduct=''; @endphp @if($log->product->id!=$log->product->reference_id) @php $rproduct=App\Models\Product::find($log->product->reference_id);  @endphp
         @if($rproduct) <br><span class="text-success">{{$rproduct->name}}</span> @endif
       @endif
   </th>
   <th>{{$log->product->note}}</th>
   <th id="rsp{{$log->id}}">{{$log->rsp}}</th>
   <th id="quantity_po{{$log->id}}">{{$log->quantity_po}}</th>
   <th id="target_price{{$log->id}}">{{$log->target_price}}</th>
   <th id="quantity_so{{$log->id}}">{{$log->quantity_so}}</th>
   <th >{{$log->amount}}</th>
   <th id="is_invoiced{{$log->id}}">@if($log->is_invoiced==0) No @else Yes @endif</th>
   <th>
   		<a onclick="editInvoiceLine({{$log->id}},{{$log->product_id}})"  class="text-warning"><i class="bx bxs-pencil font-size-18 mb-3"></i></a>
   		<a onclick="deleteInvoiceLine({{$log->id}})" class="text-danger" style="margin-left: 10px;"><i class="bx bxs-trash font-size-18 mb-3"></i></a>
   </th>
</tr>
@endforeach