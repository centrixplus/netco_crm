<div class="mb-3">
    <input type="text" style="border-radius: 30px;" class="form-control me-5" id="searchlists" value="" placeholder="Search..." onkeyup="searchvendors(this.value);" autofocus>
</div>
<div class="table-responsive search-areas" data-simplebar style="max-height: 450px;">
<table class="table table-nowrap align-middle table-hover mb-0" style="border: 1px;">
<thead>
	<tr>
		<th>Name</th>
		<th>Email</th>
		<th>Phone</th>
	</tr>
</thead>
<tbody>
@if(count($vendors)>0)
@foreach($vendors as $v)
<tr role='button' onclick="vendorselect('{{$v->id}}','{{ addslashes($v->name) }}');">
<td><h6 class="text-truncate font-size-14 mb-1">{{$v->name}}</h6></td>
<td><h6 class="text-truncate font-size-14 mb-1">{{$v->email}}</h6></td>
<td><h6 class="text-truncate font-size-14 mb-1">{{$v->phone}}</h6></td>
</tr>
@endforeach
@else
<tr> <td colspan="3">No Vendors</td> </tr>
@endif
</tbody>
</table>
</div>
