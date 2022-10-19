@extends('layouts.admin')

@section('styles')
    <style type="text/css">
        .input-field {
            padding: 15px 20px;
        }
    </style>
@endsection

@section('content') 

    <input type="hidden" id="headerdata" value="{{ __('ORDER') }}">

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-4">
                    <h4 class="heading">{{ __('All Orders') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.order.index') }}">{{ __('Orders') }}</a>
                        </li>
                        <li>
                            <a href="#">{{ __('All Orders') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-8">
                    <div class="btn-group float-right" role="group" aria-label="Order Status">
                        @php
                        $i = 1;
                        @endphp
                            <span data-href="{{ route('admin.order.datatables','none') }}"  id="{{$i}}" href="#" data-name="all_orders" class="defaultStatus currentStatus btn btn-secondary">All Orders</span>
                        @foreach (main_orders_status_hh() as  $index => $item)
                            @php
                            $i++;
                            @endphp    
                            <span data-href="{{ route('admin.order.datatables',$index) }}" id="{{$i}}" data-id="{{$index}}" href="#" class="currentStatus btn btn-secondary color_{{$index}}">{{$item}}</span>
                        @endforeach
                        <input type="hidden" value="{{$currentStatus}}" data-id="{{$currentStatus}}" class="currentStatus currentStatusFromController" data-href="{{ route('admin.order.datatables',$currentStatus) }}">

                        {{--{{ route('admin.order.datatables','none') }} {{ route('admin.order.'.$index) }}---}}
                        {{-- <a href="{{ route('admin.order.pending') }}" class="btn btn-secondary">Pending</a>
                        <a href="{{ route('admin.order.processing') }}" class="btn btn-secondary">Processing</a>
                        <a href="{{ route('admin.order.completed') }}" class="btn btn-secondary">Completed</a>
                        <a href="{{ route('admin.order.declined') }}" class="btn btn-secondary">Declined</a>
                        <a href="{{ route('admin.order.on_delivery') }}" class="btn btn-secondary">On Delivery</a>
                        <a href="{{ route('admin.order.partial_delivered') }}" class="btn btn-secondary">Partial Delivered</a> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">
                        @include('includes.form-success')
                        <div class="table-responsiv">
                            <div class="gocover"
                                 style="background: url({{asset('assets/images/xloading.gif')}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
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
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ORDER MODAL --}}
    <div class="modal fade" id="confirm-delete1" tabindex="-1" role="dialog" aria-labelledby="modal1"
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
                    <p class="text-center">{{ __("You are about to update the order's Status.") }}</p>
                    <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                    <input type="hidden" id="t-add" value="{{ route('admin.order.track.add') }}">
                    <input type="hidden" id="t-id" value="">
                    <input type="hidden" id="t-title" value="">
                    <textarea class="input-field" placeholder="Enter Your Tracking Note (Optional)"
                              id="t-txt"></textarea>
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

    {{-- MESSAGE MODAL --}}
    <div class="sub-categori">
        <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vendorformLabel">{{ __('Send Email') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid p-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="contact-form">
                                        <form id="emailreply">
                                            {{csrf_field()}}
                                            <ul>
                                                <li>
                                                    <input type="email" class="input-field eml-val" id="eml" name="to"
                                                           placeholder="{{ __('Email') }} *" value="" required="">
                                                </li>
                                                <li>
                                                    <input type="text" class="input-field" id="subj" name="subject"
                                                           placeholder="{{ __('Subject') }} *" required="">
                                                </li>
                                                <li>
                                                    <textarea class="input-field textarea" name="message" id="msg"
                                                              placeholder="{{ __('Your Message') }} *"
                                                              required=""></textarea>
                                                </li>
                                            </ul>
                                            <button class="submit-btn" id="emlsub"
                                                    type="submit">{{ __('Send Email') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- MESSAGE MODAL ENDS --}}

    {{-- ADD / EDIT MODAL --}}
    <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="submit-loader">
                    <img src="{{asset('assets/images/xloading.gif')}}" alt="">
                </div>
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- ADD / EDIT MODAL ENDS --}}

    
    {{-- delivery status MODAL --}}
        <div class="modal fade" id="deliveryStatus" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="submit-loader">
                        <img src="{{asset('assets/images/xloading.gif')}}" alt="">
                    </div>
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close closeDeliveryModel" data-dismiss="modal" aria-label="Close">
                            <span class="closeDeliveryModel" aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="padding-top: 0px;">
                        <div class="add-product-content1">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="product-description">
                                        <div class="body-area" style="padding: 30px 35px 30px 30px !important;">

                                            <div class="ajax-response-delivery-status-result"></div>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer" style="border-top:none;">
                            <button type="button" class="btn btn-secondary closeDeliveryModel" data-dismiss="modal">{{ __('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- delivery status MODAL ENDS --}}


@endsection

@section('scripts')
    <script src="{{asset('custom_js/order/main-order/delivery_status.js')}}"></script>

    <script type="text/javascript">

        $(document).ready(function(){
            $('.currentStatus').css({
                'background-color':'#6c757d'
            });
            var statusFromController = $('.currentStatusFromController').val();
            var url = "";
            if(statusFromController == 'none')
            {
                $('#1').css({
                    'background-color':'green'
                });
                url =  $('.defaultStatus').data('href');
            }else{
                url     = $('.currentStatusFromController').data('href');
                $('.color_'+statusFromController).css({
                    'background-color':'green'
                });
            }
            $("#geniustable").dataTable().fnDestroy();
            dataTableLoading(url);
        });

        $(document).on('click','.currentStatus',function(){
            var url =  $(this).data('href');
            $('.currentStatus').css({
                'background-color':'#6c757d'
            });
            var currentId = $(this).attr('id');
            $('#'+currentId).css({
                'background-color':'green'
            });
            $("#geniustable").dataTable().fnDestroy();
            dataTableLoading(url);
        });

        function dataTableLoading(url)
        {
            var table = $('#geniustable').DataTable({
                ordering: true,
                processing: true,
                serverSide: true,
                //ajax: '{{ route('admin.order.datatables','none') }}',
                ajax: url,
                columns: [
                    {data: 'customer_email', name: 'customer_email'},
                    {data: 'id', name: 'id'},
                    {data: 'totalQty', name: 'totalQty'},
                    {data: 'pay_amount', name: 'pay_amount'}, 
                    {data: 'method', name: 'method'},
                    {data: 'payment_status', name: 'payment_status'},
                    {data: 'status', name: 'status'},
                    {data: 'action', searchable: false, orderable: false}
                ],
                language: {
                    processing: '<img src="{{asset('assets/images/xloading.gif')}}">'
                },
                drawCallback: function (settings) {
                    $('.select').niceSelect();
                }
            });
        }
    </script>

    {{-- DATA TABLE --}}

@endsection
