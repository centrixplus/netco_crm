@extends('layouts.master')
@section('title','Create GRN')
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
    <h4 class="card-title">Create GRN</h4>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{!!session('error')!!}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{route('grnstore')}}" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <div class="row">
        <div class="col-lg-3 mb-3">
            <div>
                <label>company<span class="text-danger">*</span></label>
                <select onchange="loadBranch()" name="company_id" id="company_id" required="" class="form-control" required="">
                    <option value="">Select</option>
                    @foreach($companies as $company)
                     <option @if(Auth::user()->company_id==$company->id) selected="" @endif value="{{$company->id}}">{{$company->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-lg-3 mb-3">
            <div>
                <label>Branch<span class="text-danger">*</span></label>
                <select name="branch_id" id="branch_id" required="" class="form-control" required="">
                    <option value="">Select</option>
                    @foreach($branches as $branch)
                     <option class="allb b_{{$branch->company_id}}" @if(Auth::user()->branch_id==$branch->id) selected="" @endif value="{{$branch->id}}">{{$branch->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-3 mb-3">
            <div class="form-group">
                <label>Select Vendor: <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="vendor_name" name="vendor_name" placeholder="Select Vendor" onclick="selectVendor();" required="">
                 <input type="text" class="d-none" name="vendor_id" id="vendor_id" required="">
             </div>
        </div>

        <div class="col-lg-3" >
            <div class="form-group">
                <label for="cardnameInput">GRN Dtae <span class="text-danger">*</span></label>
                <input type="date" value="{{date('Y-m-d')}}" name="grn_date" id="grn_date" class="form-control">
            </div>
        </div>

        <div class="col-lg-3" >
            <div class="form-group">
                <label for="cardnameInput">Document Number</label>
                <input type="text" name="document_no" id="document_no" class="form-control">
            </div>
        </div>

        <div class="col-lg-3" >
            <div class="form-group">
                <label for="cardnameInput">Inquiry Number</label>
                <input type="text" name="inquiry_no" id="inquiry_no" class="form-control">
            </div>
        </div>

        <div class="col-lg-3" >
            <div class="form-group">
                <label for="cardnameInput">Project Number</label>
                <input type="text" name="project_no" id="project_no" class="form-control">
            </div>
        </div>
        
        <div class="col-lg-3">
            <div class="form-group">
                <label for="cardnameInput">Note</label>
                <textarea class="form-control" id="note" name="note"></textarea>
            </div>
        </div>
        <div class="col-sm-12 mb-0"><br>
            <label for="cardnameInput">File<span class="text-danger">*</span>
                @php
                    use Illuminate\Support\Str;

                    $url = url('/GRN-Upload-Fromat.xlsx');
                    $cleanUrl = Str::replace('index.php/', '', $url);
                @endphp
                <a href="{{$cleanUrl}}" target="_blank" class="btn btn-outline-info waves-effect waves-light btn-sm"><i class="mdi mdi-download" download></i></a>
                <input type="checkbox" name="price_check" checked> Check Price Condition?
            </label>
            <input type="file" name="import_file" class="form-control" id="import_file" required="">
        </div>

    </div>
    
    <div class="row mt-4">
        <div class="col-sm-6">
            <a href="{{url('/grn')}}" class="btn text-muted d-none d-sm-inline-block btn-link">
                <i class="mdi mdi-arrow-left me-1"></i> Refresh </a>
        </div> <!-- end col -->
        <div class="col-sm-6">
            <div class="text-end">
                <button type="submit" class="btn btn-info"> <i class="mdi mdi-cart me-1"></i> Save GRN </button>
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