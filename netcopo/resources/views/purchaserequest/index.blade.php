@extends('layouts.master')
@section('title',$title)
@section('StyleContent')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/jquery-ui.min.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    .tableFixHead          { overflow: auto; height: 500px; }
.tableFixHead thead th { position: sticky; top: 0; z-index: 1; }

/* Just common table stuff. Really. */
table  { border-collapse: collapse; width: 100%; }
th, td { padding: 8px 16px; }
th     { background:#eee; }
</style>
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

<div id="exampleModalFullscreen" class="modal fade" tabindex="-1" aria-labelledby="#exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><span id="partname"></span> Product Info</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="partinfoview">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
    </div>
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

<div class="row">
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                 <h4 class="text-secondary"><span id="createbutton">Create</span> {{$title}}</h4>
                 {!!Session::get('alert')!!}
                 <form  action="{{url('/purchaserequest/store')}}" onSubmit="return confirm('Do you want to save Puechase Request?') " method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <label>Select Customer:</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Select Customer" onclick="selectCustomer();">
                             <input type="hidden" name="customer_id" id="customer_id">
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label>Priority: <span class="text-danger">*</span></label>
                            <select name="priority" id="priority" class="form-control" required="">
                                <option value="">Select Priority</option>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label>Request Type: <span class="text-danger">*</span></label>
                            <select name="request_type" id="request_type" class="form-control" required="">
                                <option value="">Select Request Type</option>
                                @foreach($requesttypes as $log)
                                    <option value="{{$log->id}}">{{$log->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label>Purchase Request Date: <span class="text-danger">*</span></label>
                            <input value="{{date('Y-m-d')}}" type="date" name="pr_date" id="pr_date" class="form-control" readonly="" placeholder="Select Date">
                            <input type="hidden" name="pr_id" id="pr_id" value="0">
                        </div> 

                        <div class="col-lg-3 mb-3 d-none">
                            <label>Store: <span class="text-danger">*</span></label>
                            <input type="hidden" name="company_id" id="company_id" value="{{Auth::user()->company_id}}">
                            <input type="hidden" name="branch_id" id="branch_id" value="{{Auth::user()->branch_id}}">
                            <select class="form-control" name="store_id" id="store_id" required="">
                                <option value="">Select</option>
                                @foreach($stores as $store)
                                    <option @if(Auth::user()->store_id==$store->id) selected="" @endif value="{{$store->id}}">{{$store->name}}</option>
                                @endforeach
                            </select>
                        </div>                       
                        
                        <div class="table-responsive" id="salerows">
                        	<div class="row">
                        		<h5>{{$title}} Line Info:</h5>
                        		<div class="col-lg-3 mb-3">
		                        	<label>Product: <span class="text-danger">Current Stock: <span class="pstock"></span></span></label>
		                        	<input type="" class="form-control" id='productname'>
		                        	<input type="hidden" id='product'>
		                    	</div>
		                    	<div class="col-lg-2 mb-3">
		                    		<label>Order QTY: </label>
		                    		<input type="number" min="1" step="1" class="form-control" id='order_quantity'>
		                    	</div>
		                    	<div class="col-lg-3 mb-3">
		                    		<label>Target Price: </label>
		                    		<input type="number" step="0.001" class="form-control" id='target_price'>
		                    	</div>
		                    	<div class="col-lg-3 mb-3">
		                    		<label>Note: </label>
                                    <textarea name="notes" id="notes" class="form-control" placeholder="Enter Note"></textarea>
                                    <input type="hidden" value="0" name="editid" id="editid">
		                    	</div>
                                <div class="col-lg-1 mt-5">
                                    <button onclick="appendInvoiceRow()" type="button" class="btn btn-sm btn-info waves-effect waves-light" style="margin-left: 10px;"><i class="mdi mdi-plus"> </i></button>
                                </div>
                        	</div>
                        	<h5>Selected List:  </h5>
                            <div class="tableFixHead">
                            <table class="table"  >
                               <thead class="table-light">
                                   <tr>
                                       <th>Products</th>
                                       <th>Description</th>
                                       <th>Order QTY</th>
                                       <th>Target Price</th>
                                       <th>Note</th>
                                       <th>Action</th>
                                   </tr>
                               </thead>
                               <tbody id="purchaselines">
                        
                               </tbody>
                               <tfoot>
                                   <tr>
                                       <td colspan="6"><textarea name="comment" id="comment" class="form-control" placeholder="Enter Note"></textarea></td>
                                    
                                        
                                   </tr>
                                   <tr>
                                        <td colspan="5">
                                            <center>
                                                <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2 inputDisabled">Save</button>
                                                
                                                <a onclick="return confirm('Are you sure cancel?')" href="{{url('/createinvoice')}}" class="btn btn-secondary waves-effect waves-light resetbtn mt-2">Cancel</a>

                                            </center>
                                        </td>
                                   </tr>
                               </tfoot>
                            </table>
                            </form>
                        </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                
                <div id="customersalesinfoview" class="mt-3">
                    <h5>Product Details: <button type="button" onclick="showproductwindow()" class="btn-sm btn-info waves-effect waves-light" style="display: none;" data-bs-toggle="modal" id="productbtn" data-bs-target="#exampleModalFullscreen">View</button></h5>
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
                        	<tr style="display: none;">
                        		<td>Cost</td>
                        		<td class="text-danger" id="pcost"></td>
                        	</tr>
                        </tbody>
                    </table>
                </div>
                <div class="old5pr">
                    
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
function getCustomerInfo(){
    var customer_id=$('#customer_id').val();
    if(customer_id==''){
        customer_id=0;
    }
    loadpurchaselines(customer_id);
}

function loadpurchaselines(customer_id){
    var pr_id=$('#pr_id').val();
	$.ajax({ url: "{{url('/loadpurchaselines')}}"+'/'+customer_id+'/'+pr_id,
        type: "get", cache: false, dataType: 'json',
        success: function (data) {
           $('#purchaselines').html(data.purchaserequestlineslist);
         }
    })
}

function appendInvoiceRow(){
	var customer_id=$('#customer_id').val();
	var product=$('#product').val();
	var order_quantity=$('#order_quantity').val();
	var target_price=$('#target_price').val();
    var editid=$('#editid').val();
    var notes=$('#notes').val();
    var pr_id=$('#pr_id').val();
	if(customer_id==''){
        customer_id=0;
    }
    if(notes==''){
        notes='PR';
    }
    if(target_price==''){
        target_price=0;
    }

	if(product==''){
		alert("Select Product");
	}
	else if(order_quantity==''){
		alert("Enter Order Quantity");
	}

	else {
		$.ajax({ url: "{{url('/updatePurchaseRequestLine')}}"+'/'+customer_id+'/'+product+'/'+order_quantity+'/'+target_price+'/'+editid+'/'+notes+'/'+pr_id,
            type: "get", cache: false, dataType: 'json',
            success: function (data) {
               $('#product').val('');
               $('#productname').val('');
			   $('#order_quantity').val('');
			   $('#target_price').val('');
               $('#notes').val('');

			   	$('.pstock').html('');
                $('#ppdc').html('');
                $('#pcost').html('');
                $('#tqty').html('');
                $('#editid').val('0');
                $('#productbtn').hide();
               loadpurchaselines(customer_id);
               if(data.duplicate==1){
                 alert("Error:Part number is Duplicated");
               }
            }
        })
	}
}

function getProductInfo() {
	var product_id=$('#product').val();
    var customer_id=$('#customer_id').val();
    if(customer_id=='')
    {
        customer_id=0;
    }
	 $.ajax({ url: "{{url('/getproductinfo')}}"+'/'+product_id+'/'+customer_id,
            type: "get", cache: false, dataType: 'json',
            success: function (data) {
                $('.pstock').html(data.qty);
                $('#ppdc').html(data.pdc);
                $('#pcost').html(data.cost);
                $('#rsp').val(data.rate);
                $('#tqty').html(data.tqty);
                $('#min_target').val(data.min_target);
            }
         })
    $('#productbtn').show();
    loadoldpurchaseRequestlines(product_id);
}

function loadoldpurchaseRequestlines(product_id) {
    $.ajax({ url: "{{url('/load5oldpurchaseRequestlines')}}"+'/'+product_id,
            type: "get", cache: false, dataType: 'json',
            success: function (data) {
                $('.old5pr').html(data.old5prhtml);
            }
         })
}

function deletePurchaserequestLine(product_id) {
	if (confirm('Are you sure you want to delete this?')) {
		var customer_id=$('#customer_id').val();
        if(customer_id==''){
            customer_id=0;
        }
		 $.ajax({ url: "{{url('/deletePurchaserequestLine')}}"+'/'+product_id,
	            type: "get", cache: false, dataType: 'json',
	            success: function (data) {
	                loadpurchaselines(customer_id);
	            }
	         })
	}
}

function editPurchaserequestLine(id,product_id) {
    $("#editid").val(id);
    
    var name=$('#name'+id).text(); $("#productname").val(name);
    $("#product").val(product_id);
    var note=$('#note'+id).text(); $("#notes").val(note);
    var quantity=$('#quantity'+id).text(); $("#order_quantity").val(quantity);
    var target_price=$('#target_price'+id).text(); $("#target_price").val(target_price);
    
    getProductInfo();
}

function showproductwindow(){
   var product_id=$('#product').val();
   $.ajax({ url: "{{url('/getproductwindow')}}"+'/'+product_id,
            type: "get", cache: false, dataType: 'json',
            success: function (data) {
                $('#partname').html(data.partname);
                $('#partinfoview').html(data.partinfoview);
                $("#datatable").DataTable();
            }
         })
}

function checkTagetPrice() {
    // var target_price=$('#target_price').val();
    // var min_target=$('#min_target').val();
    // if(min_target > target_price){
    //     alert("Minimum Target Price for this Item: "+min_target);
    //     $('#target_price').val(min_target);
    // }
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
    setTimeout(function() {$('#searchlists').focus();}, 600);
    $.ajax({ url: "{{url('customersearch')}}/"+company_id+'/'+branch_id,
    type: "get", cache: false, dataType: 'json', 
    success: function (data) { 
        $("#customerinfo").html(data.html);
        $("#searchlists").focus();
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

document.getElementById("order_quantity").addEventListener("keyup", function() {
  this.value = this.value.replace(/[^0-9]/g, "");
});
    </script>
@stop