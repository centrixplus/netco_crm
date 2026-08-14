@extends('layouts.master')
@section('title',$title)
@section('PageContent')
<style>
.info-card .info-item{padding:.35rem 0;}
.info-card .info-label{font-size:11px;letter-spacing:.04em;text-transform:uppercase;color:#74788d;margin-bottom:2px;}
.info-card .info-value{font-size:14px;font-weight:600;color:#495057;margin:0;}
.line-scroll{max-height:600px;overflow-y:auto;}
.line-scroll > table > thead > tr > th{position:sticky;top:0;z-index:3;background:#eef2f7;}
#receivedModal .info-item{padding:.35rem 0;}
#receivedModal .info-label{font-size:11px;letter-spacing:.05em;text-transform:uppercase;color:#a0a6b1;margin-bottom:3px;font-weight:500;}
#receivedModal .info-value{font-size:16px;font-weight:700;color:#2c3e50;margin:0;}
#lineTable{--bs-table-border-color:#6c757d;}
#lineTable th, #lineTable td, #extraTable th, #extraTable td{border:1px solid #6c757d !important;}
.cell-strong{font-weight:700 !important;color:#212529 !important;}
</style>
<div class="row">
<div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 font-size-16">{{$title}}</h4>
        <div class="page-title-right">
            <a href="{{url('/container')}}/{{$container->id}}/cartons" class="btn btn-secondary btn-sm">Back to Boxes</a>
        </div>
    </div>
</div>
</div>

@if(session('alert'))
<div class="alert alert-success">{!! session('alert') !!}</div>
@endif
@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{$e}}</li>@endforeach</ul></div>
@endif

<div class="row">
<div class="col-md-12">
    <div class="card info-card">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-start border-bottom pb-3 mb-3">
                <div>
                    <h4 class="mb-1">Box <span class="text-primary">{{$box}}</span></h4>
                    <p class="text-muted mb-0">
                        <i class="bx bx-buildings me-1"></i>{{optional($container->vendor)->name}}
                        <span class="mx-2">&middot;</span>
                        <i class="bx bxs-box me-1"></i>Container #{{$container->container_no}} @if($container->container_code)<span class="badge bg-dark">{{$container->container_code}}</span>@endif
                    </p>
                </div>
                <div class="text-end">
                    @if($container->mode_of_shipment)<span class="badge bg-info font-size-12">{{$container->mode_of_shipment}}</span>@endif
                    <span class="badge bg-success font-size-12">{{$container->status}}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-6 info-item"><div class="info-label">Vendor Doc No</div><p class="info-value">{{$container->vendor_doc_no ?: '-'}}</p></div>
                <div class="col-md-3 col-6 info-item"><div class="info-label">Shipment Doc No</div><p class="info-value">{{$container->shipment_doc_no ?: '-'}}</p></div>
                <div class="col-md-3 col-6 info-item"><div class="info-label">Received Date</div><p class="info-value">{{ $container->received_date ? date('d-m-Y', strtotime($container->received_date)) : '-' }}</p></div>
                <div class="col-md-3 col-6 info-item"><div class="info-label">Tags</div><p class="info-value">{{$container->tag_names ?: '-'}}</p></div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Lines + single-entry received qty -->
<div class="row">
<div class="col-md-12">
    <div class="card"><div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2">
                <h4 class="text-secondary mb-0">Lines</h4>
                <button type="button" id="balanceFilterBtn" class="btn btn-outline-primary btn-sm">Balance &gt; 0</button>
                <button type="button" id="balanceClearBtn" class="btn btn-outline-secondary btn-sm d-none">Remove filter</button>
            </div>
            <div class="position-relative" style="max-width:280px;">
                <input type="text" id="partSearch" class="form-control" placeholder="Search by Product No...">
            </div>
        </div>
        <div class="table-responsive line-scroll">
        <table class="table table-bordered align-middle" id="lineTable">
            <thead>
                <tr>
                    <th>CtnNo</th>
                    <th>PartNo</th>
                    <th>Description</th>
                    <th>QUANTITY</th>
                    <th>Add Received</th>
                    <th>Received</th>
                    <th>Balance</th>
                    <th>CRM Qty</th>
                    <th>BRAND</th>
                    <th>LOCATION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lines as $line)
                <?php $balance = $line->quantity - $line->received_qty; ?>
                <tr class="line-row {{$line->received_qty > $line->quantity ? 'table-danger' : ''}}" id="row{{$line->id}}">
                    <td>{{$line->box_no}}</td>
                    <td class="part-cell cell-strong">{{$line->part_no}}</td>
                    <td>{{$line->description}}</td>
                    <td class="cell-strong">{{$line->quantity}}</td>
                    <td>
                        <form action="{{route('containerreceiptstore')}}" method="post" class="receiptForm" data-line-id="{{$line->id}}" data-qty="{{$line->quantity}}" style="min-width:160px">
                            {{csrf_field()}}
                            <input type="hidden" name="container_line_id" value="{{$line->id}}">
                            <input type="number" step="1" min="1" name="received_qty" class="form-control form-control-sm mb-1 qty-input" placeholder="Qty" onkeypress="return event.charCode>=48 && event.charCode<=57" required>
                            <input type="text" name="note" class="form-control form-control-sm mb-1" placeholder="Note">
                            <input type="text" name="remarks" class="form-control form-control-sm mb-1" placeholder="Remarks">
                            <button type="submit" class="btn btn-success btn-sm w-100">Add</button>
                        </form>
                        <button type="button" id="entriesbtn{{$line->id}}" class="btn btn-link btn-sm p-0 mt-1 {{$line->receiptlogs->count() ? '' : 'd-none'}}" data-bs-toggle="collapse" data-bs-target="#logs{{$line->id}}">
                            <i class="mdi mdi-history"></i> View entries (<span id="entriescount{{$line->id}}">{{$line->receiptlogs->count()}}</span>)
                        </button>
                    </td>
                    <td class="received-cell">
                        <span class="received-val cell-strong">{{$line->received_qty}}</span>
                        <span class="over-badge">@if($line->received_qty > $line->quantity)<span class="badge bg-danger">over</span>@endif</span>
                        <button type="button" id="recvplus{{$line->id}}"
                                class="btn btn-sm recv-plus {{$balance < 0 ? 'btn-danger' : ($balance > 0 ? 'btn-primary' : 'btn-success')}}"
                                data-line-id="{{$line->id}}"
                                data-container="{{$container->container_no}}"
                                data-box="{{$line->box_no}}"
                                data-part="{{$line->part_no}}"
                                data-qty="{{$line->quantity}}"
                                data-brand="{{$line->brand}}"
                                data-location="{{$line->location}}"
                                title="Add / view received">
                            <i class="mdi mdi-plus"></i>
                        </button>
                    </td>
                    <td class="balance-cell cell-strong">{{$balance}}</td>
                    <td>{{$line->crm_qty}}</td>
                    <td>{{$line->brand}}</td>
                    <td class="cell-strong">{{$line->location}}</td>
                </tr>
                <tr class="collapse" id="logs{{$line->id}}">
                    <td colspan="10" class="bg-light">
                        <strong class="text-secondary">Previous Received Entries — {{$line->part_no}}</strong>
                        <span class="d-none" id="entriesanchor{{$line->id}}"></span>
                        <table class="table table-sm table-bordered mb-0 mt-2 bg-white" id="entriestbl{{$line->id}}">
                            <thead>
                                <tr>
                                    <th>#</th><th>Received Qty</th><th>Source</th><th>Note</th><th>Remarks</th>
                                    <th>Created By</th><th>Created At</th><th>Updated By</th><th>Updated At</th><th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="logsbody{{$line->id}}">
                                <?php $e=1; ?>
                                @foreach($line->receiptlogs as $rl)
                                <tr id="logrow{{$rl->id}}">
                                    <td>{{$e++}}</td>
                                    <td><input type="number" step="1" min="1" class="form-control form-control-sm log-qty" value="{{$rl->received_qty}}" style="width:80px" onkeypress="return event.charCode>=48 && event.charCode<=57"></td>
                                    <td><span class="badge bg-soft-info text-info">{{$rl->source}}</span></td>
                                    <td><input type="text" class="form-control form-control-sm log-note" value="{{$rl->note}}"></td>
                                    <td><input type="text" class="form-control form-control-sm log-remarks" value="{{$rl->remarks}}"></td>
                                    <td>{{optional($rl->creator)->name}}</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($rl->created_at)) }}</td>
                                    <td class="log-updater">{{optional($rl->updater)->name}}</td>
                                    <td class="log-updated">{{ $rl->updated_by ? date('d-m-Y H:i:s', strtotime($rl->updated_at)) : '' }}</td>
                                    <td class="text-nowrap">
                                        <button type="button" class="btn btn-success btn-sm logSave" data-log-id="{{$rl->id}}" data-line-id="{{$line->id}}"><i class="mdi mdi-content-save"></i></button>
                                        <button type="button" class="btn btn-outline-danger btn-sm logDelete" data-log-id="{{$rl->id}}" data-line-id="{{$line->id}}"><i class="mdi mdi-trash-can-outline"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light"><th>Total</th><th colspan="9"><span id="logstotal{{$line->id}}">{{$line->received_qty}}</span></th></tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div></div>
</div>
</div>

<!-- Extra parts (not in list) -->
<div class="row">
<div class="col-md-12">
    <div class="card"><div class="card-body">
        <h4 class="text-secondary">Extra Parts (received but not in the list)</h4>
        <div class="table-responsive">
        <table class="table table-bordered" id="extraTable">
            <thead>
                <tr><th>#</th><th>CtnNo</th><th>PartNo</th><th>Description</th><th>Received</th><th>Brand</th><th>Location</th><th>Source</th><th>Note</th></tr>
            </thead>
            <tbody>
                <?php $x=1; ?>
                @foreach($extras as $ex)
                <tr>
                    <td>{{$x++}}</td>
                    <td>{{$ex->box_no}}</td>
                    <td>{{$ex->part_no}}</td>
                    <td>{{$ex->description}}</td>
                    <td>{{$ex->received_qty}}</td>
                    <td>{{$ex->brand}}</td>
                    <td><strong>{{$ex->location}}</strong></td>
                    <td>{{$ex->source}}</td>
                    <td>{{$ex->note}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        <h5 class="text-secondary mt-3">Add Extra Part</h5>
        <form action="{{route('containerextrastore')}}" method="post">
            {{csrf_field()}}
            <input type="hidden" name="container_id" value="{{$container->id}}">
            <input type="hidden" name="box_no" value="{{$box}}">
            <div class="row">
                <div class="col-lg-2 mb-2"><input class="form-control" type="text" name="part_no" placeholder="PartNo *" required></div>
                <div class="col-lg-3 mb-2"><input class="form-control" type="text" name="description" placeholder="Description"></div>
                <div class="col-lg-2 mb-2"><input class="form-control" type="number" step="1" min="1" name="received_qty" placeholder="Received *" onkeypress="return event.charCode>=48 && event.charCode<=57" required></div>
                <div class="col-lg-2 mb-2"><input class="form-control" type="text" name="brand" placeholder="Brand"></div>
                <div class="col-lg-2 mb-2"><input class="form-control" type="text" name="location" placeholder="Location"></div>
                <div class="col-lg-1 mb-2"><button class="btn btn-primary">Add</button></div>
            </div>
        </form>
    </div></div>
</div>
</div>

<!-- Received-entry popup (reuses the same store/update/delete endpoints) -->
<div class="modal fade" id="receivedModal" tabindex="-1" aria-labelledby="receivedModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="receivedModalLabel">Received — Add / Entries</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Top summary: Selected Container / Box / Part No / Quantity / Received / Balance -->
        <div class="row g-2 mb-3 border-bottom pb-3">
            <div class="col-md-3 col-6 info-item"><div class="info-label">Container</div><p class="info-value mb-0" id="mdContainer">-</p></div>
            <div class="col-md-3 col-6 info-item"><div class="info-label">Box</div><p class="info-value mb-0" id="mdBox">-</p></div>
            <div class="col-md-3 col-6 info-item"><div class="info-label">Part No</div><p class="info-value mb-0" id="mdPart">-</p></div>
            <div class="col-md-3 col-6 info-item"><div class="info-label">Brand</div><p class="info-value mb-0" id="mdBrand">-</p></div>
            <div class="col-md-3 col-6 info-item"><div class="info-label">Quantity</div><p class="info-value mb-0" id="mdQty">-</p></div>
            <div class="col-md-3 col-6 info-item"><div class="info-label">Received</div><p class="info-value mb-0" id="mdReceived">-</p></div>
            <div class="col-md-3 col-6 info-item"><div class="info-label">Balance</div><p class="info-value mb-0" id="mdBalance">-</p></div>
            <div class="col-md-3 col-6 info-item"><div class="info-label">Location</div><p class="info-value mb-0" id="mdLocation">-</p></div>
        </div>

        <!-- Add received (one row, labelled, * = mandatory) -->
        <form action="{{route('containerreceiptstore')}}" method="post" class="receiptForm border rounded p-3 mb-3 bg-light" id="modalReceiptForm" data-line-id="">
            {{csrf_field()}}
            <input type="hidden" name="container_line_id" value="">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label mb-1">Qty <span class="text-danger">*</span></label>
                    <input type="number" step="1" min="1" name="received_qty" class="form-control qty-input" placeholder="Qty" onkeypress="return event.charCode>=48 && event.charCode<=57" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label mb-1">Note</label>
                    <input type="text" name="note" class="form-control" placeholder="Note">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">Remarks</label>
                    <input type="text" name="remarks" class="form-control" placeholder="Remarks">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100">Submit</button>
                </div>
            </div>
        </form>

        <!-- Previous entries for this line (relocated here on open; edit / remove work as-is) -->
        <h6 class="text-secondary mb-2">Previous Received Entries</h6>
        <div id="modalEntriesSlot" class="table-responsive"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('ScriptContent')
<script>
// --- Toast notification (Bootstrap 5, no extra library) ---------------
function showToast(message, type){
    var bg = (type === 'error') ? 'bg-danger' : 'bg-success';
    var $c = $('#toastContainer');
    if(!$c.length){
        $c = $('<div id="toastContainer" style="position:fixed;top:20px;right:20px;z-index:1080;"></div>').appendTo('body');
    }
    var $t = $('<div class="toast align-items-center text-white '+bg+' border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">'+
                 '<div class="d-flex"><div class="toast-body">'+message+'</div>'+
                 '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>');
    $c.append($t);
    var t = new bootstrap.Toast($t[0], { delay: 3000 });
    t.show();
    $t.on('hidden.bs.toast', function(){ $(this).remove(); });
}

var CSRF = "{{csrf_token()}}";
var mdCurrentLine = null;   // line whose entries table is currently inside the modal

function parseErr(xhr){
    var msg = 'Action failed.';
    if(xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors){
        msg = $.map(xhr.responseJSON.errors, function(a){ return a.join(' '); }).join(' ');
    }
    return msg;
}

// Update a line row's Received / Balance / over-badge from a JSON response.
function updateLineCells(lineId, res){
    var $row = $('#row'+lineId);
    $row.find('.received-val').text(res.received_qty);
    $row.find('.balance-cell').text(res.balance);
    if(res.over){
        $row.addClass('table-danger');
        $row.find('.over-badge').html('<span class="badge bg-danger">over</span>');
    } else {
        $row.removeClass('table-danger');
        $row.find('.over-badge').html('');
    }
    $('#logstotal'+lineId).text(res.received_qty);
    // recolor the "+" button: red = over, blue = pending, green = complete
    var bal = parseFloat(res.balance);
    $('#recvplus'+lineId).removeClass('btn-danger btn-primary btn-success')
        .addClass(bal < 0 ? 'btn-danger' : (bal > 0 ? 'btn-primary' : 'btn-success'));
    // keep the modal summary in sync when it is open for this line
    if(mdCurrentLine === lineId){
        $('#mdReceived').text(res.received_qty);
        $('#mdBalance').text(res.balance);
    }
}

// Build a history row for an entry returned by the server.
function buildLogRow(lineId, entry, seq){
    var note = entry.note ? entry.note : '';
    var remarks = entry.remarks ? entry.remarks : '';
    return '<tr id="logrow'+entry.id+'">'+
        '<td>'+seq+'</td>'+
        '<td><input type="number" step="1" min="1" class="form-control form-control-sm log-qty" value="'+entry.received_qty+'" style="width:80px" onkeypress="return event.charCode>=48 && event.charCode<=57"></td>'+
        '<td><span class="badge bg-soft-info text-info">'+entry.source+'</span></td>'+
        '<td><input type="text" class="form-control form-control-sm log-note" value="'+note+'"></td>'+
        '<td><input type="text" class="form-control form-control-sm log-remarks" value="'+remarks+'"></td>'+
        '<td>'+(entry.created_by_name||'')+'</td>'+
        '<td>'+(entry.created_at||'')+'</td>'+
        '<td class="log-updater"></td>'+
        '<td class="log-updated"></td>'+
        '<td class="text-nowrap">'+
            '<button type="button" class="btn btn-success btn-sm logSave" data-log-id="'+entry.id+'" data-line-id="'+lineId+'"><i class="mdi mdi-content-save"></i></button> '+
            '<button type="button" class="btn btn-outline-danger btn-sm logDelete" data-log-id="'+entry.id+'" data-line-id="'+lineId+'"><i class="mdi mdi-trash-can-outline"></i></button>'+
        '</td></tr>';
}

// --- Add Received quantity via AJAX (no full page refresh) -------------
$(document).on('submit', '.receiptForm', function(e){
    e.preventDefault();
    var $form  = $(this);
    var lineId = $form.data('line-id');
    var $btn   = $form.find('button[type=submit]');
    var $qty   = $form.find('.qty-input');
    var addQty = parseFloat($qty.val());
    if(isNaN(addQty) || addQty <= 0){ showToast('Enter a valid quantity.', 'error'); return; }
    if(addQty % 1 !== 0){ showToast('Quantity must be a whole number (no decimals).', 'error'); return; }

    $btn.prop('disabled', true);
    $.ajax({
        url: "{{route('containerreceiptstore')}}",
        type: "POST",
        dataType: "json",
        data: $form.serialize(),
        success: function(res){
            updateLineCells(lineId, res);
            var $body = $('#logsbody'+lineId);
            var n = $body.find('tr').length + 1;
            $body.append(buildLogRow(lineId, res.entry, n));
            $('#entriescount'+lineId).text(n);
            $('#entriesbtn'+lineId).removeClass('d-none');
            $qty.val('');
            $form.find('input[name=note], input[name=remarks]').val('');
            showToast('Received quantity added.');
        },
        error: function(xhr){ showToast(parseErr(xhr), 'error'); },
        complete: function(){ $btn.prop('disabled', false); }
    });
});

// --- Edit a received-entry log ----------------------------------------
$(document).on('click', '.logSave', function(){
    var $btn   = $(this);
    var logId  = $btn.data('log-id');
    var lineId = $btn.data('line-id');
    var $row    = $('#logrow'+logId);
    var qty     = parseFloat($row.find('.log-qty').val());
    var note    = $row.find('.log-note').val();
    var remarks = $row.find('.log-remarks').val();
    if(isNaN(qty) || qty <= 0 || qty % 1 !== 0){ showToast('Quantity must be a whole number.', 'error'); return; }

    $btn.prop('disabled', true);
    $.ajax({
        url: "{{route('containerreceiptlogupdate')}}",
        type: "POST",
        dataType: "json",
        data: { _token: CSRF, id: logId, received_qty: qty, note: note, remarks: remarks },
        success: function(res){
            updateLineCells(lineId, res);
            $row.find('.log-updater').text(res.log.updated_by_name || '');
            $row.find('.log-updated').text(res.log.updated_at || '');
            showToast('Entry updated.');
        },
        error: function(xhr){ showToast(parseErr(xhr), 'error'); },
        complete: function(){ $btn.prop('disabled', false); }
    });
});

// --- Delete a received-entry log --------------------------------------
$(document).on('click', '.logDelete', function(){
    if(!confirm('Delete this entry?')){ return; }
    var $btn   = $(this);
    var logId  = $btn.data('log-id');
    var lineId = $btn.data('line-id');

    $btn.prop('disabled', true);
    $.ajax({
        url: "{{url('/container/receiptlog')}}/"+logId+"/delete",
        type: "POST",
        dataType: "json",
        data: { _token: CSRF },
        success: function(res){
            $('#logrow'+logId).remove();
            updateLineCells(lineId, res);
            var cnt = $('#logsbody'+lineId+' tr').length;
            $('#entriescount'+lineId).text(cnt);
            if(cnt === 0){ $('#entriesbtn'+lineId).addClass('d-none'); }
            showToast('Entry deleted.');
        },
        error: function(xhr){ showToast(parseErr(xhr), 'error'); $btn.prop('disabled', false); }
    });
});

var balanceFilterOn = false;
function applyFilters(){
    var v = $('#partSearch').val().toLowerCase();
    $('#lineTable tbody tr.line-row').each(function(){
        var $row = $(this);
        var matchText = $row.find('.part-cell').text().toLowerCase().indexOf(v) > -1;
        var bal = parseFloat($row.find('.balance-cell').text()) || 0;
        var matchBal = !balanceFilterOn || bal > 0;   // filter on => keep only balance > 0
        var show = matchText && matchBal;
        $row.toggle(show);
        // keep each line's collapsible detail row in sync with its line row
        var detail = $row.next('tr.collapse');
        if(detail.length){ detail.toggle(show && detail.hasClass('show')); }
    });
}
$('#partSearch').on('keyup', applyFilters);
$('#balanceFilterBtn').on('click', function(){
    balanceFilterOn = true;
    $(this).addClass('d-none');
    $('#balanceClearBtn').removeClass('d-none');
    applyFilters();
});
$('#balanceClearBtn').on('click', function(){
    balanceFilterOn = false;
    $(this).addClass('d-none');
    $('#balanceFilterBtn').removeClass('d-none');
    applyFilters();
});

// --- Received "+" button: open popup, fill summary, relocate entries ---
var receivedModal = new bootstrap.Modal(document.getElementById('receivedModal'));
$(document).on('click', '.recv-plus', function(){
    var $btn   = $(this);
    var lineId = $btn.data('line-id');
    var $row   = $('#row'+lineId);
    mdCurrentLine = lineId;

    // top summary (Received/Balance read live from the row)
    $('#mdContainer').text('#' + $btn.data('container'));
    $('#mdBox').text($btn.data('box'));
    $('#mdPart').text($btn.data('part'));
    $('#mdQty').text($btn.data('qty'));
    $('#mdReceived').text($row.find('.received-val').text());
    $('#mdBalance').text($row.find('.balance-cell').text());
    $('#mdBrand').text($btn.data('brand'));
    $('#mdLocation').text($btn.data('location'));

    // point the modal add-form at this line and clear it
    var $mf = $('#modalReceiptForm');
    $mf.attr('data-line-id', lineId).data('line-id', lineId);
    $mf.find('input[name=container_line_id]').val(lineId);
    $mf.find('.qty-input').val('');
    $mf.find('input[name=note], input[name=remarks]').val('');

    // move this line's real entries table into the modal (edit/remove keep working)
    $('#modalEntriesSlot').append($('#entriestbl'+lineId));

    receivedModal.show();
});

// on close, move the entries table back to its original place
$('#receivedModal').on('hidden.bs.modal', function(){
    if(mdCurrentLine !== null){
        var $tbl = $('#entriestbl'+mdCurrentLine);
        if($tbl.length){ $('#entriesanchor'+mdCurrentLine).after($tbl); }
        mdCurrentLine = null;
    }
});
</script>
@stop
