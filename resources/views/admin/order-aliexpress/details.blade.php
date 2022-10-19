@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Aliexpress Order Details') }}
                        <a class="add-btn float-right btn-sm mt-3" href="javascript:history.back();"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:">{{ __('Orders') }}</a>
                        </li>
                        <li>
                            <a href="javascript:">{{ __('Order Details') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="order-table-wrap shadow">
            @include('includes.form-both')
            <div class="row">

                <div class="col-lg-6">
                    <div class="special-box">
                        <div class="heading-area">
                            <h4 class="title">
                                {{ __('Order Details') }}
                            </h4>
                        </div>
                        <div class="table-responsive-sm">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="45%" width="45%">{{ __('Order ID') }}</th>
                                        <td width="10%">:</td>
                                        <td class="45%" width="45%">{{ $package->order ? $package->order->order_number :NULL }}</td>
                                    </tr>
                                    <tr>
                                        <th width="45%">{{ __('Total Product') }}</th>
                                        <td width="10%">:</td>
                                        <td width="45%">{{$package->orderProducts ? $package->orderProducts->sum('product_quantity') : 0}}</td>
                                    </tr>
                                    <tr>
                                        <th width="45%">{{ __('Total Cost') }}</th>
                                        <td width="10%">:</td>
                                        <td width="45%">€{{ number_format($package->totalProductPrice($package->orderProducts),2, '.', '') }}</td>
                                    </tr>
                                    <tr>
                                        <th width="45%">{{ __('Ordered Date') }}</th>
                                        <td width="10%">:</td>
                                        <td width="45%">{{  date('d-M-Y H:i:s a',strtotime( $package->order ? $package->order->created_at :NULL ))}}</td>
                                    </tr>
                                    <tr>
                                        <th width="45%">{{ __('Payment Method') }}</th>
                                        <td width="10%">:</td>
                                        <td width="45%">{{ $package->order ? $package->order->method :NULL }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <th width="45%">{{ __('Payment Status') }}</th>
                                        <th width="10%">:</th>
                                        <td width="45%">{!! ($package->order ? $package->order->payment_status : NULL) == 'Pending' ? "<span class='badge badge-danger'>Unpaid</span>":"<span class='badge badge-success'>Paid</span>" !!}</td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                       {{--  <div class="footer-area">
                            <a href="{{ route('admin.order.invoice',$package->id) }}" class="mybtn1"><i class="fas fa-eye"></i> {{ __('View Invoice') }}</a>
                        </div> --}}
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="special-box">
                        <div class="heading-area">
                            <h4 class="title">
                                {{ __('Billing Details') }}
                            </h4>
                        </div>
                        <div class="table-responsive-sm">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th width="45%">{{ __('Name') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$package->order ? $package->order->customer_name : NULL}}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ __('Email') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$package->order ? $package->order->customer_email : NULL}}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ __('Phone') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$package->order ? $package->order->customer_phone : NULL}}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ __('Address') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$package->order ? $package->order->customer_address : NULL}}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ __('Country') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$package->order ? $package->order->customer_country : NULL}}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ __('City') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$package->order ? $package->order->customer_city : NULL}}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ __('Postal Code') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$package->order ? $package->order->customer_zip : NULL}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if( ($package->order?$package->order->dp : NULL) == 0)
                    <div class="col-lg-6">
                        <div class="special-box">
                            <div class="heading-area">
                                <h4 class="title">
                                    {{ __('Shipping Details') }}
                                </h4>
                            </div>
                            <div class="table-responsive-sm">
                                <table class="table">
                                    <tbody>
                                    @if( ($package->order ? $package->order->shipping :NULL) == "pickup")
                                        <tr>
                                            <th width="45%"><strong>{{ __('Pickup Location') }}:</strong></th>
                                            <th width="10%">:</th>
                                            <td width="45%">{{ $package->order ? $package->order->pickup_location :NULL}}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <th width="45%"><strong>{{ __('Name') }}:</strong></th>
                                            <th width="10%">:</th>
                                            <td>{{ $package->order ? ( $package->order->shipping_name ? $package->order->shipping_name : ($package->order ? $package->order->customer_name : NULL ) ) : NULL }}</td>
                                        </tr>
                                        <tr>
                                            <th width="45%"><strong>{{ __('Email') }}:</strong></th>
                                            <th width="10%">:</th>
                                            <td width="45%">
                                                {{ $package->order ? ( $package->order->shipping_email ? $package->order->shipping_email : ($package->order ? $package->order->customer_email : NULL ) ) : NULL }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="45%"><strong>{{ __('Phone') }}:</strong></th>
                                            <th width="10%">:</th>
                                            <td width="45%">
                                                {{ $package->order ? ( $package->order->shipping_phone ? $package->order->shipping_phone : ($package->order ? $package->order->customer_phone : NULL ) ) : NULL }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="45%"><strong>{{ __('Address') }}:</strong></th>
                                            <th width="10%">:</th>
                                            <td width="45%">
                                                {{ $package->order ? ( $package->order->shipping_address ? $package->order->shipping_address : ($package->order ? $package->order->customer_address : NULL ) ) : NULL }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="45%"><strong>{{ __('Country') }}:</strong></th>
                                            <th width="10%">:</th>
                                            <td width="45%">
                                                {{ $package->order ? ( $package->order->shipping_country ? $package->order->shipping_country : ($package->order ? $package->order->customer_country : NULL ) ) : NULL }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="45%"><strong>{{ __('City') }}:</strong></th>
                                            <th width="10%">:</th>
                                            <td width="45%">
                                                {{ $package->order ? ( $package->order->shipping_city ? $package->order->shipping_city : ($package->order ? $package->order->customer_city : NULL ) ) : NULL }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="45%"><strong>{{ __('Postal Code') }}:</strong></th>
                                            <th width="10%">:</th>
                                            <td width="45%">
                                                {{ $package->order ? ( $package->order->shipping_zip ? $package->order->shipping_zip : ($package->order ? $package->order->customer_zip : NULL ) ) : NULL }}
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>


            <div class="row">
                <div class="col-lg-12 order-details-table">
                    <div class="mr-table">
                        <h4 class="title">{{ __('Products Ordered') }}</h4>
                        <div class="table-responsiv">
                            <table id="example2" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <tr>
                                        <th width="10%">{{ __('Package ID#') }}</th>
                                        <th>{{ __('Product Title') }}</th>
                                        <th width="20%">{{ __('Qty') }}</th>
                                        <th width="10%">{{ __('Total Price') }}</th>
                                    </tr>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($package->orderProducts as $product)
                                        <tr>
                                            <td>{{$package->order_package_number}}</td>
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
                                                      Size :  {{ $carts['productSize'] }} 
                                                    @endif
                                                    @if (array_key_exists('productColor',$carts))
                                                    ,   Color :  {{ $carts['productColor']}}
                                                    @endif
                                                </small>
                                            </td>
                                            <td>{{ $product->product_quantity }}</td>
                                            <td>€{{ number_format(($product->product_quantity * $product->per_product_price),2, '.', '') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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


    {{-- ORDER MODAL --}}
    <div class="modal fade" id="confirm-delete2" tabindex="-1" role="dialog" aria-labelledby="modal1"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="submit-loader">
                    <img src="{{asset('assets/images/xloading.gif')}}" alt="">
                </div>
                <div class="modal-header d-block text-center">
                    <h4 class="modal-title d-inline-block">{{ __('Update Status') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <p class="text-center">{{ __("You are about to update the order's status.") }}</p>
                    <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-success btn-ok order-btn">{{ __('Proceed') }}</a>
                </div>

            </div>
        </div>
    </div>
    {{-- ORDER MODAL ENDS --}}


@endsection


@section('scripts')

    <script type="text/javascript">
        $('#example2').dataTable({
            "ordering": false,
            'lengthChange': false,
            'searching': false,
            'ordering': false,
            'info': false,
            'autoWidth': false,
            'responsive': true
        });
    </script>

    <script type="text/javascript">
        $(document).on('click', '#license', function (e) {
            var id = $(this).parent().find('input[type=hidden]').val();
            var key = $(this).parent().parent().find('input[type=hidden]').val();
            $('#key').html(id);
            $('#license-key').val(key);
        });
        $(document).on('click', '#license-edit', function (e) {
            $(this).hide();
            $('#edit-license').show();
            $('#license-cancel').show();
        });
        $(document).on('click', '#license-cancel', function (e) {
            $(this).hide();
            $('#edit-license').hide();
            $('#license-edit').show();
        });

        $(document).on('submit', '#edit-license', function (e) {
            e.preventDefault();
            $('button#license-btn').prop('disabled', true);
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if ((data.errors)) {
                        for (var error in data.errors) {
                            $.notify('<li>' + data.errors[error] + '</li>', 'error');
                        }
                    } else {
                        $.notify(data, 'success');
                        $('button#license-btn').prop('disabled', false);
                        $('#confirm-delete').modal('toggle');

                    }
                }
            });
        });
    </script>

@endsection
