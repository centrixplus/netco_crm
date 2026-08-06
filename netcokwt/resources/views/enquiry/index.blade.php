@extends('layouts.master')
@section('title','Enquiry')
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
    <h4 class="card-title">Search Part Details </h4>
    <!-- <div class="spinner-grow text-primary m-1" role="status">
                                                
    </div> -->
    <p class="card-title-desc">Search by all information below</p>
    <form action="{{route('enquirystore')}}" method="post">
    {{csrf_field()}}
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="cardnameInput">Brand <span class="text-danger">*</span></label>
                <select class="form-control" id="brand_id" name="brand_id" required>
                    <option value="">Choose...</option>
                    @foreach($brands as $brd)
                    <option value="{{$brd->id}}">{{$brd->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="form-group mt-4 mb-0">
                <label for="expirydateInput">Part number</label>
                <input type="text" class="form-control" name="partno" id="expirydateInput" placeholder="eg: 668621HB4B">
                <input type="hidden" name="form_type" value="enquiry">
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group mt-4 mb-0">
                <label for="cvvcodeInput">Quantity</label>
                <input type="text" class="form-control" name="qty" id="cvvcodeInput" placeholder="Enter Qty">
            </div>
        </div>
        
        <p class="text-center font-size-16 mt-2">(OR)</p>
    </div>
    <div class="row">
        <div class="col-sm-12 mb-0">
        <textarea type="text" class="form-control" name="search_text" id="search-text" placeholder="Search... (eg: Part-number Quantity) Like (668621HB4B   2)"></textarea>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-sm-6">
            <a href="{{url('/enquiry')}}" class="btn text-muted d-none d-sm-inline-block btn-link">
                <i class="mdi mdi-arrow-left me-1"></i> Refresh </a>
        </div> <!-- end col -->
        <div class="col-sm-6">
            <div class="text-end">
                <button type="submit" class="btn btn-info"> <i class="mdi mdi-cart me-1"></i> Proceed to Cart </button>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
    </form>
    </div>
</div>

</div>
<div class="col-md-7">
    
    <div class="table-responsive bg-white mt-2">
    @if($enquiries->count() > 0)
        <center><h5 class="text-info p-3">Finally Click Proceed to Enquiry Request Button to send Enquiry Request</h5></center>
    @endif
    <table class="table align-middle table-nowrap mb-0">
        <thead class="table-light">
            <tr>
                <th scope="col">Brand</th>
                <th scope="col">Name / Part</th>
                <th scope="col">Qty</th>
                <th scope="col">Available</th>
                <th>Remove</th>
            </tr>
        </thead>
        <tbody>
            
            @foreach($enquiries as $log)
                @if(!empty($log->product))
                <tr>
                    <td>{{$log->brand->name}}</td>
                    <td>{{$log->product->name}} - ({{$log->part}})</td>
                    <td>{{$log->quantity}}</td>
                    <td><h6 class="text-success">Available</h6></td>
                    <td><a onclick="return confirm('Are you sure?')" href="{{url('/enquirycart')}}/{{$log->id}}/delete" class="btn btn-outline-danger waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-trash-can-outline"></i></a></td>
                </tr>
                @else
                <tr>
                    <td>{{$log->brand->name}}</td>
                    <td>{{$log->part}}</td>
                    <td>{{$log->quantity}}</td>
                    <td><h6 class="text-danger">Parts Not Found</h6></td>
                    <td><a onclick="return confirm('Are you sure?')" href="{{url('/enquirycart')}}/{{$log->id}}/delete" class="btn btn-outline-danger waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-trash-can-outline"></i></a></td>
                </tr>
                @endif
            @endforeach
        </tbody>
        @if($enquiries->count() > 0)
        <tfoot>
            
                <form action="{{route('enquirysave')}}" method="post">
                    {{csrf_field()}}
                        @if(Auth::user()->role=='Customer')
                          <input type="hidden" name="customer_id" id="customer_id" value="{{Auth::user()->customer_id}}">
                        @else
                        <tr>
                            <td colspan="3">
                                <center>
                                <select class="form-control select2" style="width: 300px;" id="customer_id" name="customer_id" required="">
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $log)
                                    <option value="{{$log->id}}">{{$log->name}} - {{$log->mobile}}  @if(Auth::user()->role=='admin') - {{$log->code}} @endif</option>
                                    @endforeach
                                </select>
                            </center>
                            </td>
                            <td colspan="2">
                                <center>
                                    <select class="form-control" id="store_id" name="store_id" required="">
                                    <option value="">Select Store</option>
                                    @foreach($stores as $log)
                                    <option @if(Auth::user()->store_id==$log->id) selected="" @endif value="{{$log->id}}">{{$log->name}}</option>
                                    @endforeach
                                </select>
                                </center>
                            </td>
                        </tr>
                        @endif
                        <input type="hidden" name="form_type" value="enquiry">
                        <tr>
                          <td colspan="5">
                              <center>
                                  <button type="submit" class="btn btn-success"> <i class="mdi mdi-truck-fast me-1"></i> Proceed to Enquiry Request</button>

                                   <a onclick="return confirm('Are you sure?')" href="{{url('/enquiryrequestrest')}}" class="btn btn-warning">Reset </a>
                              </center>
                          </td>
                        </tr>
                </form>
            </tr>
        </tfoot>
        @endif
    </table>

</div>

</div>
</div>
@endsection
@section('ScriptContent')
<script src="{{asset('/assets')}}/libs/select2/js/select2.min.js"></script>
<script src="{{asset('/assets')}}/js/pages/form-advanced.init.js"></script>
@endsection