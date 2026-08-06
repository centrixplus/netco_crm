<div class="mb-3">
    <input type="text" style="border-radius: 30px;" class="form-control me-5" id="searchlists" value="" placeholder="Search..." onkeyup="searchcustomers(this.value);">
</div>
<div class="table-responsive search-areas" data-simplebar style="max-height: 450px;">
<table class="table table-nowrap align-middle table-hover mb-0" style="border: 1px;">
<thead>
	<tr>
		<th>Code</th>
		<th>Name</th>
		<th>Mobile</th>
	</tr>
</thead>
<tbody>
@if(count($customers)>0)
@foreach($customers as $advs)
<tr role='button' onclick="customerselect('{{$advs->id}}','{{$advs->name}}','{{$advs->code}}');">
<td><h6 class="text-truncate font-size-14 mb-1">{{$advs->code}}</h6></td>
<td><h6 class="text-truncate font-size-14 mb-1">{{$advs->name}}</h6></td>
<td><h6 class="text-truncate font-size-14 mb-1">{{$advs->mobile}}</h6></td>
</tr>
@endforeach
@else
<tr> <td colspan="2">No Details</td> </tr>
@endif
</tbody>
</table>
</div>