<div class="table-responsive">
    <table id="datatable-buttons" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
        <thead>
            <tr>
                <th>Enquiry ID</th>
                @if(Auth::user()->role=='admin')<th>Customer</th>@endif
                @if(Auth::user()->role=='admin')<th>Code</th>@endif
                @if(Auth::user()->role=='admin')<th>Location</th>@endif
                <th>Date</th>
                @if(Auth::user()->role=='Customer')<th>Stage</th>@endif
                <th>Created at</th>
                @if(Auth::user()->role=='admin')<th>Update</th>@endif
                <th>View</th>
                @if(Auth::user()->role=='Customer')<th>Remove</th>@endif
        </thead>           
        <tbody>
            <?php $x=1; ?>
            @foreach ($indexes as $index)
            <tr>
                <td>{{$index->id}}</td>
                @if(Auth::user()->role=='admin')<td>{{$index->customer->name}}</td>@endif
                @if(Auth::user()->role=='admin')<td>{{$index->customer->code}}</td>@endif
                @if(Auth::user()->role=='admin')<td>@if($index->customer->location){{$index->customer->location->name}}@endif</td>@endif
                <td>{{date('d M, Y',strtotime($index->enquiry_date))}}</td>
                @if(Auth::user()->role=='Customer')<td>{{$index->stage->name}}</td>@endif
                <td>{{date('d M, Y H:i:s',strtotime($index->created_at))}}</td>
                @if(Auth::user()->role=='admin')<td></td>@endif
                <td><a href="{{url('/showenquiryrequest')}}/{{$index->id}}/" class="btn btn-outline-warning waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-eye"></i></a></td>
                @if(Auth::user()->role=='Customer')<td><a href="{{url('/enquiry')}}/{{$index->id}}/delete" onclick="return confirm('Are you sure?')" class="btn btn-outline-danger waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-trash-can-outline"></i></a></td>@endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>