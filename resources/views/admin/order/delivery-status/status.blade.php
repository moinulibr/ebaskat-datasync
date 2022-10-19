    
    

    <input type="hidden"  class="order_id" value="{{$order->id}}">
    {{---main order status --}}
        <div class="row">
            <h6>Main Order</h6>
            <table class="table table-bordered" style="background-color: rgb(255 252 253);">
                <thead>
                    <tr>
                        <th style="padding:1%;width:17%;padding-right:0px;">Order Number</th>
                        <th style="width:1%;">:</th>
                        <th style="padding:1%;text-align:left;width:35%;">
                            {{$order->order_number}}
                        </th>
                        <th style="padding:1%;width:9%;text-align:right;padding-left:0px;">
                            <strong style="padding: 3%;border: .40px solid #dfe5e5;background-color: #fafffc;border-radius: 3px;">
                                Status
                            </strong>
                        </th>
                        <th style="width:1%">:</th>
                        <th style="padding:1%;width:29%;text-align:left">
                            {{ucfirst($order->status)}}
                        </th>
                    </tr>
                    <tr>
                        <td colspan="6"></td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <!---success message-->
                            <div class="alertSuccessMainStatus" style="display: none;">
                                <div class="alert alert-success validation" style="display: none;">
                                    <button type="button" class="close alert-close"><span>×</span></button>
                                    <ul class="text-left-ul">
                                    </ul>
                                </div>
                            </div>  
                            <!---success message-->
                            <!---processing-->
                            <div style="text-align: center;margin-bottom:1%;">
                                <span class="on_processing_main_status" style="display:none">Processing...</span>
                            </div>
                            <!---processing-->

                            <div class="row" style="margin-bottom: 5px;">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-6">
                                    <div class="custom-control custom-switch" style="text-align: center;">
                                        <div style="background-color:#191a26;margin-left: -30px;color:#fff;padding-bottom: 5px;padding-top: 3px;">
                                            <input type="checkbox" value="1" class="custom-control-input email_applicable_for_main_order" id="customSwitch1MainOrder"  style="cursor: pointer;">
                                            <label class="custom-control-label" for="customSwitch1MainOrder" style="cursor: pointer;">Send email to the customer</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3"></div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                            <h4 class="heading">Delivery Status *</h4>
                                            <p class="sub-heading">(Order delivery status)</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <select name="" id="main_order_status" style="margin-bottom: 5px;">
                                        @foreach (main_orders_status_hh() as $index => $value)
                                            <option value="{{$index}}" {{ $order->status == $index ? "selected":"" }}>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <button class="addProductSubmit-btn" id="order_status_update" type="submit" style="margin-top: 0px;width: 145px;height: 35px;">
                                        Update
                                    </button>
                                    <input type="hidden" class="mainOrderStatusUpdate" value="{{ route('admin.main.order.status.update') }}">
                                </div>
                            </div>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
    {{---main order status--}}


    
    {{---order package short --}}
        <div class="row" style="margin-bottom: 4%;">
            <h6>Order Packages</h6>
            <table class="table">
                <thead style="border:1px solid #dbd9d9">
                    <tr>
                        <th style="padding:1%;">
                            Sl.
                        </th>
                        <th style="border-right:1px solid #dbd9d9 !important;padding:1%;text-align: center;">Order Package No</th>
                        <th style="border-right:1px solid #dbd9d9 !important;padding:1%;text-align: center;">Merchant</th>
                        <th style="border-right:1px solid #dbd9d9 !important;padding:1%;text-align: center;">Order Status</th>
                        <th style="padding:1%;text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody style="border:1px solid #dbd9d9">
                    @foreach ($order->orderPckages as $k=> $orderPackage)
                        <tr>
                            <td style="padding:1%;">
                                <span class="badge badge-info">
                                    {{($k + (1))}}
                                </span>
                            </td>
                            <td style="border-right:1px solid #dbd9d9;padding:1%;text-align: center;">
                                <h6 class="activeClass_{{$orderPackage->id}} allInactivePackagesWhenChangesStatus">
                                    #{{$orderPackage->order_package_number}}
                                </h6>
                            </td>
                            <td style="border-right:1px solid #dbd9d9;padding:1%;text-align: center;">
                                <strong>
                                    {{dropshipingVendorLabel_hh($orderPackage->merchant_id)}}
                                </strong>
                            </td>
                            <td style="border-right:1px solid #dbd9d9;padding:1%;text-align: center;">
                                <strong style="padding: 2%;border: .40px solid #dfe5e5;background-color: #eefdfd;border-radius: 3px;">
                                    {{ucfirst($orderPackage->delivery_status)}}
                                </strong>
                            </td>
                            <td style="padding:1%;text-align: center;">
                                <a href="#" data-href="" data-id="{{$orderPackage->id}}" class="changePackageStatus currentChangingStatusText_{{$orderPackage->id}} currentChangingStatusText btn btn-info btn-sm">Change Status</a>
                            </td> 
                            <input type="hidden" class="selectedOrderPackageId">
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    {{---order package short --}}


    @foreach ($order->orderPckages as $key=> $orderPackage)
        <input type="hidden"  class="order_package_id_{{$orderPackage->id}}" value="{{$orderPackage->id}}">
        <!---order packages with order_products tables delivery status update-->
            <div class="fullPackage_{{$orderPackage->id}} allFullPackage" style="display:none;">
                <div class="row" style="background-color: rgb(245 251 250);padding-top:2%;">
                    <div class="col-md-12">

                        <!---package title-->
                            <div class="row">
                                <div class="col-md-1" >
                                    <span style="background-color:greenyellow;color:#000106;padding:5px;">
                                        {{($key + (1))}}
                                    </span>
                                </div>
                                <div class="col-md-6" style="text-align: left;">
                                    <h6><strong>Package : </strong> 
                                        <strong class="activeClass_{{$orderPackage->id}} allInactivePackagesWhenChangesStatus">
                                            #{{$orderPackage->order_package_number}}
                                        </strong>
                                    </h6>
                                </div>
                                <div class="col-md-5" style="text-align: right;">
                                    <h6>
                                        <strong>Merchant : </strong> 
                                        <strong style="background-color:greenyellow;color:#000106;padding:5px;">
                                            {{dropshipingVendorLabel_hh($orderPackage->merchant_id)}}
                                        </strong>
                                    </h6>
                                </div>
                            
                            </div>
                        <!---package title-->

                        <!---order_package status update and short package short-->
                        <table class="table table-bordered" style="background-color: rgb(255 255 255);">
                            <thead>
                                <tr>
                                    <th style="padding:1%;width:20%;padding-right:0px;">Package Number</th>
                                    <th style="width:1%;">:</th>
                                    <th style="padding:1%;text-align:left;width:35%;">
                                        #{{$orderPackage->order_package_number}}
                                    </th>
                                    <th style="padding:1%;width:9%;text-align:right;padding-left:0px;">
                                        <strong style="padding: 3%;border: .40px solid #dfe5e5;background-color: #fafffc;border-radius: 3px;">
                                            Status
                                        </strong>
                                    </th>
                                    <th style="width:1%">:</th>
                                    <th style="padding:1%;width:29%;text-align:left">
                                        {{ucfirst($orderPackage->delivery_status)}}
                                    </th>
                                </tr>
                                <tr><td colspan="6"></td></tr>
                                <tr>
                                    <td colspan="6">
                                        <!---success message-->
                                        <div class="alertSuccessOrderPackageDeliveryStatus_{{$orderPackage->id}}" style="display: none;">
                                            <div class="alert alert-success validation orderPackageDeliveryStatus_{{$orderPackage->id}}" style="display: none;">
                                                <button type="button" class="close alert-close"><span>×</span></button>
                                                <ul class="text-left-ul orderPackageDeliveryStatusText_{{$orderPackage->id}}">
                                                </ul>
                                            </div>
                                        </div>  
                                        <!---success message-->
                                        <!---success message-->
                                        <div style="text-align: center;margin-bottom:1%;">
                                            <span class="on_processing_order_package_deliver_status_{{$orderPackage->id}}" style="display:none">Processing...</span>
                                        </div>

                                        <!---order_packages : delivery_status update-->
                                        <div class="row" style="margin-bottom: 5px;">
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-6">
                                                <div class="custom-control custom-switch" style="text-align: center;">
                                                    <div style="background-color:#090f2e;color:#fff;padding-bottom: 5px;padding-top: 3px;margin-left: -30px">
                                                        <input type="checkbox" value="1" class="custom-control-input email_applicable_for_package_order_{{$orderPackage->id}}" id="customSwitch1PackageOrder_{{$orderPackage->id}}"  style="cursor: pointer;">
                                                        <label class="custom-control-label" for="customSwitch1PackageOrder_{{$orderPackage->id}}" style="cursor: pointer;">Send email to the customer</label>
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
                                                <select name="" id="order_package_delivery_status_{{$orderPackage->id}}" style="margin-bottom: 5px;">
                                                    @foreach (order_packages_status_hh() as $index => $value)
                                                        <option value="{{$index}}" {{ $orderPackage->delivery_status == $index ? "selected":"" }}>{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3">
                                                <button class="addProductSubmit-btn updateOrderPackageDeliveryStatus" data-id="{{$orderPackage->id}}" id="order_package_status_update" type="submit" style="margin-top: 0px;width: 145px;height: 35px;">
                                                    Update
                                                </button>
                                                <input type="hidden" class="orderPackageDeliveryStatusUpdateFromMianOrder" value="{{ route('admin.order.package.delivery.status.update.from.main.order') }}">
                                            </div>
                                        </div>
                                        <!---order_packages : delivery_status update-->
                                    </td>
                                </tr>
                            </thead>
                        </table>
                        <!---order_package status update and short package short-->

                    </div>
                    <!---col-md-12-->

                    <!---col-md-12-->
                    <!---order_products: delivery_status-->
                    <div class="col-md-12" style="margin-top: -20px;">
                        @foreach ($orderPackage->orderProducts as $index => $item)
                            <div style="border:1px solid rgb(226, 226, 226); padding:2%;margin-top:.20%;padding-top:0xp">
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
                                </div>
                                    <!---success message-->
                                    <div style="text-align: center;margin-bottom:1%;">
                                        <span class="on_processing_order_product_delivery_status_{{$item->id}}" style="display:none">Processing...</span>
                                    </div>
                                    <div class="alertSuccessOrderProductStatusSingle alertSuccessOrderProductDeliveryStatus_{{$item->id}}" style="display: none;">
                                        <div class="alert alert-success validation alertSuccessSingleOrderProductDeliveryStatus_{{$item->id}}" style="display: none;">
                                            <button type="button" class="close alert-close"><span>×</span></button>
                                            <ul class="text-left-ul successMessageSingleOrderProductDeliveryStatus_{{$item->id}}">
                                            </ul>
                                        </div>
                                    </div>  
                                    <!---success message-->
                                <div class="row" style="border-bottom:.5px dashed #cab5bf;"></div>
                                
                                <input type="hidden" class="order_product_id_{{$item->id}}" value="{{$item->id}}">
                                
                                <div class="row" style="margin-bottom: 5px;">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-9">
                                        <div class="custom-control custom-switch" style="text-align: center;">
                                            <div style="background-color:#000106;color:#fff;padding-bottom: 5px;padding-top: 3px;margin-left: -30px">
                                                <input type="checkbox" value="1" class="custom-control-input email_applicable_for_package_order_product_{{$item->id}}" id="customSwitch1PackgeOrderProduct_{{$item->id}}"  style="cursor: pointer;">
                                                <label class="custom-control-label" for="customSwitch1PackgeOrderProduct_{{$item->id}}" style="cursor: pointer;">Send email to the customer</label>
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
                                        <select name="" id="order_product_delivery_status_{{$item->id}}" style="margin-bottom: 5px;">
                                            @foreach (order_products_status_hh() as $index => $value)
                                                <option value="{{$index}}" {{ $item->delivery_status == $index ? "selected":"" }}>{{$value}}</option>
                                            @endforeach
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
                                        <button class="addProductSubmit-btn add_order_product_status" data-id="{{$item->id}}" data-package-id="{{$orderPackage->id}}"  type="submit" style="margin-top: 0px;width: 145px;height: 30px;">ADD</button>
                                        <button class="addProductSubmit-btn ml=3 d-none" id="cancel-btn" type="button">Cancel</button>
                                        <input type="hidden" value="{{route('admin.order.product.delivery.status.update.from.main.order')}}" class="orderProductDeliveryStatusUpdateFromMianOrder">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!---order_products: delivery_status-->
                    <!---col-md-12-->
                </div>
            </div>
        <!---order_products tables delivery status update-->
    @endforeach