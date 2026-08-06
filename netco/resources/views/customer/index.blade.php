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
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
        <h4 class="text-secondary"><span id="createbutton">Create</span> {{$title}}</h4>
        <div class="row">
            <div class="col-sm-12 pt-3">
                <form  action="{{url('/customer/store')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}  
                <div class="row">
                    
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Code<span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="code" id="code" required="">
                            @error('code')
                            <div class="text-danger">
                                This Code is already there with us
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}" required="">
                            <input type="hidden" name="editid" id="editid">
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Location<span class="text-danger">*</span></label>
                            <select name="location_id" id="location_id" required="" class="form-control select2" required="">
                                <option value="">Select</option>
                                @foreach($locations as $location)
                                 <option @if(old('location_id')==$location->id) selected="" @endif value="{{$location->id}}">{{$location->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>company<span class="text-danger">*</span></label>
                            <select onchange="loadBranch()" name="company_id" id="company_id" required="" class="form-control" required="">
                                <option value="">Select</option>
                                @foreach($companies as $company)
                                 <option @if(old('company_id')==$company->id) selected="" @endif value="{{$company->id}}">{{$company->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>branch<span class="text-danger">*</span></label>
                            <select name="branch_id" id="branch_id" required="" class="form-control" required="">
                                <option value="">Select</option>
                                @foreach($branches as $branch)
                                 <option class="allb b_{{$branch->company_id}}" @if(old('branch_id')==$branch->id) selected="" @endif value="{{$branch->id}}">{{$branch->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Area<span class="text-danger">*</span></label>
                            <input class="form-control" value="{{ old('area') }}" type="text" name="area" id="area" required="">
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Mobile<span class="text-danger">*</span></label>
                            <input value="{{ old('mobile') }}" class="form-control" type="text" name="mobile" id="mobile" required="">
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div>
                            <label>Address</label>
                            <textarea class="form-control" type="text" name="note" id="note">{{ old('note') }}</textarea>
                        </div>
                    </div>           
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-primary waves-effect waves-light me-2 mt-2">Save</button>
                        <a href="{{url('/customer')}}" class="btn btn-secondary waves-effect waves-light resetbtn mt-2">Cancel</a>
                    </div>
                </form>
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
            <h4 class="text-secondary">{{$title}} List</h4>
            <table id="datatable-buttons" class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Location</th>
                        <th>Area</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>           
                <tbody>
                    <?php $x=1; ?>
                    @foreach ($indexes as $index)

                    <tr>
                        <td>{{$x++}}</td>
                        <td>{{$index->code}}</td>
                        <td>{{$index->name}}</td>
                        <td>{{$index->mobile}}</td>
                        <td>@if(!empty($index->location_id)){{$index->location->name}}@endif</td>
                        <td>{{$index->area}}</td>
                        <td>{{$index->note}}</td>
                        <td>
                            <a href="#" data-id="{{$index->id}}" data-name="{{$index->name}}" data-location_id="{{$index->location_id}}" data-code="{{$index->code}}" data-mobile="{{$index->mobile}}" data-area="{{$index->area}}" data-note="{{$index->note}}" data-company_id="{{$index->company_id}}" data-branch_id="{{$index->branch_id}}" class="btn btn-outline-secondary waves-effect waves-light btn-sm font-size-18 editbtn"><i class="mdi mdi-pencil"></i></a>
                            <a onclick="return confirm('Are you sure?')" href="{{url('/customer')}}/{{$index->id}}/delete" class="btn btn-outline-danger waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-trash-can-outline"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
$('.allb').hide();
$(document).ready(function(){$("#datatable").DataTable(),$("#datatable-buttons").DataTable({lengthChange:!1,buttons:["copy","excel","pdf"]}).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),$(".dataTables_length select").addClass("form-select form-select-sm")});

$('.editbtn').click(function() {
    var id = $(this).data("id"); $("#editid").val(id);
    var name = $(this).data("name"); $("#name").val(name);
    var code = $(this).data("code"); $("#code").val(code);
    var mobile = $(this).data("mobile"); $("#mobile").val(mobile);
    var area = $(this).data("area"); $("#area").val(area);
    var location_id = $(this).data("location_id"); $('#location_id').val(location_id).trigger('change');

    var company_id = $(this).data("company_id"); $("#company_id").val(company_id);
    var branch_id = $(this).data("branch_id"); $("#branch_id").val(branch_id);
    var note = $(this).data("note"); $("#note").val(note);
    $('#createbutton').html('Update');
});

function loadBranch() {
    var company_id=$('#company_id').val();
    if(company_id!=''){
        $('.allb').hide();
        $('.b_'+company_id).show();
    }
}
</script>
@stop