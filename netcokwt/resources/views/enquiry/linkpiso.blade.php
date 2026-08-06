@extends('layouts.master')
@section('title','PI to SO')
@section('StyleContent')
<link href="{{asset('/assets')}}/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{asset('/assets')}}/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('PageContent')



<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
            <h4 class="card-title">Convert Sale Order </h4>
            <!-- <div class="spinner-grow text-primary m-1" role="status">
                                                        
            </div> -->
            <p class="card-title-desc">Search by all information below</p>
            
            <form action="{{route('convertinvoicetoso')}}" method="post">
            {{csrf_field()}}
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="cardnameInput">Customer</label>
                        <h5>{{$customer->name}} - {{$customer->code}}</h5>
                        <input type="hidden" name="customer_id" id="customer_id" value="{{$customer->id}}">
                        <input type="hidden" name="invoice_id"  value="{{$invoice->id}}">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="cardnameInput">Profoma Invoice <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="invoice_id" name="pi[]" multiple="multiple" onchange="getPIOrderList()" required>
                            <option value="">Choose...</option>
                            @foreach($invoices as $log)
                            <option @if($log->id==$invoice->id) selected='' @endif  value="{{$log->id}}">{{$log->id }} - {{date('d M, Y',strtotime($log->invoice_date))}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-4 mt-3">
                    <label>Select Priority: <span class="text-danger">*</span></label>
                    <select class="form-control customer" id="priority" name="priority">
                        <option value="Next Day Delivery">Next Day Delivery</option>
                        <option value="Priority">Priority </option>
                    </select>
                </div>
                
                <div class="col-lg-6 mt-3">
                    <label>Order Note: </label>
                    <textarea name="comment" id="comment" class="form-control" placeholder="Enter Order Note"></textarea>
                </div>  
            </div>
            
            <div class="row mt-4">
                <div class="col-sm-6">
                    <a href="{{url('/saleorder')}}" class="btn text-muted d-none d-sm-inline-block btn-link">
                        <i class="mdi mdi-arrow-left me-1"></i> Refresh </a>
                </div> <!-- end col -->
                <div class="col-sm-6">
                    <div class="text-end">
                        <button type="submit" class="btn btn-info"> <i class="mdi mdi-cart me-1"></i> Proceed to SO </button>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
            </form>
            </div>
        </div>

    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                {!!Session::get('alert')!!}
                <h5 class="mt-3">Selected Proforma Invoice List:</h5>
                <div id="invoicelist"></div>
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
<script type="text/javascript">
    function loadCustomerApprovedQuote() {
        var customer_id=$('#customer_id').val();
        
    }
    getPIOrderList();

    function getPIOrderList(){
        var invoice_ids=$('#invoice_id').val();
        $.ajax({ url: "{{url('/getPIOrderList')}}"+'/'+invoice_ids,
            type: "get", cache: false, dataType: 'json',
            success: function (data) {
                $('#invoicelist').html(data.invoicelist);
                $("#datatable-buttons1").DataTable();
            }
        })
    }
</script>
@endsection