<div class="row">
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h4 class="text-secondary">last 5 Receipts List</h4>
            <div class="table-responsive">
                <table id="datatable-buttons" class="table table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Company</th>
                            <th>Branch</th>
                            <th>Customer Code</th>
                            <th>Customer</th>
                            <th>Receipt No</th>
                            <th>Receipt Date</th>
                            <th>Receipt Amount</th>
                            <th>Payment Type</th>
                            <th>Payee Refrence No</th>
                            <th>Particulars</th>
                            <th>Payment Deposit Refrence</th>
                            <th>Deposit Bank</th>
                            <th>Status</th>
                            <th>Print</th>
                        </tr>
                    </thead>           
                    <tbody>
                        <?php $x=1; ?>
                        @foreach ($receiptslastfive as $index)

                        <tr class="{{$index->rvstage->color_code}}">
                            <td>{{$x++}}</td>
                            <td>{{$index->company->name}}</td>
                            <td>{{$index->branch->name}}</td>
                            <td>@if($index->customer){{$index->customer->code}}@endif</td>
                            <td>@if($index->customer){{$index->customer->name}}@endif</td>
                            <td>{{$index->receipt_no}}</td>
                            <td>{{date('d-m-Y',strtotime($index->receipt_date))}}</td>
                            <td>{{$index->receipt_amount}} {{$index->company->country->currency_unit}}</td>
                            <td>{{$index->paymenttype->name}}</td>
                            <td>{{$index->payment_reference_no}}</td>
                            <td>{{$index->particulars}}</td>
                            <td>{{$index->payment_deposit_refrence_number}}</td>
                            <td>@if($index->paymentbank){{$index->paymentbank->name}}@endif</td>
                            <td>{{$index->rvstage->name}}</td>
                           
                            <td><a href="{{url('/receiptprint')}}/{{$index->id}}" target="_blank" class="btn btn-outline-warning waves-effect waves-light btn-sm font-size-18"><i class="mdi mdi-cloud-print-outline"></i></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>