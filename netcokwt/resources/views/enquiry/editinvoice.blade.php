@extends('layouts.master')
@section('title',$title)
@section('StyleContent')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/jquery-ui.min.css" rel="stylesheet" type="text/css" />
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

 <!--  Extra Large modal example -->
<div class="modal modalnew fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myExtraLargeModalLabel">Select Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="customerinfo">
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Update Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  action="{{url('/searchsaleparts')}}" method="post">
                {{csrf_field()}}  
                <div class="modal-body">
                        <div class="row">
                       
                            <div class="col-sm-12 mt-3">
                                <label for="cardnameInput">Search Part Details <span class="text-danger">*</span></label>
                                <textarea type="text" class="form-control" name="search_text" id="search_text" id="search-text" placeholder="Search... (eg: Part-number Quantity) Like (668621HB4B   2)" required=""></textarea>
                            </div>
                        </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
               <!--  <button type="button" onclick="addSaleList()" class="btn btn-primary waves-effect waves-light">Add</button> -->
            </div>
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="row">
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                 <h4 class="text-secondary"><span id="createbutton">Create</span> {{$title}}</h4>
                 {!!Session::get('alert')!!}
                 <form  action="{{url('/invoice/update')}}" onSubmit="return confirm('Do you want to save proforma invoice?') " method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <label>Select Customer: <span class="text-danger">*</span></label>
                            <input type="text" value="{{$invoice->customer->code}}-{{$invoice->customer->name}}" class="form-control" id="customer_name" name="customer_name" placeholder="Select Customer" required onclick="selectCustomer();">
                             <input type="hidden" value="{{$invoice->customer_id}}" name="customer_id" id="customer_id">
                        </div>
                        <div class="col-lg-3 mb-3">
                        	<label>Area: <span id="area" class="text-danger"></span></label><label style="margin-left: 20px;">, Mobile: <span class="text-danger" id="mobile"></span></label><br>
                        	<label>Address: <span  class="text-danger" id="note"></span></label><br>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>Invoice Date: <span class="text-danger">*</span></label>
                            <input value="{{date('Y-m-d')}}" type="date" name="invoice_date" id="invoice_date" class="form-control" readonly="" placeholder="Select Date">
                            <input type="hidden" name="invoice_id" id="invoice_id" value="{{$invoice->id}}">
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>Store: <span class="text-danger">*</span></label>
                            <input type="hidden" name="company_id" id="company_id" value="{{$invoice->company_id}}">
                            <input type="hidden" name="branch_id" id="branch_id" value="{{$invoice->branch_id}}">
                            <select class="form-control" name="store_id" id="store_id" required="">
                                <option value="">Select</option>
                                @foreach($stores as $store)
                                    <option @if($invoice->store_id==$store->id) selected="" @endif value="{{$store->id}}">{{$store->name}}</option>
                                @endforeach
                            </select>
                        </div>                        
                        
                        <div class="table-responsive" style="display: none;" id="salerows">
                        	<div class="row">
                        		<h5>Invoice Line Info:</h5>
                        		<div class="col-lg-3 mb-3">
		                        	<label>Product: <span class="text-danger">Current Stock: <span class="pstock"></span></span></label>
		                        	<input type="" class="form-control" id='productname'>
		                        	<input type="hidden" id='product'>
		                    	</div>
		                    	<div class="col-lg-2 mb-3">
		                    		<label>RSP: </label>
		                    		<input type="number" min="0" step="0.001" class="form-control" id='rsp' >
                                    <input type="hidden" name="editid" id="editid" value="0">
		                    	</div>
		                    	<div class="col-lg-2 mb-3">
		                    		<label>Order QTY: </label>
		                    		<input type="number" min="1" class="form-control" onkeyup="loadSo()" id='order_quantity'>
		                    	</div>
		                    	<div class="col-lg-2 mb-3">
		                    		<label>Target Price: </label>
		                    		<input type="number" min="0" step="0.001" class="form-control" id='target_price'>
		                    	</div>
		                    	<div class="col-lg-2 mb-3">
		                    		<label>SO Quantity: </label>
		                    		<input type="number" min="1" class="form-control" id='so_quantity'>
		                    	</div>
		                    	<div class="col-lg-1 mb-3">
		                    		<label>Invoiced: </label><br>
		                    		<input id="is_invoiced" type="checkbox" name="is_invoiced" checked="">
                                    <button onclick="appendInvoiceRow()" type="button" class="btn btn-sm btn-info waves-effect waves-light" style="margin-left: 10px;"><i class="mdi mdi-plus"> </i></button>
		                    	</div>
                        	</div>
                        	<h5>Selected List:  </h5>
                            <table class="table"  >
                               <thead class="table-light">
                                   <tr>
                                       <th>Products</th>
                                       <th>Description</th>
                                       <th>RSP</th>
                                       <th>Order QTY</th>
                                       <th>Target Price</th>
                                       <th>SO QTY</th>
                                       <th>Total Price</th>
                                       <th>Invoiced</th>
                                       <th></th>
                                   </tr>
                               </thead>
                               <tbody id="invoicelines">
                        
                               </tbody>
                               <tfoot>
                                   <tr>
                                       <td colspan="4"><textarea name="comment" id="comment" class="form-control" placeholder="Enter Note"></textarea></td>
                                       <td><span class="float-end"><h6>Total:</h6></span></td>
                                       <td colspan="3"><input type="hidden" name="no_of_qty" id="no_of_qty" value="0" class="form-control" readonly="">
                                            <input type="text" name="grand_total" id="grand_total" value="0" class="form-control" readonly=""></td>
                                        
                                   </tr>
                                   <tr>
                                        <td colspan="5">
                                            <center>
                                                <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Save</button>
                                                
                                                <a onclick="return confirm('Are you sure cancel?')" href="{{url('/createinvoice')}}" class="btn btn-secondary waves-effect waves-light resetbtn mt-2">Cancel</a>

                                            </center>
                                        </td>
                                   </tr>
                               </tfoot>
                            </table>
                        </div>
                    </div>
                </form>
                  <div class="row">
                    <div class="col-md-4" id="customerinfoview">
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
                                    <td id="cinvoice_count"></td>
                                    <td id="cinvoice_amount"></td>
                                </tr>

                                <tr>
                                    <td>New</td>
                                    <td id="new_invoice_count"></td>
                                    <td id="new_cinvoice_amount"></td>
                                </tr>

                                <tr>
                                    <td>Approved</td>
                                    <td id="apvd_invoice_count"></td>
                                    <td id="apvd_cinvoice_amount"></td>
                                </tr>

                                <tr>
                                    <td>Sale Order</td>
                                    <td id="so_invoice_count"></td>
                                    <td id="so_cinvoice_amount"></td>
                                </tr>
                                <tr>
                                    <td>Last Invoice Date:</td>
                                    <td colspan="2" id="cinvoice_date"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-8" id="invoicelists">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div id="customersalesinfoview" class="mt-3">
                    <h5>Product Details:</h5>
                    <table class="table">
                    	 <thead>
                            <tr>
                                <th>Info</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<tr>
                        		<td>Stock</td>
                        		<td class="text-danger pstock"></td>
                        	</tr>
                            <tr>
                                <td>Transit Quantity</td>
                                <td class="text-danger" id="tqty"></td>
                            </tr>
                        	<tr>
                        		<td>PDC</td>
                        		<td class="text-danger" id="ppdc"></td>
                        	</tr>
                        	<tr>
                        		<td>Cost</td>
                        		<td class="text-danger" id="pcost"></td>
                        	</tr>
                        </tbody>
                    </table>
                </div>
                <div id="last5PIhtmlview">
                    
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
<script src="{{asset('/assets')}}/libs/jquery-ui.min.js"></script>
<script>
getCustomerInfo();
loadInvoiceLines({{$invoice->id}});
function getCustomerInfo(){
    var customer_id=$('#customer_id').val();
    if(customer_id!=''){
        $('#salerows').show();
        $.ajax({ url: "{{url('/customerinfo')}}"+'/'+customer_id,
            type: "get", cache: false, dataType: 'json',
            success: function (data) {
                $('#mobile').text(data.mobile);
                $('#area').text(data.area);
                $('#note').text(data.note);

                $('#cinvoice_date').text(data.invoice_date);
                $('#cinvoice_amount').text(data.invoice_amount);
                $('#cinvoice_count').text(data.invoice_count);

                $('#new_invoice_count').text(data.invoice_count_new);
                $('#new_cinvoice_amount').text(data.invoice_amount_new);

                $('#apvd_invoice_count').text(data.invoice_count_apvd);
                $('#apvd_cinvoice_amount').text(data.invoice_amount_apvd);

                $('#so_invoice_count').text(data.invoice_count_so);
                $('#so_cinvoice_amount').text(data.invoice_amount_so);
                
                loadInvoiceLines({{$invoice->id}});
                loadInvoiceLinesCustomer(customer_id);
            }
        })
    }
    else{
        $('#mobile').text('');
        $('#area').text('');
        $('#note').text('');
        $('#cinvoice_date').text('');
        $('#cinvoice_amount').text('');
        $('#cinvoice_count').text('');
    }
   
}

function loadInvoiceLines(invoice_id){
	$.ajax({ url: "{{url('/loadInvoiceLinesAppointment')}}"+'/'+invoice_id,
        type: "get", cache: false, dataType: 'json',
        success: function (data) {
           $('#invoicelines').html(data.invoicelineslists);
           $('#invoicelists').html(data.invoicelists);
           $('#grand_total').val(data.invoiceamount);
           if(data.invoiceamount > 0){
           		$('.inputDisabled').prop("disabled", false);
           }
           else{
           		$('.inputDisabled').prop("disabled", true);
           }
         }
    })
}

function loadInvoiceLinesCustomer(customer_id){
    $.ajax({ url: "{{url('/loadInvoiceLines')}}"+'/'+customer_id,
        type: "get", cache: false, dataType: 'json',
        success: function (data) {
           $('#invoicelists').html(data.invoicelists);
         }
    })
}

function loadSo(){
	var order_quantity=$('#order_quantity').val();
	$('#so_quantity').val(order_quantity);
}

function appendInvoiceRow(){
    var customer_id=$('#customer_id').val();
    var product=$('#product').val();
    var order_quantity=$('#order_quantity').val();
    var so_quantity=$('#so_quantity').val();
    var target_price=$('#target_price').val();
    var editid=$('#editid').val();
    var invoice_id=$('#invoice_id').val();
    var rsp=$('#rsp').val();
    if($("#is_invoiced").is(':checked')){
        is_invoiced=1;
    }
    else{
        is_invoiced=0;
    }
    
    if(product==''){
        alert("Select Product");
    }
    else if(order_quantity==''){
        alert("Enter Order Quantity");
    }
    else if(so_quantity==''){
        alert("Enter SO Quantity");
    }
    else if(target_price==''){
        alert("Enter Target Price");
    }
    else if(rsp==''){
        alert("Enter RSP Price");
    }
    else if(rsp <= 0){
        alert("RSP Price more than 0");
    }
    else if(target_price < 0){
        alert("Target Price should postive");
    }
    else if (!Number.isInteger(Number(so_quantity))) {
        alert("SO Quantity should be a whole number (no decimals)");
    }
    else if (!Number.isInteger(Number(order_quantity))) {
        alert("Order Quantity should be a whole number (no decimals)");
    }
    else if(so_quantity < 0){
        alert("SO Quantity should postive");
    }
    else if(order_quantity < 0){
        alert("Order Quantity should postive");
    }
    else {
        $.ajax({ url: "{{url('/updateInvoiceLine')}}"+'/'+customer_id+'/'+product+'/'+order_quantity+'/'+so_quantity+'/'+target_price+'/'+is_invoiced+'/'+editid+'/'+invoice_id+'/'+rsp,
            type: "get", cache: false, dataType: 'json',
            success: function (data) {
               $('#product').val('');
               $('#productname').val('');
               $('#order_quantity').val('');
               $('#so_quantity').val('');
               $('#target_price').val('');

                $('.pstock').html('');
                $('#ppdc').html('');
                $('#pcost').html('');
                $('#rsp').val('');
                $('#editid').val('0');
               loadInvoiceLines(invoice_id);
            }
        })
    }
}

function getProductInfo() {
	var product_id=$('#product').val();
	 $.ajax({ url: "{{url('/getproductinfo')}}"+'/'+product_id,
            type: "get", cache: false, dataType: 'json',
            success: function (data) {
                $('.pstock').html(data.qty);
                $('#ppdc').html(data.pdc);
                $('#pcost').html(data.cost);
                $('#rsp').val(data.rate);
                $('#tqty').html(data.tqty);
                $('#last5PIhtmlview').html(data.last5PIhtml);
            }
         })
}
function getProductInfoEdit() {
    var product_id=$('#product').val();
     $.ajax({ url: "{{url('/getproductinfo')}}"+'/'+product_id,
            type: "get", cache: false, dataType: 'json',
            success: function (data) {
                $('.pstock').html(data.qty);
                $('#ppdc').html(data.pdc);
                $('#pcost').html(data.cost);
                $('#tqty').html(data.tqty);
                $('#last5PIhtmlview').html(data.last5PIhtml);
            }
         })
}



function deleteInvoiceLine(product_id) {
    var invoice_id=$('#invoice_id').val();
	if (confirm('Are you sure you want to delete this?')) {
		var customer_id=$('#customer_id').val();
		 $.ajax({ url: "{{url('/deleteInvoiceLine')}}"+'/'+product_id,
	            type: "get", cache: false, dataType: 'json',
	            success: function (data) {
	                loadInvoiceLines(invoice_id);
	            }
	         })
	}
}

function editInvoiceLine(id,product_id) {
    $("#editid").val(id);
    
    var name=$('#name'+id).text(); $("#productname").val(name);
    $("#product").val(product_id);
    var rsp=$('#rsp'+id).text(); $("#rsp").val(rsp);
    var quantity_po=$('#quantity_po'+id).text(); $("#order_quantity").val(quantity_po);
    var quantity_so=$('#quantity_so'+id).text(); $("#so_quantity").val(quantity_so);
    var target_price=$('#target_price'+id).text(); $("#target_price").val(target_price);
    var is_invoiced=$('#is_invoiced'+id).text();
    if(is_invoiced==' No '){
        $("#is_invoiced").attr("checked", false);
    }
    else{
         $("#is_invoiced").attr("checked", true);
    }
    getProductInfoEdit();
}

</script>

<script type="text/javascript">

    // CSRF Token
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function(){

      $( "#productname" ).autocomplete({
        source: function( request, response ) {
          // Fetch data
          if(request.term.length>5){
             $.ajax({
            url:"{{route('autocomplete')}}",
            type: 'post',
            dataType: "json",
            data: {
               _token: CSRF_TOKEN,
               search: request.term
            },
            success: function( data ) {
               response( data );
            }
          });
          }
         
        },
        select: function (event, ui) {
           // Set selection
           $('#productname').val(ui.item.label); // display the selected text
           $('#product').val(ui.item.value); // save selected id to input
           getProductInfo();
           return false;
        }
      });

    });

function selectCustomer() {
    var company_id=$('#company_id').val();
    var branch_id=$('#branch_id').val();

    if(company_id==''){
        company_id=0;
    }

    if(branch_id==''){
        branch_id=0;
    }
    $(".modalnew").modal('show'); 
    $.ajax({ url: "{{url('customersearch')}}/"+company_id+'/'+branch_id,
    type: "get", cache: false, dataType: 'json', 
    success: function (data) { 
        $("#customerinfo").html(data.html);
    },error:function(){ 
        // 
    }
    })
}

function searchcustomers(value){
    var value = value.toLowerCase();
  $(".search-areas table tr").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
  });
}

function customerselect(id,name,code){
    var cus_code=code+'-'+name;
    $('#customer_name').val(cus_code);
    $('#customer_id').val(id);
    getCustomerInfo();
    $(".modalnew").modal('hide'); 
}
    </script>
@stop