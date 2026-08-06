<?php $x=1; ?>
    @foreach ($indexes as $index)
    <tr>
    <td>{{$x++}}</td>


    <td>@if($index->pr_no!='') {{$index->pr_no}} @else {{$index->id}} @endif</td>

    @if(Auth::user()->role!='Customer')
    <td>@if($index->customer){{$index->customer->name}}@endif</td>
    @endif

    <td>@if($index->customer){{$index->customer->code}}@endif</td>

    <td>{{date('d-m-Y',strtotime($index->pr_date))}}</td>

    <td>{{$index->requesttype->name}}</td>

    <td>{{$index->priority}}</td>

    <td>{{$index->prstatus->name}}</td>

    <td>{{date('d-m-Y H:i:s',strtotime($index->created_at))}}</td>

    <td>{{$index->user->name}} </td>

    <td><a target="_blank" href="{{url('/view/purchaserequest')}}/{{$index->id}}" class="btn btn-outline-primary waves-effect waves-light btn-sm"><i class="mdi mdi-eye"></i></a></td>

    <td>@if($index->prstatus_id=='1')<a href="{{url('/edit/purchaserequest')}}/{{$index->id}}" onclick="return confirm('Are you sure Edit PR?')" class="btn btn-outline-info waves-effect waves-light btn-sm"><i class="mdi mdi-pencil"></i></a>@endif</td>

    @if(Auth::user()->role=='admin' || Auth::user()->role=='PR')

    <td>@if($index->prstatus_id=='1')
        <a href="{{url('status/purchaserequest')}}/{{$index->id}}/2" onclick="return confirm('Are you sure Approve PR?')" class="btn btn-outline-success waves-effect waves-light btn-sm"><i class="dripicons-thumbs-up"></i></a>

        <a href="{{url('status/purchaserequest')}}/{{$index->id}}/3" onclick="return confirm('Are you sure Rejrct PR?')" class="btn btn-outline-warning waves-effect waves-light btn-sm"><i class="dripicons-thumbs-down"></i></a>
        @endif
    </td>
    @endif
    <td>@if($index->prstatus_id=='1')<a href="{{url('/delete/purchaserequest')}}/{{$index->id}}" onclick="return confirm('Are you sure?')" class="btn btn-outline-danger waves-effect waves-light btn-sm"><i class="mdi mdi-trash-can-outline"></i></a>@endif</td>

    </tr>
    @endforeach