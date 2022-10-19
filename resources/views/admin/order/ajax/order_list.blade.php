<div class="table-responsiv">
    {{-- <button class="allOrderPlace btn btn-sm btn-primary" style="display: none;">another</button>
    <button class="allOrderSyncStatus btn btn-sm btn-success" style="display: none;">Sync Order Status </button>
    <div class="gocover" style="background: url({{asset('assets/images/xloading.gif')}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div> --}}
    <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                {{-- <th>
                    <input class="check_all_class" type="checkbox" value="all" name="check_all">
                </th> --}}
                <th>{{ __('Customer Email') }}</th>
                <th>{{ __('Order Number') }}</th>
                <th>{{ __('Order Date') }}</th>
                <th>{{ __('Total Qty') }}</th>
                <th>{{ __('Total Cost') }}</th>
                <th>{{ __('Payment Status') }}</th>
                <th>{{ __('Order Status') }}</th>
                <th>{{ __('Options') }}</th>
            </tr>
        </thead>
        <body>
            @foreach($orders as $order)    
                <tr>
                   {{--  <td>
                        <input class="check_single_class" type="checkbox"  name="checked_id[]" value="{{ $order->order_id }}" class="check_single_class" id="{{$order->order_id}}"   data-aliex_order_id="{{$order->alix_order_id}}">
                    </td> --}}
                    <td>{{$order->customer_email}}</td>
                    <td>{{$order->order_number}}</td>
                    <td>{{ date('Y-m-d',strtotime($order->created_at)) }}</td>
                    <td>{{$order->totalQty}}</td>
                    <td>{{ number_format($order->pay_amount,2, '.', '') }}</td>
                    <td>
                        @if ($order->payment_status == 'success')
                            <span class="badge badge-success">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                            @else
                            <span class="badge badge-warning">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        @endif
                        <span class="badge badge-secondary">
                            {{ ucfirst($order->method) }}
                        </span>
                    </td>
                    <td>
                        <strong class="badge badge-{{main_orders_status_label_and_class_hh($order->status)['class']}}">
                            {{main_orders_status_label_and_class_hh($order->status)['label']}}
                        </strong>
                    </td>
                    <td>
                        <div class="godropdown">
                            <button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button>
                            <div class="action-list">
                                <a href="{{route('admin.order.show', $order->id)}}" > <i class="fas fa-eye"></i> Details</a>
                                <a href="javascript:;" data-href="{{route('admin.main.order.show.delivery.status.details')}}" class="delivery_status" data-id="{{$order->id}}">
                                    <i class="fas fa-dollar-sign"></i> Delivery Status
                                </a>
                                <a href="javascript:;" class="send" data-email="{{$order->customer_email}}" data-toggle="modal" data-target="#vendorform">
                                    <i class="fas fa-envelope"></i> Send
                                </a>
                                <a href="javascript:;" data-href="{{route('admin.order.track', $order->id)}}" class="trackingDetails">
                                    <i class="fas fa-truck"></i> Track Order
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </body>
    </table>
    {{-- {{$orders->links()}} --}}
    <input type="hidden" class="page_no" name="page" value="{{$page_no}}">
</div>

<div class="row">
    <div class="col-md-3">
        Showing {{$orders->count()}} from {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() }} of {{ $orders->total() }}  entries 
    </div>
    <div class="col-md-9">
        <div style="float: right">
            {{ $orders->links() }}
        </div>
    </div>
</div>