<div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Update Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  action="{{url('/updatestatus')}}" method="post">
                {{csrf_field()}}  
                <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <input type="hidden" name="sale_id" id="sale_id">
                                <input type="hidden" name="user_role" id="user_role" value="{{Auth::user()->role}}">
                                <div>
                                    <label>Status:</label>
                                    <select class="form-control"  name="order_status" id="order_status" required="" onchange="changeStatus()"> 
                                        @foreach($statuses as $sts)
                                            <option class="allsts s_{{$sts->show_order}} c_{{$sts->id}}" docs="{{$sts->is_document}}" delivery_tab="{{$sts->is_delivery}}" reasons="{{$sts->is_reason}}" value="{{$sts->name}}">{{$sts->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="inv_no" class="mt-3" style="display: none;">
                                    <label>Document Number:</label>
                                    <input class="form-control" type="text" value="0" placeholder="Enter Document Number" name="invoice_no" id="invoice_no" required="">
                                </div>

                                <div id="reason_tab" class="mt-3" style="display: none;">
                                    <label>Reason:</label>
                                    <textarea class="form-control" name="reason" id="reason"></textarea>
                                </div>
                                <div id="del_tab" style="display: none;">
                                    <div  class="mt-3" >
                                        <label>AWB Number:</label>
                                        <input class="form-control" type="text" placeholder="Enter AWB Number" name="awb_no" id="awb_no">
                                    </div>
                                    <div  class="mt-3">
                                        <label>No.of.Box:</label>
                                        <input class="form-control" type="number" min="1" placeholder="Enter No.of.Box" name="box" id="box">
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
            </div>
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->