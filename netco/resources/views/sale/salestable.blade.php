<div class="table-responsive">
    <table id="datatable-buttons" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                @if(Auth::user()->role=='admin')<th>Code</th>@endif
                <th>Location</th>
                <th>Date</th>
                @if(Auth::user()->role=='warehouse')<th>Assign</th>@endif
                <th>Update</th>
                <th>View</th>
                @if(Auth::user()->role!='warehouse')<th>Delete</th>@endif
                <th>Total Amount</th>
                <th>Priority</th>
                <th>Document No</th>
                <th>Reason</th>
                <th>Status</th>
                <th>AWB</th>
                <th>No.of.Boxes</th>
                <th>Created at</th>
                @if(Auth::user()->role=='admin')<th>Created By</th>@endif
            </tr>
        </thead>           
        <tbody>
            <?php $x=1; ?>
            @foreach ($indexes as $index)
            <tr class="@if($index->delete_status==1) bg-danger @else {{$index->orderstatus->color}} @endif">
                <td>{{App\Models\Sale::saleno($index->id)}}</td>
                <td>{{$index->customer->name}}</td>
                @if(Auth::user()->role=='admin')<td>{{$index->customer->code}}</td>@endif
                <td>@if($index->customer->location){{$index->customer->location->name}}@endif</td>
                <td>{{date('d M, Y',strtotime($index->sale_date))}}</td>

               

               @if(Auth::user()->role=='warehouse')
                   <td> 
                        @if($index->status_id=='2')
                            @if($index->warehousepersion)
                                {{$index->warehousepersion->name}}
                            @else
                                <button data-id="{{$index->id}}" type="button" class="btn btn-outline-info waves-effect waves-light btn-sm font-size-18 assignbtn" data-bs-toggle="modal" data-bs-target="#myModal1"> <i class="mdi mdi-account-arrow-left-outline ms-1"></i></button>
                             @endif

                        @else
                            @if($index->warehousepersion) {{$index->warehousepersion->name}} @endif
                        @endif
                    </td> 
                @endif

                <td> 
                    @if(Auth::user()->role=='warehouse' )
                        @if($index->status!='Delivered')
                            @if($index->status_id=='2')
                                @if($index->warehousepersion)
                                    <button data-id="{{$index->id}}" data-invoice_no="{{$index->invoice_no}}" data-status="{{$index->status}}" data-status_id="{{$index->status_id}}" data-show_status_id="{{$index->orderstatus->show_order}}" type="button" class="btn btn-outline-info waves-effect waves-light btn-sm font-size-18 updatebtn" data-bs-toggle="modal" data-bs-target="#myModal"> <i class="mdi mdi-upload ms-1"></i></button>
                                @endif
                           
                            @else
                                <button data-id="{{$index->id}}" data-invoice_no="{{$index->invoice_no}}" data-status="{{$index->status}}" data-status_id="{{$index->status_id}}" data-show_status_id="{{$index->orderstatus->show_order}}" type="button" class="btn btn-outline-info waves-effect waves-light btn-sm font-size-18 updatebtn" data-bs-toggle="modal" data-bs-target="#myModal"> <i class="mdi mdi-upload ms-1"></i></button>
                             @endif
                        @endif

                    @else
                        @if($index->status!='Delivered')
                            <button data-id="{{$index->id}}" data-invoice_no="{{$index->invoice_no}}" data-status="{{$index->status}}" data-status_id="{{$index->status_id}}" data-show_status_id="{{$index->orderstatus->show_order}}" type="button" class="btn btn-outline-info waves-effect waves-light btn-sm font-size-18 updatebtn" data-bs-toggle="modal" data-bs-target="#myModal"> <i class="mdi mdi-upload ms-1"></i></button>
                        @endif
                    @endif
                </td>

                <td><a href="{{url('/showsales')}}/{{$index->id}}/" class="btn btn-outline-warning waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-eye"></i></a></td>
                @if(Auth::user()->role!='warehouse') <td>
                    <!-- <a href="{{url('/sale')}}/{{$index->id}}/edit" class="btn btn-outline-secondary waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-pencil"></i></a> -->
                    <!-- <a href="{{url('/sales')}}/{{$index->id}}/view" class="btn btn-outline-info waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-eye"></i></a>
                    <a href="{{url('/sales')}}/{{$index->id}}/print" class="btn btn-outline-warning waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-printer"></i></a> -->
                    @if($index->status=='New' && $index->delete_status=='0')<a href="{{url('/sale')}}/{{$index->id}}/delete" onclick="return confirm('Are you sure?')" class="btn btn-outline-danger waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-trash-can-outline"></i></a>@endif

                    @if($index->delete_status=='1') {{App\Models\User::find($index->updated_by)->name}}   @endif
                </td>@endif
                <td>{{number_format( $index->grand_total, 2, '.', '' )}}</td>
                <td>{{$index->priority}}</td>
                <td>{{$index->invoice_no}}</td>
                <td>{{$index->reason}}</td>
                <td>{{$index->orderstatus->name}}</td>
                <td>{{$index->awb_no}}</td>
                <td>{{$index->box}}</td>
                <td>{{date('d M, Y H:i:s',strtotime($index->created_at))}}</td>
                @if(Auth::user()->role=='admin')<td>{{App\Models\User::find($index->created_by)->name}}</td>@endif
                
            </tr>
            @endforeach
        </tbody>
    </table>
</div>