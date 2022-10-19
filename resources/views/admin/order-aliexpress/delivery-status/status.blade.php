

    <input type="hidden"  class="order_package_id" value="{{$orderPackage->id}}">
    <input type="hidden"  class="order_id" value="{{$orderPackage->order_id}}">
    {{---order short details--}}
        <div style="margin-bottom: 1% !important;margin-top:0px;border-bottom: 1px dashed rgb(253, 222, 222);">
            <div class="row" style="margin-bottom:1%;">
                <div class="col-lg-5">
                    <strong>Order Package No : {{$orderPackage->order_package_number}}</strong>
                    <br/>
                    <strong>
                        Main Order Number : {{$orderPackage->order->order_number}}
                    </strong>
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

            <div class="row" style="margin-bottom: 5px;">
                <div class="col-lg-3">
                    <div class="left-area">
                            
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="custom-control custom-switch" style="text-align: center;">
                        <div style="background-color:#191a26;margin-left: -30px;color:#fff;padding-bottom: 5px;padding-top: 3px;">
                            <input type="checkbox" value="1" class="custom-control-input email_applicable_for_package" id="customSwitch1Package"  style="cursor: pointer;">
                            <label class="custom-control-label" for="customSwitch1Package" style="cursor: pointer;">Send email to the customer</label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3"></div>
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


    {{---order_products tables delivery status update--}}
    @foreach ($orderPackage->orderProducts as $index => $item)
        <div style="border:1px solid rgb(226, 226, 226); padding:2%;margin-left:2%;margin-top:.20%;">
            @php
                $carts =  json_decode($item->cart, true);
            @endphp
            <div class="row">
                <div class="col-lg-1">
                    <h5>{{( $index + ( 1))}} .</h5>
                </div>
                <div class="col-lg-7">
                    <strong> 
                        @if (array_key_exists('productName',$carts))
                            {{ $carts['productName']}}
                        @endif  
                    </strong><br/>
                    <small>
                        @if (array_key_exists('productSize',$carts))
                            Size :  {{ $carts['productSize'] }} 
                        @endif
                        @if (array_key_exists('productColor',$carts))
                        ,   Color :  {{ $carts['productColor']}}
                        @endif
                    </small>
                </div>
                <div class="col-lg-4">
                    <strong>Qtuantity : {{$item->product_quantity}}</strong><br/>
                    <span>Status: {{ucfirst($item->delivery_status)}}</span>
                </div>
                {{-- <div class="col-lg-3">
                    <strong>Status : {{$item->delivery_status}}</strong>
                </div> --}}
            </div>
                <!---success message-->
                <div style="text-align: center;margin-bottom:1%;">
                    <span class="on_processing_{{$item->id}}" style="display:none">Processing...</span>
                </div>
                <div class="alertSuccessProductStatusSingle alertSuccessProductStatus_{{$item->id}}" style="display: none;">
                    <div class="alert alert-success validation alertSuccessSingleProductStatus_{{$item->id}}" style="display: none;">
                        <button type="button" class="close alert-close"><span>×</span></button>
                        <ul class="text-left-ul successMessage_{{$item->id}}">
                        </ul>
                    </div>
                </div>  
                <!---success message-->
            <div class="row" style="border-bottom:.5px dashed #cab5bf;"></div>
            
            <input type="hidden" class="order_product_id" value="{{$item->id}}">
            
            <div class="row" style="margin-bottom: 5px;">
                <div class="col-lg-3">
                    <div class="left-area">
                            
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="custom-control custom-switch" style="text-align: center;">
                        <div style="background-color:#000106;color:#fff;padding-bottom: 5px;padding-top: 3px;margin-left: -30px">
                            <input type="checkbox" value="1" class="custom-control-input email_applicable_for_package_order_{{$item->id}}" id="customSwitch1_{{$item->id}}"  style="cursor: pointer;">
                            <label class="custom-control-label" for="customSwitch1_{{$item->id}}" style="cursor: pointer;">Send email to the customer</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-bottom: 5px !important">
                <div class="col-lg-3">
                    <div class="left-area">
                            <h4 class="heading">Delivery Status *</h4>
                            <p class="sub-heading">(It will be updated)</p>
                    </div>
                </div>
                <div class="col-lg-9">
                    <select name="" id="order_product_status_id_{{$item->id}}" style="margin-bottom: 5px;">
                        @foreach (order_products_status_hh() as $index => $value)
                            <option value="{{$index}}" {{ $item->delivery_status == $index ? "selected":"" }}>{{$value}}</option>
                        @endforeach
                        {{-- <option value="pending" {{$item->delivery_status == 'pending' ?'selected':""}}>Pending</option>
                        <option value="processing" {{$item->delivery_status == 'processing' ?'selected':""}}>Processing</option>
                        <option value="on delivery" {{$item->delivery_status == 'on delivery' ?'selected':""}}>On Delivery</option>
                        <option value="completed" {{$item->delivery_status == 'completed' ?'selected':""}}>Completed</option>
                        <option value="declined" {{$item->delivery_status == 'declined' ?'selected':""}}>Declined</option> --}}
                    </select>
                    <strong style="color:red;font-size: 12px;">
                        If this product is unavailable, please change the status ' Declined '
                    </strong>
                    {{-- <input type="text" value="{{$item->delivery_status ? ucfirst($item->delivery_status) : 'Pending'}}" class="input-field order_product_status_id_{{$item->id}}"  placeholder="Delivery Status"> --}}
                    <strong class="status_error_message_{{$item->id}}" style="color:red"></strong>
                </div>
            </div>

            <div class="row" style="margin-bottom: 5px;margin-top:5px;">
                <div class="col-lg-3">
                    <div class="left-area">
                            <h4 class="heading">Title *</h4>
                            <p class="sub-heading"></p>
                    </div>
                </div>
                <div class="col-lg-9">
                    <textarea class="input-field title_id_{{$item->id}}"  placeholder="Title" required="" style="height:70px!important"></textarea>
                    <strong class="title_error_message_{{$item->id}}" style="color:red"></strong>
                </div>
            </div>
            <div class="row" style="margin-bottom: 5px;">
                <div class="col-lg-3">
                    <div class="left-area">
                            <h4 class="heading">Details *</h4>
                            <p class="sub-heading"></p>
                    </div>
                </div>
                <div class="col-lg-9">
                    <textarea class="input-field text_id_{{$item->id}}"  name="text" placeholder="Details" required="" style="height:70px!important"></textarea>
                    <strong class="details_error_message_{{$item->id}}" style="color:red"></strong>
                </div>
            </div>

            <div class="row" style="margin-bottom: 0px;">
                <div class="col-lg-9"><div class="left-area"></div></div>
                <div class="col-lg-3">
                    <button class="addProductSubmit-btn add_status" data-id="{{$item->id}}" id="track-btn" type="submit" style="margin-top: 0px;width: 145px;height: 30px;">ADD</button>
                    <button class="addProductSubmit-btn ml=3 d-none" id="cancel-btn" type="button">Cancel</button>
                    <input type="hidden" value="{{route('aliexpress.admin.order.product.status.update')}}" class="orderProductStatusUpdate">
                </div>
            </div>
        </div>
    @endforeach
    {{---order_products tables delivery status update--}}