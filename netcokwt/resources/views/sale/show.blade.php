@extends('layouts.master')
@section('title',$title)
@section('StyleContent')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('PageContent')
<style type="text/css">
tr.ex2:hover, a.ex2:active {cursor: pointer; background-color:#adf7a9  ! important;font-size: 115%;}
tr.selected {background-color:#adf7a9  ! important;}
</style>
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-16">{{$title}}</h4>
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Details</a></li>
                    <li class="breadcrumb-item active">{{$title}}</li>
            </ol>
        </div>

    </div>
</div>
</div>

<div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Update Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  action="{{url('/updatestatus')}}" method="post">
                {{csrf_field()}}  
                <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <input type="hidden" name="sale_id" id="sale_id">
                                <input type="hidden" name="user_role" id="user_role" value="{{Auth::user()->role}}">
                                <div>
                                    <label>Status:</label>
                                    <select class="form-control"  name="order_status" id="order_status" required="" onchange="changeStatus()"> 
                                        @foreach($statuses as $sts)
                                            <option class="allsts s_{{$sts->show_order}} c_{{$sts->id}}" docs="{{$sts->is_document}}" delivery_tab="{{$sts->is_delivery}}" reasons="{{$sts->is_reason}}" value="{{$sts->name}}">{{$sts->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-3 inv_no" style="display: none;">
                                    <label>Document Number:</label>
                                    <input class="form-control" type="text" value="0" placeholder="Enter Document Number" name="invoice_no" id="invoice_no" required="">
                                </div>

                                <div class="mt-3 inv_no" style="display: none;">
                                    <label>Invoice Amount:</label>
                                    <input class="form-control" type="text" value="0" placeholder="Enter Invoice Amount" name="invoice_amount" id="invoice_amount" required="">
                                </div>

                                <div id="reason_tab" class="mt-3" style="display: none;">
                                    <label>Reason:</label>
                                    <textarea class="form-control" name="reason" id="reason"></textarea>
                                </div>
                                <div id="del_tab" style="display: none;">
                                    <div  class="mt-3" >
                                        <label>AWB Number:</label>
                                        <input class="form-control" type="text" placeholder="Enter AWB Number" name="awb_no" id="awb_no">
                                    </div>
                                    <div  class="mt-3">
                                        <label>No.of.Box:</label>
                                        <input class="form-control" type="number" min="1" placeholder="Enter No.of.Box" name="box" id="box">
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
            </div>
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
            <h4 class="text-secondary"><span id="createbutton">View</span> {{$title}}</h4>
            {!!Session::get('alert')!!}
            <div class="row">
                <div class="col-sm-12 pt-3">
                    <div class="row">
                       <div class="col-lg-3 mb-3">
                            <div>
                                <label>Order ID:</label>
                                @if($sale->sale_no!='') {{$sale->sale_no}} @else {{App\Models\Sale::saleno($sale->id)}} @endif
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Name:</label>
                                {{$sale->customer->name}}
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Code:</label>
                                {{$sale->customer->code}}
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Mobile:</label>
                               {{$sale->customer->mobile}}
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Area:</label>
                                {{$sale->customer->area}}
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Address:</label>
                                {{$sale->customer->note}}
                            </div>
                        </div>  

                        <div class="col-lg-3 mb-3">
                            <label>Priority: </label>
                             <span>{{$sale->priority}}</span>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>Order Date:</label>
                            {{date('d-m-Y',strtotime($sale->sale_date))}}
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>Order Note: </label>
                            {{$sale->note}}
                        </div> 

                        <div class="col-lg-3 mb-3">
                            <label>Order Status: </label>
                            <span class="{{$sale->orderstatus->color}}">{{$sale->status}}</span>
                        </div>         

                        <div class="col-lg-3 mb-3">
                            <label>Document No: </label>
                            {{$sale->invoice_no}}
                        </div> 

                        <div class="col-lg-3 mb-3">
                            <label>Total No of  Quantity: </label>
                            {{$sale->no_of_qty}}
                        </div> 

                        <div class="col-lg-3 mb-3">
                            <label>Grand Total: </label>
                            {{$sale->grand_total}}
                        </div> 

                        <div class="col-lg-3 mb-3">
                            <label>Warehouse Person: </label>
                            @if($sale->warehousepersion){{$sale->warehousepersion->name}}@endif
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>Sale From: </label>
                            {{$sale->sales_from}}
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>Created By: </label>
                            {{App\Models\User::find($sale->created_by)->name}}
                        </div>

                        <div class="col-lg-5 mb-3">
                            <label>Created Date & Time: </label>
                            {{date('d-m-Y H:i:s',strtotime($sale->created_at))}}
                        </div>

                        

                        <h5>Sale List </h5>
                        <table id="datatable-buttons" class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Product Name</th>
                                    <th>Description</th>
                                    <th>Rate</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                            </thead>           
                            <tbody>
                                <?php $x=1; ?>
                                @foreach ($sale_lines as $index)

                                <tr>
                                    <td>{{$x++}}</td>
                                    <td>{{$index->product->name}}</td>
                                    <td>{{$index->product->note}}</td>
                                    <td>{{$index->rate}}</td>
                                    <td>{{$index->quantity}}</td>
                                    <td>{{$index->price}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>

                <h5>Status Log List <button data-id="{{$sale->id}}" data-invoice_no="{{$sale->invoice_no}}" data-status="{{$sale->status}}" data-status_id="{{$sale->status_id}}" data-show_status_id="{{$sale->orderstatus->show_order}}" type="button" class="btn btn-outline-info waves-effect waves-light btn-sm font-size-18 updatebtn" data-bs-toggle="modal" data-bs-target="#myModal"> <i class="mdi mdi-upload ms-1"></i></button></h5>

                <table class="table table-bordered table-responsive mt-3">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Status</th>
                            <th>Document No</th>
                            <th>Invoice Amount</th>
                            <th>Reason</th>
                            <th>Warehouse Assign</th>
                            <th>AWB</th>
                            <th>No.of.Boxes</th>
                            <th>Updated Date & Time</th>
                            <th>Updated By</th>
                        </tr>
                    </thead>           
                    <tbody>
                        <?php $x=1; ?>
                        @foreach ($statuslogs as $index)

                        <tr>
                            <td>{{$x++}}</td>
                            <td>{{$index->status}}</td>
                            <td>{{$index->document_no}}</td>
                            <td>{{$index->invoice_amount}}</td>
                            <td>{{$index->reason}}</td>
                            <td>@if($index->sale->warehousepersion){{$index->sale->warehousepersion->name}}@endif</td>
                            <td>{{$index->awb_no}}</td>
                            <td>{{$index->box}}</td>
                            <td>{{date('d-m-Y H:i:s',strtotime($index->created_at))}}</td>
                            <td>{{App\Models\User::find($index->created_by)->name}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div> 
</div>

@endsection
@section('ScriptContent')
<script src="{{asset('/assets')}}/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="{{asset('/assets')}}/libs/jszip/jszip.min.js"></script>
<script src="{{asset('/assets')}}/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="{{asset('/assets')}}/libs/pdfmake/build/vfs_fonts.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>
<script src="{{asset('/assets')}}/js/pages/form-advanced.init.js"></script>
<script>
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

function getCustomerInfo(cart_id,company){
    var customer_id=$('#customer_id').val();
    if(customer_id!=''){
        $.ajax({ url: "{{url('/customerinfo')}}"+'/'+customer_id,
            type: "get", cache: false, dataType: 'json',
            success: function (data) {
                $('#name').val(data.name);
                $('#mobile').val(data.mobile);
                $('#email').val(data.email);
                $('#note').val(data.note);
            }
        })
    }
    else{
        $('#name').val('');
        $('#mobile').val('');
        $('#email').val('');
        $('#note').val('');
    }
   
}

function appendSaleRow() {
    var sale_line_count=$('#sale_line_count').val();
    $.ajax({ url: "{{url('/appendsalerow')}}"+'/'+sale_line_count,
        type: "get", cache: false, dataType: 'json',
        success: function (data) {
            $('#bill').append(data.salerow);
            $('#sale_line_count').val(data.sale_line_count);
            $(".select2").select2();
        }
    })
}

$(document).ready(function() {
    
    $(document).on('blur','.qty',function() {  
        var nqty = 0;
        $('.qty').each(function(){
            nqty += parseFloat(this.value);
        });
        $('#no_of_qty').val(nqty);
    });

});


function removeRow(x) {
   $('#product'+x).val('');
   $('#qty'+x).val('0');
   $('#row'+x).hide();
}

$('.updatebtn').click(function() {
    var id = $(this).data("id"); $("#sale_id").val(id);
    var status_id = $(this).data("status_id");
    var show_status_id = $(this).data("show_status_id");
    var user_role=$('#user_role').val();
    $('.allsts').hide();
    $('.c_'+status_id).show();
    $('.c_'+show_status_id).show();
    $('.s_'+show_status_id).show();
    if(status_id==8){
        $('.s_100').show();
    }
    if(show_status_id==100){
        if(user_role=='admin'){
            $('.s_9').show();
        }
    }
    var status = $(this).data("status"); $("#order_status").val(status);
    var invoice_no = $(this).data("invoice_no"); $("#invoice_no").val(invoice_no);
});

function changeStatus() {
    var order_status=$('#order_status').val();
    var docs=$('#order_status').find('option:selected').attr('docs');
    var reasons=$('#order_status').find('option:selected').attr('reasons');
    var delivery_tab=$('#order_status').find('option:selected').attr('delivery_tab');
    
    if(docs=='1'){
        $('.inv_no').show();
    }
    else{
        $('.inv_no').hide();
    }

    if(reasons=='1'){
        $('#reason_tab').show();
    }
    else{
        $('#reason_tab').hide();
        $('#reason').val('');
    }

    if(delivery_tab=='1'){
        $('#del_tab').show();
        $('#box').prop('required',true);
        $('#awb_no').prop('required',true);
        $('#invoice_no').prop('readonly',true);
    }
    else{
        $('#delivery_tab').hide();
        $('#awb_no').val('');
        $('#box').val('');
        $('#box').prop('required',false);
        $('#awb_no').prop('required',false);
        $('#invoice_no').prop('readonly',false);
    }
}
</script>
@stop