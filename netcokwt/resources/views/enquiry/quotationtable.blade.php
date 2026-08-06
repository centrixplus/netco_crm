<div class="table-responsive">
    <table id="datatable-buttons" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                @if(Auth::user()->role!='Customer')<th>Customer</th>@endif
                <th>Code</th>
                @if(Auth::user()->role!='Customer')<th>Location</th>@endif
                <th>Enquiry Date</th>
                <th>Quote Date</th>
                <th>Expiry Date</th>
                <th>Stage</th>
                <th>Created at</th>
                @if(Auth::user()->role=='admin')<th>Edit Quote</th>@endif
                <th>View</th>
                <th>Quote</th>
                @if(Auth::user()->role!='Customer')<th>Update Status</th>@endif
                @if(Auth::user()->role!='Customer')<th>Remove</th>@endif
        </thead>           
        <tbody>
            <?php $x=1; ?>
            @foreach ($indexes as $index)
            <tr>
                <td>@if($index->quote_no!='') {{$index->quote_no}} @else {{$index->id}} @endif</td>
                @if(Auth::user()->role!='Customer')<td>{{$index->customer->name}}</td>@endif
                <td>{{$index->customer->code}}</td>
                @if(Auth::user()->role!='Customer')<td>@if($index->customer->location){{$index->customer->location->name}}@endif</td>@endif
                <td>{{date('d M, Y',strtotime($index->enquiry_date))}}</td>
                <td>{{date('d M, Y H:i:s',strtotime($index->quote_date))}}</td>
                <td>{{date('d M, Y',strtotime($index->expiry_date))}}</td>
                <td>{{$index->stage->name}}</td>
                <td>{{date('d M, Y H:i:s',strtotime($index->created_at))}}</td>
                <td>
                    @if($index->stage_id=='3' && $index->expiry_date >= date('Y-m-d'))
                        <button data-id="{{$index->id}}" type="button" class="btn btn-outline-info waves-effect waves-light btn-sm updatebtn"  data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl"><i class="mdi mdi-pencil"></i></button>
                    @else
                        -
                    @endif
                </td>
                <td><a href="{{url('/showenquiryrequest')}}/{{$index->id}}/" class="btn btn-outline-warning waves-effect waves-light btn-sm"><i class="mdi mdi-eye"></i></a></td>
                <td><a href="{{url('/quote')}}/{{$index->id}}" class="btn btn-outline-warning waves-effect waves-light btn-sm"><i class="mdi mdi-file"></i></a></td>
                 @if(Auth::user()->role!='Customer')<td>
                    @if($index->stage_id=='3')
                        <a onclick="return confirm('Are you sure?')" href="{{url('/quoteapprove')}}/{{$index->id}}" class="btn btn-outline-success waves-effect waves-light btn-sm">Approve</a>
                    @else
                        <a onclick="return confirm('Are you sure?')" href="{{url('/saleorder')}}/{{$index->id}}" class="btn btn-outline-success waves-effect waves-light btn-sm">Sale Order</a>
                    @endif
                </td>@endif
                @if(Auth::user()->role!='Customer')<td><a href="{{url('/enquiry')}}/{{$index->id}}/delete" onclick="return confirm('Are you sure?')" class="btn btn-outline-danger waves-effect waves-light btn-sm"><i class="mdi mdi-trash-can-outline"></i></a></td>@endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>