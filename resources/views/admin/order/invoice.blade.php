@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Order Invoice') }}
                    <a class="add-btn float-right btn-sm mt-3" href="javascript:history.back();"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="javascript:;">{{ __('Orders') }}</a>
                    </li>
                    <li>
                        <a href="javascript:;">{{ __('Invoice') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="order-table-wrap shadow">
        <div class="invoice-wrap">
            <div class="invoice__title">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="invoice__logo text-left">
                           <img src="{{ asset('assets/images/e-basket.png') }}" alt="eBaskat">
                        </div>
                    </div>
                    <div class="col-lg-6 text-right">
                        <a class="btn  add-newProduct-btn print" href="{{route('admin.order.print',$order->id)}}"
                        target="_blank"><i class="fa fa-print"></i> {{ __('Print Invoice') }}</a>
                    </div>
                </div>
            </div>
            <br>
            <div class="row invoice__metaInfo mb-4">
                <div class="col-lg-6">
                    <div class="invoice__orderDetails">

                        <p><strong>{{ __('Order Details') }} </strong></p>
                        <span><strong>{{ __('Invoice Number') }} :</strong> {{ sprintf("%'.08d", $order->id) }}</span><br>
                        <span><strong>{{ __('Order Date') }} :</strong> {{ date('d-M-Y',strtotime($order->created_at)) }}</span><br>
                        <span><strong>{{  __('Order ID')}} :</strong> {{ $order->order_number }}</span><br>
                        @if($order->dp == 0)
                        <span> <strong>{{ __('Shipping Method') }} :</strong>
                            @if($order->shipping == "pickup")
                            {{ __('Pick Up') }}
                            @else
                            {{ __('Ship To Address') }}
                            @endif
                        </span><br>
                        @endif
                        <span> <strong>{{ __('Payment Method') }} :</strong> {{$order->method}}</span>
                    </div>
                </div>
            </div>
            <div class="row invoice__metaInfo">
           @if($order->dp == 0)
                <div class="col-lg-6">
                        <div class="invoice__shipping">
                            <p><strong>{{ __('Shipping Address') }}</strong></p>
                           <span><strong>{{ __('Customer Name') }}</strong>: {{ $order->shipping_name == null ? $order->customer_name : $order->shipping_name}}</span><br>
                           <span><strong>{{ __('Address') }}</strong>: {{ $order->shipping_address == null ? $order->customer_address : $order->shipping_address }}</span><br>
                           <span><strong>{{ __('City') }}</strong>: {{ $order->shipping_city == null ? $order->customer_city : $order->shipping_city }}</span><br>
                           <span><strong>{{ __('Country') }}</strong>: {{ $order->shipping_country == null ? $order->customer_country : $order->shipping_country }}</span>

                        </div>
                </div>

            @endif

                <div class="col-lg-6">
                        <div class="buyer">
                            <p><strong>{{ __('Billing Details') }}</strong></p>
                            <span><strong>{{ __('Customer Name') }}</strong>: {{ $order->customer_name}}</span><br>
                            <span><strong>{{ __('Address') }}</strong>: {{ $order->customer_address }}</span><br>
                            <span><strong>{{ __('City') }}</strong>: {{ $order->customer_city }}</span><br>
                            <span><strong>{{ __('Country') }}</strong>: {{ $order->customer_country }}</span>
                        </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="invoice_table">
                        <div class="mr-table">
                            <div class="table-responsive">
                                <table id="example2" class="table table-hover dt-responsive" cellspacing="0"
                                    width="100%" >
                                    <thead>
                                        <tr>
                                            <th>{{ __('Package Code') }}</th>
                                            <th>{{ __('Product') }}</th>
                                            <th>{{ __('Details') }}</th>
                                            <th>{{ __('Total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $subtotal = 0;
                                        $tax = 0;
                                        @endphp

                                        @foreach($order->merchantPckages as $package)
                                            @foreach($package->orderProducts as $product)
                                            @php
                                                $productName = json_decode($product->cart);
                                                $user = App\Models\User::find($package->merchant_id);
                                            @endphp
                                                <tr>
                                                    <td>{{ $package->order_package_number }}</td>
                                                    <td>{{ $productName->productName }}
                                                    </td>
                                                    <td>
                                                        Qty: {{ $product->product_quantity }} <br>
                                                        @if($productName->productColor) Color: {{ $productName->productColor ?? NULL }} @endif
                                                    </td>
                                                    <td>{{ $subtotals = number_format($product->product_quantity * $productName->price?$productName->price:$product->per_product_price,2, '.', '') }} {{$order->currency_sign}}</td>
                                                </tr>
                                                @php $subtotal += $subtotals; @endphp
                                            @endforeach
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td class="font-weight-bold">{{ __('Subtotal:') }}</td>
                                            <td>{{ number_format($subtotal ,2, '.', '') }} {{$order->currency_sign}}</td>
                                        </tr>
                                        @if($order->shipping_cost != 0)
                                        @php
                                        $price = number_format(($order->shipping_cost / $order->currency_value) ,2, '.', '');
                                        @endphp
                                            @if(DB::table('shippings')->where('price','=',$price)->count() > 0)
                                            <tr>
                                                <td colspan="2"></td>
                                                <td>{{ DB::table('shippings')->where('price','=',$price)->first()->title }}</td>
                                                <td>{{ number_format($order->shipping_cost ,2, '.', '') }} {{$order->currency_sign}}</td>
                                            </tr>
                                            @endif
                                        @endif

                                        @if($order->packing_cost != 0)
                                        @php
                                        $pprice = number_format(($order->packing_cost / $order->currency_value) , 2, '.', '');
                                        @endphp
                                        @if(DB::table('packages')->where('price','=',$pprice)->count() > 0)
                                        <tr>
                                            <td colspan="2"></td>
                                            <td>{{ DB::table('packages')->where('price','=',$pprice)->first()->title }}</td>
                                            <td>{{ number_format($order->packing_cost ,2, '.', '') }} {{$order->currency_sign}}</td>
                                        </tr>
                                        @endif
                                        @endif
                                        @if($order->shipping_cost != 0)
                                            <tr class="font-weight-bold">
                                                <td colspan="2"></td>
                                                <td class="font-weight-bold">{{ __('Total Shipping Cost:') }}</td>
                                                <td>{{number_format($order->shipping_cost ,2, '.', '')}} {{$order->currency_sign}}</td>
                                            </tr>
                                        @endif
                                        @if($order->tax != 0)
                                        <tr>
                                            <td colspan="2"></td>
                                            <td class="font-weight-bold">{{ __('TAX') }}</td>
                                            <td>{{number_format($order->tax ,2, '.', '')}} {{$order->currency_sign}}</td>
                                        </tr>
                                        @endif
                                        @if($order->coupon_discount != null)
                                        <tr>
                                            <td colspan="2"></td>
                                            <td>{{ __('Coupon Discount') }}</td>
                                            <td>€{{number_format($order->coupon_discount ,2, '.', '')}} {{$order->currency_sign}}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td colspan="2"></td>
                                            <td>{{ __('Total') }}</td>
                                            <td>{{ number_format($order->pay_amount * $order->currency_value ,2, '.', '') }} {{$order->currency_sign}}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main Content Area End -->
</div>
</div>
</div>

@endsection
