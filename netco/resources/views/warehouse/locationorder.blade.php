<table id="datatable-buttons" class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>S.No</th>
            <th>Name</th>
            <th>Count (NOS)</th>
            <th>Amount (AED)</th>
            <th>View</th>
        </tr>
    </thead>           
    <tbody>
        <?php $x=1; ?>
        @foreach ($locaions as $index)
        <?php
           $count= $index->sale->where('delete_status','0')->where('status_id','2')->count();
        ?>
            @if($count>0)
            <tr>
                <td>{{$x++}}</td>
                <td>{{$index->name}}</td>
                <td>{{$count}}</td>
                <td>{{$index->sale->where('delete_status','0')->where('status_id','2')->sum('grand_total')}}</td>
                <td><a href="{{url('/warehouseOrders')}}/0/{{$index->id}}" class="btn btn-outline-warning waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-eye"></i></a></td>
            </tr>
            @endif
        @endforeach
    </tbody>
</table>