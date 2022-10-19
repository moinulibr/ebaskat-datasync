   
   
   <div class="table-responsiv">
        <table id="example2" class="table table-hover dt-responsive" cellspacing="0" width="100%">
            <thead>
            <tr>
                <tr>
                    <th>{{ __('Product Title') }}</th>
                    <th width="5%">{{ __('Qty') }}</th>
                    <th width="17%">
                        <small style="font-size:12px;">
                            {{ __('Bigbuy Order No') }}
                        </small>
                    </th>
                    <th width="5%">{{ __('Status') }}</th>
                    <th width="15%">{{ __('Action') }}</th>
                </tr>
            </tr>
            </thead>
            <tbody>
                @foreach($package->orderProducts as $product)
                    <tr>
                        <td>
                            @php
                                $carts =  json_decode($product->cart, true);
                            @endphp
                            @if (array_key_exists('productName',$carts))
                                {{ $carts['productName']}}
                            @endif
                            {{-- {{ $product->products?$product->products->name :NULL }} --}} <br>
                            <small>
                                @if (array_key_exists('productSize',$carts))
                                    @if ($carts['productSize'])
                                        Size :  {{ $carts['productSize'] }} 
                                    @endif  
                                @endif
                                @if (array_key_exists('productColor',$carts))
                                    @if ($carts['productColor'])
                                        ,   Color :  {{ $carts['productColor']}}
                                    @endif  
                                @endif
                            </small>
                            @if ( $product->ds_order_no )
                                <br/>
                                Bigbuy Order ID#{{ $product->ds_order_no }}
                            @endif
                        </td>
                        <td>{{ $product->product_quantity }}</td>
                        <td>
                            <input type="hidden" class="orderPackageIdForSingle" value="{{$package->id}}">
                            <input type="hidden" class="orderIdForSingle" value="{{$package->order_id}}">
                            <input type="hidden" class="displayAllProductsUrlIdForSingle" value="{{route('bigbuy.admin.display.all.products.for.single.order.place.to.bigbuy',$package->order_id)}}">
                            @php
                                $orderDatas = json_decode($product->ds_order_data,true);
                            @endphp
                            <span style="" >
                                @if($product->ds_order_no)
                                        <strong class="bigbuyOrderNumberUpdateForSingleOrder" data-order_product_name="@if (array_key_exists('productName',$carts)){{ $carts['productName']}}@endif" data-id="{{$product->id}}"  data-ds_order_no="{{$product->ds_order_no}}"  style="color:green;font-weight:700;background-color:aliceblue;padding:3px;cursor: pointer;">
                                        #{{$product->ds_order_no}} 
                                        </strong>
                                    @else
                                        @if ($product->ds_order_data)
                                            @if (is_array($orderDatas))
                                                <strong class="bigbuyOrderNumberUpdateForSingleOrder" data-order_product_name="@if (array_key_exists('productName',$carts)){{ $carts['productName']}}@endif" data-id="{{$product->id}}"  data-ds_order_no="{{$product->ds_order_no}}"  style="color:rgb(43, 0, 255);font-size:9px;cursor: pointer;">
                                                    Bigbuy Order No
                                                </strong>
                                                @else
                                                <small style="color:red;font-size:9px;">
                                                    Order Not Placed 
                                                </small>
                                            @endif
                                            @else
                                            <small style="color:red;font-size:9px;">
                                                Order Not Placed 
                                            </small>  
                                        @endif
                                @endif
                            </span>
                        </td>
                        <td>
                            {{ $product->delivery_status ? ucfirst($product->delivery_status) : "Pending" }}
                        </td>
                        <td>
                            @if ($product->ds_order_no)
                                <small style="font-size:11px;background-color:green;color:#ffff;padding:1px 2px;">Order Placed</small>
                                @else
                                <span class="single_order_placing_to_bigbuy place_{{$product->id }}" data-id="{{$product->id }}"  data-order_id_{{$product->id }} ="{{$package->order_id }}"  data-order_product_id_{{$product->id }} = "{{$product->id }}" data-order_package_id_{{$product->id }} ="{{$product->order_package_id }}" data-product_id_{{$product->id }} = "{{$product->product_id}}" data-href_{{$product->id }} = "{{route('bigbuy.admin.single.order.placing.to.bigbuy')}}"  style="cursor: pointer;font-size:11px;background-color:rgb(226, 226, 221);color:green;padding:1px;">
                                    <i class="fas fa-forward"></i>Place Order  
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>