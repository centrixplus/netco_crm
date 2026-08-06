@extends('layouts.master')
@section('title','Edit PO')
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
                <h5 class="modal-title" id="myExtraLargeModalLabel">Select Vendor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="vendorinfo">
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="row">
<div class="col-md-2"></div>
<div class="col-md-8">

<div class="card">
    <div class="card-body">
    <h4 class="card-title">Edit PO Details </h4>
    <!-- <div class="spinner-grow text-primary m-1" role="status">
                                                
    </div> -->
    <form action="{{route('poupdate')}}" method="post">
    {{csrf_field()}}
    <div class="row">
        <div class="col-lg-4 mb-3">
            <div>
                <label>company<span class="text-danger">*</span></label>
                <select onchange="loadBranch()" name="company_id" id="company_id" required="" class="form-control" required="">
                    <option value="">Select</option>
                    @foreach($companies as $company)
                     <option @if($po->company_id==$company->id) selected="" @endif value="{{$company->id}}">{{$company->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div>
                <label>Branch<span class="text-danger">*</span></label>
                <select name="branch_id" id="branch_id" required="" class="form-control" required="">
                    <option value="">Select</option>
                    @foreach($branches as $branch)
                     <option class="allb b_{{$branch->company_id}}" @if($po->branch_id==$branch->id) selected="" @endif value="{{$branch->id}}">{{$branch->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="form-group">
                <label>Select Vendor: <span class="text-danger">*</span></label>
                <input @if($po->vendor) value="{{$po->vendor->name}} - {{$po->vendor->mobile}}  @if(Auth::user()->role=='admin') - {{$po->vendor->code}} @endif" @endif type="text" class="form-control" id="vendor_name" name="vendor_name" placeholder="Select Vendor" onclick="selectVendor();">
                 <input @if($po->vendor) value="{{$po->vendor_id}}" @endif type="text" class="d-none" name="vendor_id" id="vendor_id" required="">
                <input type="hidden" name="edit_id" value="{{$po->id}}">
             </div>
        </div>

        <div class="col-lg-4" >
            <div class="form-group">
                <label for="cardnameInput">PO Date <span class="text-danger">*</span></label>
                <input type="date" value="{{$po->po_date}}" name="po_date" id="po_date" class="form-control">
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-group">
                <label for="cardnameInput">Note</label>
                <textarea class="form-control" id="note" name="note">{{$po->note}}</textarea>
            </div>
        </div>

    </div>
    
    <div class="row mt-4">
        <div class="col-sm-6">
            <div class="text-end">
                <button type="submit" class="btn btn-info"> <i class="mdi mdi-cart me-1"></i> Update PO </button>
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
function selectVendor() {
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
    $.ajax({ url: "{{url('vendorsearch')}}/"+company_id+'/'+branch_id,
    type: "get", cache: false, dataType: 'json', 
    success: function (data) { 
        $("#vendorinfo").html(data.html);
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

function vendorselect(id,name,code){
    var cus_code=code+'-'+name;
    $('#vendor_name').val(cus_code);
    $('#vendor_id').val(id);
    $(".modalnew").modal('hide'); 
}
function loadBranch() {
    var company_id=$('#company_id').val();
    if(company_id!=''){
        $('.allb').hide();
        $('.b_'+company_id).show();
    }
}
</script>
@endsection