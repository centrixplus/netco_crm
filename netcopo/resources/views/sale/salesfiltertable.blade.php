 @foreach ($lists as $list)
 	<div class="accordion" id="accordionExample">
 	  @if($list->sale->whereBetween('sale_date',[$fromdate, $todate])->count() >0)
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$list->id}}" aria-expanded="true" aria-controls="collapse{{$list->id}}">
                {{$list->name}} - ({{$list->sale->whereBetween('sale_date',[$fromdate, $todate])->count()}})
                </button>
            </h2>
            <div id="collapse{{$list->id}}" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="text-muted">
                        <table id="datatable-buttons1" style="font-size: 11px !important;border-collapse: collapse !important;" class="table table-bordered">
				        <thead class="table-light">
				            <tr>
				                <th>Order ID</th>
				                <th>Customer</th>
				                @if(Auth::user()->role=='admin')<th>Code</th>@endif
				                <th>Area</th>
				                <th>Date</th>
				                <th>Update</th>
				                <th>View</th>
				                @if(Auth::user()->role!='warehouse')<th>Delete</th>@endif
				                <th>Total Amount</th>
				                <th>Priority</th>
				                <th>Document No</th>
				                <th>Reason</th>
				                <th>Status</th>
				                <th>Created at</th>
				                @if(Auth::user()->role=='admin')<th>Created By</th>@endif
				            </tr>
				        </thead>           
				        <tbody>
				            <?php $x=1; ?>
				            @foreach ($list->sale->whereBetween('sale_date',[$fromdate, $todate]) as $index)
				            <tr class="@if($index->delete_status==1) bg-danger @else {{$index->orderstatus->color}} @endif">
				                <td>{{App\Models\Sale::saleno($index->id)}}</td>
				                <td>{{$index->customer->name}}</td>
				                @if(Auth::user()->role=='admin')<td>{{$index->customer->code}}</td>@endif
				                <td>{{$index->customer->area}}</td>
				                <td>{{date('d M, Y',strtotime($index->sale_date))}}</td>

				               <td> @if($index->status!='Delivered')<button data-id="{{$index->id}}" data-invoice_no="{{$index->invoice_no}}" data-status="{{$index->status}}" type="button" class="btn btn-outline-info waves-effect waves-light btn-sm font-size-18 updatebtn" data-bs-toggle="modal" data-bs-target="#myModal"> <i class="mdi mdi-upload ms-1"></i></button>@endif</td>
				                <td><a href="{{url('/showsales')}}/{{$index->id}}/" class="btn btn-outline-warning waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-eye"></i></a></td>
				                @if(Auth::user()->role!='warehouse') <td>
				                    <!-- <a href="{{url('/sale')}}/{{$index->id}}/edit" class="btn btn-outline-secondary waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-pencil"></i></a> -->
				                    <!-- <a href="{{url('/sales')}}/{{$index->id}}/view" class="btn btn-outline-info waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-eye"></i></a>
				                    <a href="{{url('/sales')}}/{{$index->id}}/print" class="btn btn-outline-warning waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-printer"></i></a> -->
				                    @if($index->status=='New' && $index->delete_status=='0')<a href="{{url('/sale')}}/{{$index->id}}/delete" onclick="return confirm('Are you sure?')" class="btn btn-outline-danger waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-trash-can-outline"></i></a>@endif

				                    @if($index->delete_status=='1') {{App\Models\User::find($index->updated_by)->name}}   @endif
				                </td>@endif
				                <td>{{$index->grand_total}}</td>
				                <td>{{$index->priority}}</td>
				                <td>{{$index->invoice_no}}</td>
				                <td>{{$index->reason}}</td>
				                <td>{{$index->orderstatus->name}}</td>
				                <td>{{date('d M, Y H:i:s',strtotime($index->created_at))}}</td>
				                @if(Auth::user()->role=='admin')<td>{{App\Models\User::find($index->created_by)->name}}</td>@endif
				                
				            </tr>
				            @endforeach
				        </tbody>
				    </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
 @endforeach