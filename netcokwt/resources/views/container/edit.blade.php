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
            <a href="{{url('/container')}}/{{$container->id}}/cartons" class="btn btn-info btn-sm">View Boxes</a>
            <a href="{{url('/container')}}" class="btn btn-secondary btn-sm">Back to List</a>
        </div>
    </div>
</div>
</div>

@if(session('alert'))
<div class="alert alert-success">{!! session('alert') !!}</div>
@endif
@if(session('error'))
<div class="alert alert-danger">{!! session('error') !!}</div>
@endif
@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{$e}}</li>@endforeach</ul></div>
@endif

<!-- Header (always editable) -->
<form action="{{route('containerupdate')}}" method="post">
{{csrf_field()}}
<input type="hidden" name="id" value="{{$container->id}}">
<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h4 class="text-secondary">Container Details</h4>
            <div class="row pt-3">
                <div class="col-lg-3 mb-3">
                    <label>Select Vendor: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="vendor_name" name="vendor_name" placeholder="Select Vendor" onclick="selectVendor();" value="{{optional($container->vendor)->name}}" autocomplete="off">
                    <input type="text" class="d-none" name="vendor_id" id="vendor_id" value="{{$container->vendor_id}}" required>
                </div>
                <div class="col-lg-3 mb-3">
                    <label>Vendor Doc No</label>
                    <input class="form-control" type="text" name="vendor_doc_no" value="{{$container->vendor_doc_no}}">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>Container No <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="container_no" value="{{$container->container_no}}" required>
                </div>
                <div class="col-lg-3 mb-3">
                    <label>Mode of Shipment</label>
                    <select class="form-control" name="mode_of_shipment">
                        <option value="">Select</option>
                        @foreach(['AIR','LAND','SEA'] as $m)
                            <option value="{{$m}}" {{$container->mode_of_shipment==$m?'selected':''}}>{{$m}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 mb-3">
                    <label>Shipment Doc No</label>
                    <input class="form-control" type="text" name="shipment_doc_no" value="{{$container->shipment_doc_no}}">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>Received Date</label>
                    <input class="form-control" type="date" name="received_date" value="{{$container->received_date}}">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>PO No</label>
                    <input class="form-control" type="text" name="po_no" value="{{$container->po_no}}">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>SA No</label>
                    <input class="form-control" type="text" name="sa_no" value="{{$container->sa_no}}">
                </div>
                <div class="col-lg-6 mb-3">
                    <label>Tags</label>
                    <select class="form-control select2" name="tags[]" multiple data-placeholder="Select tags" style="width:100%;">
                        @foreach($tags as $t)
                            <option value="{{$t->id}}" {{ in_array($t->id, $selectedTags) ? 'selected' : '' }}>{{$t->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 mb-3">
                    <label>Remarks</label>
                    <input class="form-control" type="text" name="remarks" value="{{$container->remarks}}">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>Note</label>
                    <input class="form-control" type="text" name="note" value="{{$container->note}}">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>Note 1</label>
                    <input class="form-control" type="text" name="note1" value="{{$container->note1}}">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>Note 2</label>
                    <input class="form-control" type="text" name="note2" value="{{$container->note2}}">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>Note 3</label>
                    <input class="form-control" type="text" name="note3" value="{{$container->note3}}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary waves-effect waves-light mt-2">Update Container</button>
        </div>
    </div>
</div>
</div>
</form>

<!-- Lines -->
<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h4 class="text-secondary">Lines</h4>
            @if($locked)
            <div class="alert alert-warning">
                <i class="mdi mdi-lock"></i> Line editing is <strong>locked</strong> because receiving has already started for this container. You can still edit the header above.
            </div>
            @elseif($started)
            <div class="alert alert-info">
                <i class="mdi mdi-shield-account"></i> Receiving has already started for this container. You are editing lines as <strong>Admin</strong> — received quantities are <strong>not</strong> auto-adjusted, so review balances after changing quantity.
            </div>
            @endif

            {{-- Search across all lines (server-side) --}}
            <form action="{{url('/container')}}/{{$container->id}}/edit" method="get" class="row g-2 mb-3">
                <div class="col-lg-3">
                    <input class="form-control form-control-sm" type="text" name="box_no" value="{{$box_no}}" placeholder="Search Box No (CtnNo)">
                </div>
                <div class="col-lg-3">
                    <input class="form-control form-control-sm" type="text" name="part_no" value="{{$part_no}}" placeholder="Search Part No">
                </div>
                <div class="col-lg-4 align-self-center">
                    <button type="submit" class="btn btn-primary btn-sm">Search</button>
                    <a href="{{url('/container')}}/{{$container->id}}/edit" class="btn btn-light btn-sm">Clear</a>
                    <span class="text-muted ms-2">Total lines: {{$lines->total()}}</span>
                </div>
            </form>

            {{-- Per-line edit forms declared outside the table; inputs link via form="..." --}}
            @if(!$locked)
                @foreach($lines as $line)
                <form id="lineform{{$line->id}}" class="lineEditForm" data-line-id="{{$line->id}}" action="{{route('containerlineupdate')}}" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="id" value="{{$line->id}}">
                </form>
                @endforeach
            @endif
            <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>CtnNo</th>
                        <th>PartNo</th>
                        <th>Description</th>
                        <th>QUANTITY</th>
                        <th>BRAND</th>
                        <th>LOCATION</th>
                        <th>CRM Qty</th>
                        <th>Received</th>
                        <th>Balance</th>
                        @if(!$locked)<th>Action</th>@endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($lines as $line)
                    <?php $bal = $line->quantity - $line->received_qty; ?>
                    @if($locked)
                    <tr class="{{$line->received_qty > $line->quantity ? 'table-danger' : ''}}">
                        <td>{{$line->box_no}}</td>
                        <td>{{$line->part_no}}</td>
                        <td>{{$line->description}}</td>
                        <td>{{$line->quantity}}</td>
                        <td>{{$line->brand}}</td>
                        <td>{{$line->location}}</td>
                        <td>{{$line->crm_qty}}</td>
                        <td>{{$line->received_qty}}@if($line->received_qty > $line->quantity) <span class="badge bg-danger">over</span>@endif</td>
                        <td>{{$bal}}</td>
                    </tr>
                    @else
                    <tr id="linerow{{$line->id}}" class="{{$line->received_qty > $line->quantity ? 'table-danger' : ''}}">
                        <td><input class="form-control form-control-sm" form="lineform{{$line->id}}" type="text" name="box_no" value="{{$line->box_no}}" style="width:90px"></td>
                        <td><input class="form-control form-control-sm" form="lineform{{$line->id}}" type="text" name="part_no" value="{{$line->part_no}}" required></td>
                        <td><input class="form-control form-control-sm" form="lineform{{$line->id}}" type="text" name="description" value="{{$line->description}}"></td>
                        <td><input class="form-control form-control-sm" form="lineform{{$line->id}}" type="number" step="1" min="1" name="quantity" value="{{$line->quantity}}" style="width:90px" onkeypress="return event.charCode>=48 && event.charCode<=57" required></td>
                        <td><input class="form-control form-control-sm" form="lineform{{$line->id}}" type="text" name="brand" value="{{$line->brand}}"></td>
                        <td><input class="form-control form-control-sm" form="lineform{{$line->id}}" type="text" name="location" value="{{$line->location}}"></td>
                        <td class="fw-bold">{{$line->crm_qty}}</td>
                        <td class="fw-bold received-cell">{{$line->received_qty}}@if($line->received_qty > $line->quantity) <span class="badge bg-danger">over</span>@endif</td>
                        <td class="fw-bold balance-cell">{{$bal}}</td>
                        <td class="text-nowrap">
                            <button type="submit" form="lineform{{$line->id}}" class="btn btn-success btn-sm"><i class="mdi mdi-content-save"></i></button>
                            <button type="button" class="btn btn-outline-danger btn-sm lineDelete" data-line-id="{{$line->id}}"><i class="mdi mdi-trash-can-outline"></i></button>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
            </div>
            <div class="mt-3">
                {{ $lines->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('ScriptContent')
<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>
<script>
$(document).ready(function(){
    $(".select2").select2({ placeholder: "Select", allowClear: true, width: '100%' });
});

var CSRF = "{{csrf_token()}}";

function showToast(message, type){
    var bg = (type === 'error') ? 'bg-danger' : 'bg-success';
    var $c = $('#toastContainer');
    if(!$c.length){
        $c = $('<div id="toastContainer" style="position:fixed;top:20px;right:20px;z-index:1080;"></div>').appendTo('body');
    }
    var $t = $('<div class="toast align-items-center text-white '+bg+' border-0 mb-2" role="alert">'+
               '<div class="d-flex"><div class="toast-body">'+message+'</div>'+
               '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>');
    $c.append($t);
    var t = new bootstrap.Toast($t[0], { delay: 3000 });
    t.show();
    $t.on('hidden.bs.toast', function(){ $(this).remove(); });
}
function parseErr(xhr){
    var msg = 'Action failed.';
    if(xhr.responseJSON && xhr.responseJSON.message){ msg = xhr.responseJSON.message; }
    if(xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors){
        msg = $.map(xhr.responseJSON.errors, function(a){ return a.join(' '); }).join(' ');
    }
    return msg;
}

// Save a line via AJAX (no full reload).
$(document).on('submit', '.lineEditForm', function(e){
    e.preventDefault();
    var $form  = $(this);
    var lineId = $form.data('line-id');
    var $btn   = $('button[form="lineform'+lineId+'"]');
    $btn.prop('disabled', true);
    $.ajax({
        url: $form.attr('action'), type: "POST", dataType: "json", data: $form.serialize(),
        success: function(res){
            var $row = $('#linerow'+lineId);
            $row.find('.balance-cell').text(res.balance);
            if(res.over){
                $row.addClass('table-danger');
                $row.find('.received-cell').html(res.received_qty + ' <span class="badge bg-danger">over</span>');
            } else {
                $row.removeClass('table-danger');
                $row.find('.received-cell').text(res.received_qty);
            }
            showToast('Line updated.');
        },
        error: function(xhr){ showToast(parseErr(xhr), 'error'); },
        complete: function(){ $btn.prop('disabled', false); }
    });
});

// Delete a line via AJAX.
$(document).on('click', '.lineDelete', function(){
    if(!confirm('Delete this line?')){ return; }
    var $btn   = $(this);
    var lineId = $btn.data('line-id');
    $btn.prop('disabled', true);
    $.ajax({
        url: "{{url('/container/line')}}/"+lineId+"/delete", type: "GET", dataType: "json",
        success: function(res){
            $('#linerow'+lineId).remove();
            showToast('Line deleted.');
        },
        error: function(xhr){ showToast(parseErr(xhr), 'error'); $btn.prop('disabled', false); }
    });
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
