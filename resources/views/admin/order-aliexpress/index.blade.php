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

    <input type="hidden" id="headerdata" value="{{ __('ORDER') }}">

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-5">
                    <h4 class="heading">
                        <span class="status_label">
                        {{ __('All Aliexpress Orders') }}
                        </span>
                    </h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('aliexpress.admin.order.index') }}">{{ __('Ali-x Orders') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('aliexpress.admin.order.index') }}">{{ __('All Ali-x Orders') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-1">
                    <img src="{{ asset('storage/xloading.gif') }}" alt="" class="loading mr-5" style="display: none;">
                </div>
               
                <div class="col-md-6">
                    <div class="btn-group float-right" role="group" aria-label="Order Status">
                        <a data-status="all_orders" id="" class="order_status btn btn-sm btn-primary" name="status" >Orders</a>
                        @foreach (order_packages_status_hh() as  $index => $item)
                        <a data-status="{{$index}}" id="" class="order_status btn btn-sm btn-secondary"  name="status">
                            {{$item}}
                        </a>
                        @endforeach
                        <input type="hidden"  class="selectedStatus"  id="selectedStatus">
                        {{--
                            <a data-status="all_orders" id="" class="order_status btn btn-primary" name="name" >Orders</a> 
                            <a data-status="pending" id="" class="order_status btn btn-secondary"  name="name">Pending</a>
                            <a data-status="processing" id="" class="order_status btn btn-secondary" name="name">Processing</a>
                            <a data-status="completed"  id="" class="order_status btn btn-secondary" name="name">Completed</a>
                            <a data-status="declined"  id="" class="order_status btn btn-secondary" name="name">Declined</a>
                            <input type="hidden"  class="selectedStatus"  id="selectedStatus"> 
                        --}}
                    </div>
                </div>
            </div>
            <div style="text-align: center;margin-bottom:1%;">
                <span class="individually_processing" style="display:none">Processing...</span>
            </div>
            <div class="alertSuccess_Individually_processing" style="display: none;">
                <div class="alert alert-success validation alertSuccess_Individually" style="display: none;">
                    <button type="button" class="close alert-close"><span>×</span></button>
                    {{-- <p class="text-left mb-0"></p> --}}
                    <ul class="text-left-ul alertSuccessMessage_Individually">
                    </ul>
                </div>
            </div>
        </div>

        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">
                        {{-- @include('includes.form-success') --}}
                        <div class="alertSuccessBulk" style="display: none;">
                            <div class="alert alert-success validation" style="display: none;">
                                <button type="button" class="close alert-close"><span>×</span></button>
                                {{-- <p class="text-left mb-0"></p> --}}
                                <ul class="text-left-ul">
                                </ul>
                            </div>
                        </div>

                        <div class="alertDangerBulk" style="display: none;">
                            <div class="alert alert-danger validation" style="display: none;">
                                <button type="button" class="close alert-close"><span>×</span></button>
                                <ul class="text-left-li">
                                </ul>
                            </div>
                        </div>

                        <div class="alertDanger" style="display: none;">
                            <div class="alert alert-danger validation" style="display: none;">
                                <button type="button" class="close alert-close"><span>×</span></button>
                                <p class="text-left mb-0"></p>
                            </div>
                        </div>
                        {{-- @include('includes.form-error') --}}

                        <div class="alertSuccessSingle" style="display: none;">
                            <div class="alert alert-success validation" style="display: none;">
                                <button type="button" class="close alert-close"><span>×</span></button>
                                <p class="text-left-single mb-0"></p>
                            </div>
                        </div>
                        <div class="alertDangerSingle" style="display: none;">
                            <div class="alert alert-danger validation" style="display: none;">
                                <button type="button" class="close alert-close"><span>×</span></button>
                                <p class="text-left-single mb-0"></p>
                            </div>
                        </div>
                        <div class="loading" style="text-align: center;padding-bottom:20px;display:none;">
                            <strong style="color:#57837c;">
                                Processing...
                            </strong>
                        </div>
                        <div class="row" >
                            <div class="col-md-2">
                                <label for="">	&nbsp;</label>
                               <select class="form-control paginate" id="paginate" name="paginate" style="font-size: 12px">
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
                                <label for="">Order Package/Aliexpress Number</label>
                                <input type="text" autocomplete="off" name="custom_search" class="form-control custom_search" placeholder="Order Package / Aliexpress Number" style="font-size: 12px">
                            </div>
                            <div class="col-lg-3">
                                <label for="">Start Date</label>
                                <div class="form-group input-group input-daterange">
                                    <input type="text" name="date" id="toDate" class="form-control end_date datetime" style="font-size: 12px" />
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label for="">
                                    To Date
                                </label>
                                <div class="form-group input-group input-daterange">
                                    <input type="text" name="date" id="formDate" class="form-control start_date datetime"  style="font-size: 12px"/>
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


    
    {{--Single Order place confirmation MODAL --}}
        <div class="modal fade" id="confirmation_place_single_order_modal" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header d-block text-center">
                        <h4 class="modal-title d-inline-block">{{ __('Confirm Place to Order') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <p class="text-center">{{ __('You are about to place the order to aliexpress') }}.</p>
                        <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                        <div class="loading" style="text-align: center;padding-bottom:20px;display:none;">
                            <strong style="color:#57837c;">
                                Processing...
                            </strong>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <input type="hidden" class="singleOrderPlaceRouteWithId">
                        <a class="btn btn-primary single-order-confirm-button">{{ __('Confirm Order') }}</a>
                    </div>

                </div>
            </div>
        </div>
    {{--Single Order place confirmation MODAL ENDS --}}

    {{--bulk Order place confirmation MODAL --}}
    <div class="modal fade" id="confirmation_place_order_modal" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
    
                <div class="modal-header d-block text-center">
                    <h4 class="modal-title d-inline-block">{{ __('Confirm Place to Order') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <!-- Modal body -->
                <div class="modal-body">
                    <p class="text-center">{{ __('You are about to place the order to aliexpress ') }}.</p>
                    <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                </div>
    
                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-primary btn-submit confirm-button">{{ __('Confirm Order') }}</a>
                </div>
    
            </div>
        </div>
    </div>
    {{--bulk Order place confirmation MODAL ENDS --}}


    {{-- Order place confirmation MODAL --}}
    {{-- <div class="modal fade" id="sync_order_status_modal" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
    
                <div class="modal-header d-block text-center">
                    <h4 class="modal-title d-inline-block">{{ __('Confirm Place to Order') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <!-- Modal body -->
                <div class="modal-body">
                    <p class="text-center">{{ __('You are about to Syncing all order status from aliexpress') }}.</p>
                    <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                </div>
    
                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-primary btn-submit confirm-button">{{ __('Confirm Order') }}</a>
                </div>
    
            </div>
        </div>
    </div> --}}
    {{-- Order place confirmation MODAL ENDS --}}


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

    
    {{-- Single Order syncing confirmation MODAL --}}
        <div class="modal fade" id="confirmation_single_order_syncing_modal" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
        
                    <div class="modal-header d-block text-center">
                        <h4 class="modal-title d-inline-block">{{ __('Confirm to Syncing Order') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
        
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="single_syncing_loading" style="text-align: center;padding-bottom:20px;display:none;">
                            <strong style="color:#57837c;">
                                Processing...
                            </strong>
                        </div>
                        <p class="text-center">{{ __('You are about to syncing this order from aliexpress ') }}.</p>
                        <p class="text-center">{{ __('Do you want to proceed?') }}</p>

                        <div class="custom-control custom-switch" style="text-align: center;">
                            <div style="background-color:#101638;color:#fff;padding-bottom: 5px;padding-top: 3px;margin-left: -30px">
                                <input type="checkbox" value="1" class="custom-control-input email_applicable_when_syncing_for_single_order" id="customSwitch1"  style="cursor: pointer;">
                                <label class="custom-control-label" for="customSwitch1" style="cursor: pointer;">Send email to the customer</label>
                            </div>
                        </div>

                    </div>
                    
                    <input type="hidden" value="" class="orderPackageStatusUpdateBySyncingSingleOrderUrl">
                    <input type="hidden" value="" class="orderPackageStatusUpdateBySyncingSingleOrderId">

                    <!-- Modal footer -->
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <a class="btn btn-primary btn-submit confirm_single_order_syning_button">{{ __('Confirm Syncing') }}</a>
                    </div>
                </div>
            </div>
        </div>
    {{--Single Order syncing confirmation MODAL ENDS--}}
    

    {{-- Order bulk syncing confirmation MODAL --}}
        <div class="modal fade" id="confirmation_bulk_syncing_modal" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
        
                    <div class="modal-header d-block text-center">
                        <h4 class="modal-title d-inline-block">{{ __('Confirm to Syncing Order') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
        
                    <!-- Modal body -->
                    <div class="modal-body">

                        <div class="bulk_syncing_loading" style="text-align: center;padding-bottom:20px;display:none;">
                            <strong style="color:#57837c;">
                                Processing...
                            </strong>
                        </div>
                        <p class="text-center">{{ __('You are about to syncing all order from aliexpress ') }}.</p>
                        <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                        <div class="custom-control custom-switch" style="text-align: center;">
                            <div style="background-color:#101638;color:#fff;padding-bottom: 5px;padding-top: 3px;margin-left: -30px">
                                <input type="checkbox" value="1" class="custom-control-input email_applicable_when_syncing_for_bulk_order" id="customSwitch1Bulk"  style="cursor: pointer;">
                                <label class="custom-control-label" for="customSwitch1Bulk" style="cursor: pointer;">Send email to the customer</label>
                            </div>
                        </div>
                    </div>
        
                    <!-- Modal footer -->
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <a class="btn btn-primary btn-submit confirm-bulk-syning-button">{{ __('Confirm Syncing') }}</a>
                    </div>
                    <input type="hidden" value="{{route('aliexpress.admin.order.package.status.update.by.syncing.bulking')}}" class="orderPackageStatusUpdateBySyncingBulking">
                </div>
            </div>
        </div>
    {{--Order bulk syncing confirmation MODAL ENDS--}}


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


    <input type="hidden" value="{{route('aliexpress.admin.order.list.by.ajax')}}" class="orderListByAjax" />
    <input type="hidden" value="{{route('aliexpress.admin.bulk.order.to.aliexpress')}}" class="bulkOrderPlaceToAliexpress" />
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
    <script src="{{asset('custom_js/order/aliexpress-order/aliexpress_order_list.js')}}"></script>
@endsection
