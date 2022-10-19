{{---order short details--}}
        <div style="margin-bottom: 1% !important;margin-top:0px;border-bottom: 1px dashed rgb(253, 222, 222);">
            <div class="row" style="margin-bottom:1%;">
                <div class="col-lg-5">
                    <strong>Order Package No : {{$orderPackage->order_package_number}}</strong>
                </div>
                <div class="col-lg-4">
                    
                    @if($orderPackage->alix_order_id)
                        <strong>Ali-x Order No : </strong>
                        <small style="color:green;font-weight:700;">
                        #{{$orderPackage->alix_order_id}}
                        </small>
                        @else
                        <small style="color:red;font-size:9px;">
                            Order Not Placed
                        </small>
                    @endif
                    
                </div>
                <div class="col-lg-3">
                    <strong>Status : {{ucfirst($orderPackage->delivery_status)}}</strong>
                </div>
            </div>
        </div>
    {{---order short details--}}

  
    {{---order package main delivery status update--}}
        <div style="margin-bottom: 5% !important;margin-top:2%;border-bottom: 1px dashed hsl(0, 38%, 69%);">
            <!---success message-->
            <div class="alertSuccessMainStatus" style="display: none;">
                <div class="alert alert-success validation" style="display: none;">
                    <button type="button" class="close alert-close"><span>×</span></button>
                    <ul class="text-left-ul">
                    </ul>
                </div>
            </div>  
            <!---success message-->
            <!---success message-->
            <div style="text-align: center;margin-bottom:1%;">
                <span class="on_processing_main_status" style="display:none">Processing...</span>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <div class="left-area">
                            <h4 class="heading">Delivery Status *</h4>
                            <p class="sub-heading">(Package delivery status)</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <select name="" id="main_order_package_status" style="margin-bottom: 5px;">
                        @foreach (order_packages_status_hh() as $index => $value)
                            <option value="{{$index}}" {{ $orderPackage->delivery_status == $index ? "selected":"" }}>{{$value}}</option>
                        @endforeach
                        {{-- <option value="pending" {{$orderPackage->delivery_status == 'pending' ?'selected':""}}>Pending</option>
                        <option value="processing" {{$orderPackage->delivery_status == 'processing' ?'selected':""}}>Processing</option>
                        <option value="on delivery" {{$orderPackage->delivery_status == 'on delivery' ?'selected':""}}>On Delivery</option>
                        <option value="partial delivered" {{$orderPackage->delivery_status == 'partial delivered' ?'selected':""}}>Partial Delivered</option>
                        <option value="completed" {{$orderPackage->delivery_status == 'completed' ?'selected':""}}>Completed</option>
                        <option value="declined" {{$orderPackage->delivery_status == 'declined' ?'selected':""}}>Declined</option> --}}
                    </select>
                </div>
                <div class="col-lg-3">
                    <button class="addProductSubmit-btn" id="order_package_status_update" type="submit" style="margin-top: 0px;width: 145px;height: 35px;">
                        Update
                    </button>
                    <input type="hidden" class="orderPackageStatusUpdate" value="{{ route('aliexpress.admin.order.package.status.update') }}">
                </div>
            </div>
        </div>
    {{---order package main delivery status update--}}

