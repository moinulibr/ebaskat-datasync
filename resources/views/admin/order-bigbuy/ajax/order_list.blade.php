<div class="table-responsiv">
    <button class="allOrderPlaceToBigbuy btn btn-sm btn-primary" style="display: none;">Place Order To Bigbuy</button>
    <button class="allOrderSyncStatus btn btn-sm btn-success" style="display: none;">Sync Order Status </button>
    <div class="gocover" style="background: url({{asset('assets/images/xloading.gif')}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
    <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>
                    <input class="check_all_class" type="checkbox" value="all" name="check_all">
                </th>
               <th>{{ __('Order Package Number') }}</th>
               <th>{{ __('Order Number') }}</th>
                <th><small>{{ __('Bigbuy Order Placement') }}</small></th>
                <th>{{ __('Total Qty') }}</th>
                <th>{{ __('Total Cost') }}</th>
                <th>{{ __('Payment Status') }}</th>
                <th>{{ __('Order Status') }}</th>
                <th>{{ __('Options') }}</th>
            </tr>
        </thead>
        <body>
            @foreach($pacakages as $orderPackage)    
                <tr>
                    <td>
                        <input class="check_single_class" type="checkbox"  name="checked_id[]" value="{{ $orderPackage->order_id }}" class="check_single_class" id="{{$orderPackage->order_id}}"   data-aliex_order_id="{{$orderPackage->alix_order_id}}">
                    </td>
                    <td>{{$orderPackage->order_package_number}}</td>
                    <td>
                        {{$orderPackage->order->order_number}} <br/>
                        <span style="color:rgb(22, 97, 32);background-color:antiquewhite;margin-top:3px;">
                            {{$orderPackage->order->customer_email}}
                        </span>
                    </td>
                    
                    <td style="text-align: center;"> 
                        @php
                            $bigbuyOrderPlacement = bigbuy_order_placement_hh($orderPackage);
                        @endphp
                        {{-- {{ bigbuy_order_placement_status_hh($bigbuyOrderPlacement) }} --}}
                        
                        
                            @php
                                $orderDatas = json_decode($orderPackage->alix_order_data,true);
                            @endphp
                            <span style="" >
                                @if($orderPackage->alix_order_id)
                                        <strong class="bigbuyOrderNumberUpdate" data-package_number="{{$orderPackage->order_package_number}}" data-id="{{$orderPackage->id}}"  data-aliex_order_id="{{$orderPackage->alix_order_id}}"  style="color:green;font-weight:700;background-color:aliceblue;padding:3px;cursor: pointer;">
                                        #{{$orderPackage->alix_order_id}} 
                                        </strong>
                                    @else
                                        @if ($orderPackage->alix_order_data)
                                            @if (is_array($orderDatas))
                                                <strong class="bigbuyOrderNumberUpdate" data-package_number="{{$orderPackage->order_package_number}}" data-id="{{$orderPackage->id}}"  data-aliex_order_id="{{$orderPackage->alix_order_id}}"  style="color:rgb(43, 0, 255);font-size:9px;cursor: pointer;">
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

                            @if ($orderPackage->alix_order_data)
                                @if (is_array($orderDatas))
                                    <small  style="background-color:rgb(245, 247, 245);color:rgb(12, 20, 84);font-weight:600;padding:3px;">
                                        {{ $orderDatas['internalReference'] }}
                                    </small>
                                @endif
                            @endif 
                       
                    </td>

                    <td>{{ $orderPackage->orderProducts?$orderPackage->orderProducts->sum('product_quantity'):0 }}</td>
                    <td>
                        {{ number_format($orderPackage->totalProductPrice($orderPackage->orderProducts),2, '.', '') }}
                    </td>
                    <td>{{ucfirst($orderPackage->payment_status)}}</td>
                    <td>{{ucfirst($orderPackage->delivery_status)}}</td>
                    <td>
                        <div class="godropdown">
                            <button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button>
                            <div class="action-list">
                                <a href="{{ route('bigbuy.admin.order.show', $orderPackage->id )}}" > <i class="fas fa-eye"></i> Details</a>
                                @if($orderPackage->alix_order_id)
                                    <a class="orderPackageStatusUpdateBySyncingIndividually" href="#" data-href="{{route('bigbuy.admin.order.package.status.update.by.syncing.individually')}}" data-id="{{$orderPackage->alix_order_id}}"> <i class="fas fa-sync"></i> Sync Status</a>
                                @endif
                                
                               {{--  @if ($bigbuyOrderPlacement == 0)
                                    <a class="adminOrderToBigbuy" href="#" data-href="{{route('bigbuy.adminOrderToBigbuy', $orderPackage->order_id)}}"> <i class="fas fa-forward"></i>
                                        Place To Bigbuy
                                    </a>
                                @endif --}}
                                
                                @if (!$orderPackage->alix_order_data || !$orderPackage->alix_order_id)
                                    <a class="adminOrderToBigbuy" href="#" data-href="{{route('bigbuy.adminOrderToBigbuy', $orderPackage->order_id)}}"> <i class="fas fa-forward"></i>
                                        Place To Bigbuy
                                    </a>
                                @endif
                                
                            
                                    <a class="displayAllProductForSingleOrderPlace" data-package_id="{{$orderPackage->id}}" data-order_id="{{$orderPackage->order_id}}" data-href="{{route('bigbuy.admin.display.all.products.for.single.order.place.to.bigbuy', $orderPackage->order_id)}}"> <i class="fas fa-forward"></i>
                                        Single Order Place 
                                    </a>
                                
                                
                                <a class="adminOrderDeliveryStatus" href="#" data-href="{{route('bigbuy.admin.delivery.status')}}" data-id="{{$orderPackage->id}}"> <i class="fas fa-play"></i> Delivery Status</a>
                                <a class="adminOrderTrackingDetails" href="#" data-href="{{route('bigbuy.admin.tracking.details')}}" data-id="{{$orderPackage->order_id}}"> <i class="fas fa-truck"></i>Tracking Details</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </body>
    </table>
    {{-- {{$pacakages->links()}} --}}
    <input type="hidden" class="page_no" name="page" value="{{$page_no}}">
</div>


<div class="row">
    <div class="col-md-3">
        Showing {{$pacakages->count()}} from {{ $pacakages->firstItem() ?? 0 }} to {{ $pacakages->lastItem() }} of {{ $pacakages->total() }}  entries 
    </div>
    <div class="col-md-9">
        <div style="float: right">
        {{ $pacakages->links() }}
        </div>
    </div>
</div>
