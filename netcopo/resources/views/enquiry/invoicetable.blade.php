<?php $x=1; ?>
    @foreach ($indexes as $index)
    <tr class="@if($index->sale) {{$index->sale->orderstatus->color}} @else {{$index->invoicestatus->color}} @endif">
    <td>{{$x++}}</td>
    <td>@if($index->invoice_no!='') {{$index->invoice_no}} @else {{$index->id}} @endif</td>
    @if(Auth::user()->role!='Customer')<td>{{$index->customer->name}}</td>@endif
    <td>{{$index->customer->code}}</td>
    @if(Auth::user()->role!='Customer')<td>@if($index->customer->location){{$index->customer->location->name}}@endif</td>@endif
    <td>@if($index->store){{$index->store->name}}@endif</td>
    <td>{{date('d-m-Y',strtotime($index->invoice_date))}}</td>
    <td>@if($index->sale) {{$index->sale->orderstatus->name}} @else{{$index->invoicestatus->name}}@endif</td>
    <td>{{$index->amount_so}}</td>
    <td>@if($index->sale){{$index->sale->sale_no}}@endif</td>
    <td>@if($index->sale){{$index->sale->invoice_amount}}@endif</td>
    <td>{{date('d-m-Y H:i:s',strtotime($index->created_at))}}</td>
    <td>{{$index->user->name}} </td>
    <td>
        @if($index->invoicestatus->status_order=='1')
            @if($index->invoicelines->where('is_invoiced','1')->sum('quantity_so')!=0)
                <a href="{{url('/invoiceapprove')}}/{{$index->id}}" onclick="return confirm('Are you sure?')" class="btn btn-outline-primary waves-effect waves-light btn-sm d-none">Approve</a>

                <button data-id="{{$index->id}}" type="button" class="btn btn-outline-info waves-effect waves-light btn-sm updatebtn" data-bs-toggle="modal" data-bs-target="#myModal"> <i class="mdi mdi-upload ms-1"></i></button>
            @else
                <h4>LOS</h4>
            @endif
        @elseif($index->invoicestatus->status_order=='2')
        
            <a href="{{url('/invoicetoso')}}/{{$index->id}}" onclick="return confirm('Are you sure?')" class="btn btn-outline-success waves-effect waves-light btn-sm">Convert SO</a>
        @elseif($index->invoicestatus->status_order=='3')
            <a href="{{url('/showsales')}}/{{$index->sale_id}}" target="_blank" class="btn btn-outline-secondary waves-effect waves-light btn-sm">SO</a>
        
        @endif

    </td>
    <td><a target="_blank" href="{{url('/viewInvoice')}}/{{$index->id}}" class="btn btn-outline-info waves-effect waves-light btn-sm"><i class="mdi mdi-eye"></i></a></td>
    <td>@if($index->status=='1')<a target="_blank" href="{{url('/editinvoice')}}/{{$index->id}}" class="btn btn-outline-warning waves-effect waves-light btn-sm"><i class="mdi mdi-pencil"></i></a>@endif</td>
    <td>@if($index->status=='1')<a href="{{url('/deleteInvoice')}}/{{$index->id}}" onclick="return confirm('Are you sure?')" class="btn btn-outline-danger waves-effect waves-light btn-sm"><i class="mdi mdi-trash-can-outline"></i></a>@endif</td>
    </tr>
    @endforeach