<div class="table-responsiv">
    <button class="allOrderPlaceToAliexpress btn btn-sm btn-primary" style="display: none;">Place Order To Aliexpress</button>
    <div class="gocover" style="background: url({{asset('assets/images/xloading.gif')}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
    <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>
                    {{-- <input class="check_all_class" type="checkbox" value="all" name="check_all"> --}}
                    #
                </th>
                <th>{{ __('Customer Email') }}</th>
                <th>{{ __('Order Number') }}</th>
                <th>{{ __('Total Qty') }}</th>
                <th>{{ __('Total Cost') }}</th>
                <th>{{ __('Payment Method') }}</th>
                <th>{{ __('Payment Status') }}</th>
                <th>{{ __('Order Status') }}</th>
                <th>{{ __('Options') }}</th>
            </tr>
        </thead>
        <body>
            @php
                $i =1;
            @endphp
            @foreach($orders as $item)   
                <tr>
                    <td>
                        {{ $i }}.
                        {{--  <input class="check_single_class" type="checkbox" name="" name="checked_id[]" value="{{ $item->order_id }}" class="check_single_class" id="{{$item->order_id}}" > --}}
                    </td>
                    <td>
                        {{$item->customer_email}}
                    </td>
                    <td>
                        {{$item->order_number}}
                    </td>
                    <td>
                        {{$item->ebaskatOrderQuantity()}}
                    </td>
                    <td>
                        {{ number_format(($item->ebaskatOrderProductAmount()),2, '.', '')}}
                    </td>
                    <td>
                        {{$item->method}}
                    </td>
                    <td>
                        {{ucfirst($item->payment_status)}}
                    </td>
                    <td>
                        {{ucfirst($item->status)}}
                    </td>
                    <td>
                        <div class="godropdown">
                            <button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button>
                            <div class="action-list">
                                <a href="{{ route('ebaskat.admin.order.show', $item->id )}}" > <i class="fas fa-eye"></i> Details</a>
                                <a href="javascript:;" class="send" data-email=" {{$item->customer_email}} "  data-toggle="modal" data-target="#vendorform">
                                    <i class="fas fa-envelope"></i>
                                    Send
                                </a>
                                <a href="javascript:;" data-href="{{route('ebaskat.admin.order.track', $item->id)}}" class="track" data-toggle="modal" data-target="#modal1">
                                    <i class="fas fa-truck"></i>
                                    Track Order
                                </a>
                             
                                {{-- <a href="javascript:;" data-href="{{route('ebaskat.admin.order.edit', $item->id)}}" class="delivery" data-toggle="modal" data-target="#modal1">
                                    <i class="fas fa-dollar-sign"></i>
                                    Delivery Status
                                </a> --}}
                            </div>
                        </div>
                    </td>
                </tr>
                @php
                    $i++;
                @endphp
            @endforeach
        </body>
    </table>
    {{$orders->links()}}
</div>
