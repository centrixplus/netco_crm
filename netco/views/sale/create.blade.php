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

<div class="row">
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                 <h4 class="text-secondary"><span id="createbutton">Create</span> {{$title}}</h4>
                 {!!Session::get('alert')!!}
                 <form  action="{{url('/sale/store')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label>Select Customer: </label>
                            <select class="form-control select2 customer" id="customer_id" name="customer_id" onchange="getCustomerInfo()">
                                <option value="">Select Customer</option>
                                @foreach($customers as $log)
                                <option value="{{$log->id}}">{{$log->name}}  {{$log->mobile}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Customer Name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="name" id="name" required="">
                                <input type="hidden" value="1" name="sale_line_count" id="sale_line_count">
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Area<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="area" id="area" required="">
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Mobile</label>
                                <input class="form-control" type="text" name="mobile" id="mobile">
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div>
                                <label>Address</label>
                                <textarea class="form-control" type="text" name="note" id="note"></textarea>
                            </div>
                        </div>  

                        <div class="col-lg-4 mb-3">
                            <label>Select Priority: </label>
                            <select class="form-control customer" id="priority" name="priority">
                                <option value="Next Day Delivery">Next Day Delivery</option>
                                <option value="Priority">Priority </option>
                            </select>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label>Order Date: <span class="text-danger">*</span></label>
                            <input value="{{date('Y-m-d')}}" type="date" name="sale_date" id="sale_date" class="form-control" readonly="" placeholder="Select Date">
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label>Order Note: </label>
                            <textarea name="comment" id="comment" class="form-control" placeholder="Enter Order Note"></textarea>
                        </div>         
                        <h5>Product List </h5>
                        <div class="table-responsive">
                            <table class="table"  style="display: none;" id="salerows">
                               <thead class="table-light">
                                   <tr>
                                       <th>Products</th>
                                       <th>QTY</th>
                                       <th>Rate</th>
                                       <th colspan="2">Price</th>
                                       <th></th>
                                   </tr>
                               </thead>
                               <tbody id="bill">
                                   {!!$list!!}
                               </tbody>
                               <tfoot>
                                   <tr>
                                       <td colspan="2"></td>
                                       <td><span class="float-end"><h6>Total:</h6></span></td>
                                       <td colspan="3"><input type="hidden" name="no_of_qty" id="no_of_qty" value="0" class="form-control" readonly="">
                                            <input type="text" name="grand_total" id="grand_total" value="0" class="form-control" readonly=""></td>
                                        
                                   </tr>
                                   <tr>
                                        <td colspan="5">
                                            <center>
                                                <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Save</button>
                                                
                                                <a onclick="return confirm('Are you sure cancel?')" href="{{url('/sale')}}" class="btn btn-secondary waves-effect waves-light resetbtn mt-2">Cancel</a>

                                                <button onclick="appendSaleRow()" type="button" class="btn btn-info waves-effect waves-light me-2 mt-2"><i class="mdi mdi-plus"> </i></button>
                                            </center>
                                        </td>
                                   </tr>
                               </tfoot>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div id="customerinfoview" class="mt-3">
                    <h5>Custome Order:</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Count</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Total</td>
                                <td id="sales_count"></td>
                                <td id="sales_amount"></td>
                            </tr>
                            <tr>
                                <td>New</td>
                                <td id="new_count"></td>
                                <td id="new_amount"></td>
                            </tr>
                            <tr>
                                <td>Processing</td>
                                <td id="processing_count"></td>
                                <td id="processing_amount"></td>
                            </tr>
                            <tr>
                                <td>Deleiverd</td>
                                <td id="delivered_count"></td>
                                <td id="delivered_amount"></td>
                            </tr>
                            <tr>
                                <td>Last Order Date:</td>
                                <td colspan="2" id="order_date"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="customersalesinfoview" class="mt-3">
                    <h5>Last Purchase Details:</h5>
                    <div id="customerpurchaselist"></div>
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

function getCustomerInfo(){
    var customer_id=$('#customer_id').val();
    if(customer_id!=''){
        $('#salerows').show();
        $.ajax({ url: "{{url('/customerinfo')}}"+'/'+customer_id,
            type: "get", cache: false, dataType: 'json',
            success: function (data) {
                $('#name').val(data.name);
                $('#mobile').val(data.mobile);
                $('#area').val(data.area);
                $('#note').val(data.note);
                
                $('#sales_count').html(data.sales_count);
                $('#new_count').html(data.new_count);
                $('#processing_count').html(data.processing_count);
                $('#delivered_count').html(data.delivered_count);

                $('#sales_amount').html(data.sales_amount);
                $('#new_amount').html(data.new_amount);
                $('#processing_amount').html(data.processing_amount);
                $('#delivered_amount').html(data.delivered_amount);

                $('#order_date').html(data.order_date);
            }
        })
    }
    else{
        $('#salerows').hide();
        $('#name').val('');
        $('#mobile').val('');
        $('#area').val('');
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


$(document).ready(function() {

    $(document).on('change','.product',function() { 
         var product_id = $(this).val(); 
         var x = $(this).data("x"); 
         $.ajax({ url: "{{url('/getproductinfo')}}"+'/'+product_id,
            type: "get", cache: false, dataType: 'json',
            success: function (data) {
            
                $('#cstock'+x).html(data.qty);
                $('#pdcstock'+x).html(data.pdc);
                $('#rate'+x).val(data.rate);
                calculatePrice(x);
                loadLastPurchase(product_id);
            }
         })
    });

    $(document).on('blur','.qty',function() { 
        var x = $(this).data("x"); 
        var qty=$('#qty'+x).val(); 
        if(qty<=0){
            
            alert('Quantity must greater than Zero');
            $('#qty'+x).val('1');
        }
        else{
             
             calculatePrice(x);
        }
        
    });

    $(document).on('change','.qty',function() {  
        var x = $(this).data("x"); 
        var qty=$('#qty'+x).val(); 
        if(qty<=0){
            
            alert('Quantity must greater than Zero');
            $('#qty'+x).val('1');
        }
        else{
             
             calculatePrice(x);
        }
    });

    $(document).on('blur','.rate',function() {  
        var x = $(this).data("x"); 
        var rate=$('#rate'+x).val();
        if(rate<0){
            alert('Rate must greater than Zero');
            $('#rate'+x).val('');
        }
        else{
            
             calculatePrice(x);
        }
        
    });
});

function loadLastPurchase(product_id) {
    var customer_id=$('#customer_id').val();
    if(customer_id!=''){
        $.ajax({ url: "{{url('/loadLastPurchase')}}"+'/'+product_id+'/'+customer_id,
            type: "get", cache: false, dataType: 'json',
            success: function (data) {
                $('#customerpurchaselist').html(data.saletable);
            }
        })
    }
    
}

function calculatePrice(x) {
    var rate=$('#rate'+x).val();
    var qty=$('#qty'+x).val();
    if(rate!='' || qty!=''){
        var price=rate*qty;
        $('#price'+x).val(price);

        var sum = 0;
        $('.price').each(function(){
            sum += parseFloat(this.value);
        });

        $('#grand_total').val(sum);

        var nqty = 0;
        $('.qty').each(function(){
            nqty += parseFloat(this.value);
        });

        $('#no_of_qty').val(nqty);
    }
    else{
        $('#price'+x).val(0);
    }
}

function removeRow(x) {
   $('#product'+x).val('');
   $('#rate'+x).val('0');
   $('#qty'+x).val('0');
   $('#price'+x).val('0');
    $('#row'+x).hide();
    calculatePrice(x);
   
}

function submitForm(argument) {
   $('form').submit();
}

</script>
@stop