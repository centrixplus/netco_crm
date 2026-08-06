<div class="table-responsive">
    <table id="datatable-buttons" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
        <thead>
            <tr>
                <th>Enquiry ID</th>
                @if(Auth::user()->role!='Customer')<th>Customer</th>@endif
                <th>Code</th>
                @if(Auth::user()->role!='Customer')<th>Location</th>@endif
                <th>Date</th>
                <th>Stage</th>
                <th>Created at</th>
                <th>Update</th>
                <th>View</th>
                <th>Remove</th>
        </thead>           
        <tbody>
            <?php $x=1; ?>
            @foreach ($indexes as $index)
            <tr>
                <td>@if($index->enquiry_no!='') {{$index->enquiry_no}} @else {{$index->id}} @endif</td>
                @if(Auth::user()->role!='Customer')<td>{{$index->customer->name}}</td>@endif
                <td>{{$index->customer->code}}</td>
                @if(Auth::user()->role!='Customer')<td>@if($index->customer->location){{$index->customer->location->name}}@endif</td>@endif
                <td>{{date('d-m-Y',strtotime($index->enquiry_date))}}</td>
                <td>{{$index->stage->name}}</td>
                <td>{{date('d-m-Y H:i:s',strtotime($index->created_at))}}</td>
                <td>
                    @if($index->stage_id=='1')
                        <a href="{{url('/enquirystage')}}/update/{{$index->id}}/2" onclick="return confirm('Are you sure?')" class="btn btn-outline-primary waves-effect waves-light btn-sm">Accept</a>
                    @elseif($index->stage_id=='2')
                        <button data-id="{{$index->id}}" type="button" class="btn btn-outline-info waves-effect waves-light btn-sm updatebtn" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl"> Quote</button>
                    @endif

                </td>
                <td><a href="{{url('/showenquiryrequest')}}/{{$index->id}}/" class="btn btn-outline-warning waves-effect waves-light btn-sm"><i class="mdi mdi-eye"></i></a></td>
               <td><a href="{{url('/enquiry')}}/{{$index->id}}/delete" onclick="return confirm('Are you sure?')" class="btn btn-outline-danger waves-effect waves-light btn-sm"><i class="mdi mdi-trash-can-outline"></i></a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>