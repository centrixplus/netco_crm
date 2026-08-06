<tr class="@if($x!=1) mt-3 @endif" id="row{{$x}}">
	<td>
		<input type="hidden" data-x='{{$x}}' name="product{{$x}}" id="product{{$x}}" value="{{$product->id}}" class="form-control select2 product">
		{{$product->name}} {{$product->note}}<br>
		<span class="text-primary">Stock- <span id="cstock{{$x}}">{{$product->productstock->qty+$product->productstock->t_qty}}</span>, PDC- <span id="pdcstock{{$x}}">{{$product->productstock->pdc}}</span></span>
	</td>
	<td>
		<input data-x='{{$x}}' value="{{$qty}}" style="width: 100px;" min="1" type="number" name="qty{{$x}}" step="1"  value="1" id="qty{{$x}}" class="form-control mt-3 qty" @if($x==1) required="" @endif>
	</td>
	<td>
		<input data-x='{{$x}}' value="{{$product->price}}" style="width: 140px;"  type="number" name="rate{{$x}}" id="rate{{$x}}" class="form-control mt-3 rate" readonly="">
	</td>
	<td colspan="2">
		<input data-x='{{$x}}' value="{{$product->price*$qty}}" style="width: 140px;" min="1" type="text" name="price{{$x}}" id="price{{$x}}" class="form-control price mt-3" value="0" readonly="">
	</td>
	<td>
		@if($x!=1)<a class="btn-sm btn-danger waves-effect waves-light me-2 mt-2"><i class="mdi mdi-minus" onclick="removeRow({{$x}})"></i></a>@endif
	</td>
</tr>