@extends('layouts.admin')

@section('styles')
    <style type="text/css">
        .input-field {
            padding: 15px 20px;
        }
        .activeStatus{
            color: #fff;
            background-color: #5a6268;
            border-color: #545b62;s
        }
    </style>
@endsection

@section('content')

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-5">
                    <h4 class="heading">
                        <span class="status_label">
                        {{ __('All Orders') }}
                        </span>
                    </h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.main.order.index') }}">{{ __('All Orders') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-1">
                    <img src="{{ asset('storage/xloading.gif') }}" alt="" class="loading mr-5" style="display: none;">
                </div>
                <div class="col-md-6">
                    <div class="btn-group float-right" role="group" aria-label="Order Status">
                       
                        <a data-status="all_orders" id="none" class="order_status btn {{$currentStatus == 'none' ?'btn-primary':'btn-secondary'}} btn-sm" name="status" >Orders</a>
                        @foreach (main_orders_status_hh() as  $index => $item)
                        <a data-status="{{$index}}" id="{{$index}}" class="order_status btn btn-secondary btn-sm"  name="status">
                            {{$item}}
                        </a>
                        @endforeach
                        <input type="hidden" value="{{$currentStatus}}"  class="selectedStatus"  id="selectedStatus">
                        <input type="hidden" value="{{$currentStatus}}" class="currentStatus currentStatusFromController">
                    </div>
                </div>
            </div>
        </div>
       
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">
                        
                        <div class="row" >
                            <div class="col-md-2">
                                <label for="">	&nbsp;</label>
                               <select class="form-control paginate" id="paginate" style="font-size: 12px"  name="paginate">
                                   <option value="10">10</option>
                                   <option value="20">20</option>
                                   <option value="30">30</option>
                                   <option value="40">40</option>
                                   <option value="50">50</option>
                                   <option value="100">100</option>
                                   <option value="200">200</option>
                                   <option value="300">300</option>
                                   <option value="500">500</option>
                                   <option value="1000">1000</option>
                               </select>
                            </div>
                            <div class="col-md-4">
                                <label for="">&nbsp;</label>
                                <input type="text" autocomplete="off" class="form-control custom_search" placeholder="Search..." style="font-size: 12px"  name="custom_search">
                            </div>
                            <div class="col-lg-3">
                                <label for="">Start Date</label>
                                <div class="form-group input-group input-daterange">
                                    <input type="text" class="form-control end_date datetime" style="font-size: 12px"    name="date" id="toDate"/>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label for="">
                                    To Date
                                </label>
                                <div class="form-group input-group input-daterange">
                                    <input type="text" class="form-control start_date datetime"  style="font-size: 12px"   name="date" id="fromDate"/>
                                </div>
                            </div>
                        </div>
                        
                        <div class="processing_on" style="text-align: center;padding-bottom:20px;display:none;">
                            <strong style="color:#0c0c0c;z-index:99999;background-color:#f9f9f9;padding:3px 5px;border-radious:3px solidg gray;">
                                Processing...
                            </strong>
                        </div>

                        <div class="ajax-response-result"></div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>




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
    
    {{-- Order Trackig details MODAL --}}
        <div class="modal fade" id="trackingDetails" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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
                    <div class="modal-body" style="padding-top: 0px;">
                        <div class="add-product-content1">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="product-description">
                                        <div class="body-area" style="padding: 30px 35px 30px 30px !important;">

                                            <div class="ajax-response-tracking-details-result"></div>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer" style="border-top:none;">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- Order Trackig details MODAL ENDS --}}

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


    <input type="hidden" value="{{route('admin.main.order.list')}}" class="orderListByAjax" />
@endsection

@section('scripts')
<script>
    $('#fromDate').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        startDate: moment().subtract(0, 'days'),
        minYear: 1901,
        locale: {
            format: 'YYYY-MM-DD'
        },
        autoApply:true
    }); 
    $('#toDate').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        startDate: moment().subtract(90, 'days'),
        minYear: 1901,
        locale: {
            format: 'YYYY-MM-DD'
        },
        autoApply:true
    });
</script>
    <script src="{{asset('custom_js/order/main-order/main_order_list.js')}}"></script>
    <script src="{{asset('custom_js/order/main-order/delivery_status.js')}}"></script>
@endsection
