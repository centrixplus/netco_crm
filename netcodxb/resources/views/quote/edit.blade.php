@extends('layouts.master')
@section('title','Edit Quote')
@section('StyleContent')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('PageContent')

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
<div class="col-md-2"></div>
<div class="col-md-8">

<div class="card">
    <div class="card-body">
    <h4 class="card-title">Edit Quote Details </h4>
    <!-- <div class="spinner-grow text-primary m-1" role="status">
                                                
    </div> -->
    <form action="{{route('quoteupdate')}}" method="post">
    {{csrf_field()}}
    <div class="row">

        <div class="col-lg-5 mb-3">
            <div class="form-group">
                <label>Select Customer: <span class="text-danger">*</span></label>
                <input @if($quote->customer) value="{{$quote->customer->name}} - {{$quote->customer->mobile}}  @if(Auth::user()->role=='admin') - {{$quote->customer->code}} @endif" @endif type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Select Customer" onclick="selectCustomer();">
                 <input @if($quote->customer) value="{{$quote->customer_id}}" @endif type="text" class="d-none" name="customer_id" id="customer_id" required="">
                 <input type="hidden" name="company_id" id="company_id" value="{{Auth::user()->company_id}}">
                <input type="hidden" name="branch_id" id="branch_id" value="{{Auth::user()->branch_id}}">
                <input type="hidden" name="edit_id" value="{{$quote->id}}">
             </div>
        </div>

        <div class="col-lg-2">
            <div class="form-group">
                <label for="cardnameInput">Brand <span class="text-danger">*</span></label>
                <select class="form-control" id="brand_id" name="brand_id" required>
                    <option value="">Choose...</option>
                    @foreach($brands as $brd)
                    <option @if($quote->brand_id==$brd->id) selected="" @endif value="{{$brd->id}}">{{$brd->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-lg-2">
            <div class="form-group">
                <label for="cardnameInput">User <span class="text-danger">*</span></label>
                <select class="form-control" id="user_id" name="user_id" required>
                    <option value="">Choose...</option>
                    @foreach($users as $user)
                    <option @if($quote->user_id==$user->id) selected="" @endif value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-lg-3" >
            <div class="form-group">
                <label for="cardnameInput">Quote Dtae <span class="text-danger">*</span></label>
                <input type="date" value="{{$quote->quote_date}}" name="quote_date" id="quote_date" class="form-control">
            </div>
        </div>
        
        <div class="col-lg-3 mt-3">
            <div class="form-group">
                <label for="cardnameInput">Price Level</label>
                <input value="{{$quote->price_level}}" type="text" class="form-control" id="price_level" name="price_level">
            </div>
        </div>

        <div class="col-lg-3 mt-3">
            <div class="form-group">
                <label for="cardnameInput">Currency</label>
                <select class="form-control" id="currency" name="currency" required>
                    @foreach($currencies as $crr)
                    <option @if($quote->currency_id==$crr->id) selected="" @endif value="{{$crr->id}}">{{$crr->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-lg-6 mt-3">
            <div class="form-group">
                <label for="cardnameInput">Note</label>
                <textarea class="form-control" id="note" name="note">{{$quote->note}}</textarea>
            </div>
        </div>

    </div>
    
    <div class="row mt-4">
        <div class="col-sm-6">
            <div class="text-end">
                <button type="submit" class="btn btn-info"> <i class="mdi mdi-cart me-1"></i> Update Quote </button>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
    </form>
    </div>
</div>

</div>

</div>
</div>
@endsection
@section('ScriptContent')
<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>
<script src="{{asset('/assets')}}/js/pages/form-advanced.init.js"></script>
<script type="text/javascript">
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
    $(".modalnew").modal('hide'); 
}
</script>
@endsection