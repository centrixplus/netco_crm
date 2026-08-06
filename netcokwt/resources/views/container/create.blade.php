@extends('layouts.master')
@section('title',$title)
@section('StyleContent')
<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('PageContent')
<!-- Vendor picker modal -->
<div class="modal modalnew fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Vendor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="vendorinfo"></div>
        </div>
    </div>
</div>

<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-16">{{$title}}</h4>
        <div class="page-title-right">
            <a href="{{url('/container')}}" class="btn btn-secondary btn-sm">Back to List</a>
        </div>
    </div>
</div>
</div>

@if(session('errors_list'))
<div class="alert alert-danger">
    <strong>Upload stopped — fix these and try again:</strong>
    <ul class="mb-0 mt-2">
        @foreach(session('errors_list') as $err)
            <li>{{$err}}</li>
        @endforeach
    </ul>
</div>
@endif
@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{$e}}</li>@endforeach</ul>
</div>
@endif

<form action="{{url('/container/store')}}" method="post" enctype="multipart/form-data">
{{csrf_field()}}
<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h4 class="text-secondary">Container Details</h4>
            <div class="row pt-3">
                <div class="col-lg-3 mb-3">
                    <label>Select Vendor: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="vendor_name" name="vendor_name" placeholder="Select Vendor" onclick="selectVendor();" value="{{old('vendor_name')}}" autocomplete="off">
                    <input type="text" class="d-none" name="vendor_id" id="vendor_id" value="{{old('vendor_id')}}" required>
                </div>
                <div class="col-lg-3 mb-3">
                    <label>Vendor Doc No</label>
                    <input class="form-control" type="text" name="vendor_doc_no" value="{{old('vendor_doc_no')}}">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>Container No <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="container_no" value="{{old('container_no')}}" required>
                </div>
                <div class="col-lg-3 mb-3">
                    <label>Mode of Shipment</label>
                    <select class="form-control" name="mode_of_shipment">
                        <option value="">Select</option>
                        @foreach(['AIR','LAND','SEA'] as $m)
                            <option value="{{$m}}" {{old('mode_of_shipment')==$m?'selected':''}}>{{$m}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 mb-3">
                    <label>Shipment Doc No</label>
                    <input class="form-control" type="text" name="shipment_doc_no" value="{{old('shipment_doc_no')}}">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>Received Date</label>
                    <input class="form-control" type="date" name="received_date" value="{{old('received_date', date('Y-m-d'))}}">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>PO No</label>
                    <input class="form-control" type="text" name="po_no" value="{{old('po_no')}}">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>SA No</label>
                    <input class="form-control" type="text" name="sa_no" value="{{old('sa_no')}}">
                </div>
                <div class="col-lg-6 mb-3">
                    <label>Tags</label>
                    <select class="form-control select2" name="tags[]" multiple data-placeholder="Select tags" style="width:100%;">
                        @foreach($tags as $t)
                            <option value="{{$t->id}}" {{ (collect(old('tags'))->contains($t->id)) ? 'selected' : '' }}>{{$t->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 mb-3">
                    <label>Remarks</label>
                    <input class="form-control" type="text" name="remarks" value="{{old('remarks')}}">
                </div>
                <div class="col-lg-4 mb-3">
                    <label>Note</label>
                    <input class="form-control" type="text" name="note" value="{{old('note')}}">
                </div>
                <div class="col-lg-4 mb-3">
                    <label>Note 1</label>
                    <input class="form-control" type="text" name="note1" value="{{old('note1')}}">
                </div>
                <div class="col-lg-4 mb-3">
                    <label>Note 2</label>
                    <input class="form-control" type="text" name="note2" value="{{old('note2')}}">
                </div>
                <div class="col-lg-4 mb-3">
                    <label>Note 3</label>
                    <input class="form-control" type="text" name="note3" value="{{old('note3')}}">
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <h4 class="text-secondary mb-0">Upload Lines (Excel / CSV)</h4>
                <a href="{{route('containerlineformat')}}" class="btn btn-outline-info btn-sm"><i class="mdi mdi-download"></i> Download Format</a>
            </div>
            <div class="alert alert-info mt-2">
                Required columns (must have a value): <strong>CtnNo, PartNo, QUANTITY</strong>.
                QUANTITY must be a number greater than 0. Other columns: Description, BRAND, LOCATION, CUS ID, note 1, note 2, note.
            </div>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label>File <span class="text-danger">*</span></label>
                    <input class="form-control" type="file" name="import_file" accept=".xlsx,.xls,.csv" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary waves-effect waves-light mt-2">Upload &amp; Save</button>
            <a href="{{url('/container')}}" class="btn btn-secondary waves-effect waves-light mt-2">Cancel</a>
        </div>
    </div>
</div>
</div>
</form>
@endsection
@section('ScriptContent')
<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>
<script>
$(document).ready(function(){
    $(".select2").select2({ placeholder: "Select tags", allowClear: true, width: '100%' });
});

function selectVendor(){
    $(".modalnew").modal('show');
    setTimeout(function(){ $('#searchlists').focus(); }, 600);
    $.ajax({ url: "{{url('vendorsearch')}}", type: "get", cache: false, dataType: 'json',
        success: function(data){ $("#vendorinfo").html(data.html); $("#searchlists").focus(); }
    });
}
function searchvendors(value){
    value = value.toLowerCase();
    $(".search-areas table tbody tr").filter(function(){
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
}
function vendorselect(id,name){
    $('#vendor_name').val(name);
    $('#vendor_id').val(id);
    $(".modalnew").modal('hide');
}
</script>
@stop
