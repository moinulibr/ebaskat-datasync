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
                    <div class="col-lg-12 order-details-table">
                        <div class="mr-table">
                            <h4 class="title">{{ __('Main Order NO : ') }}{{$package->order->order_number}}</h4>
                            <h4 class="title">{{ __('Order Package ID : ') }}{{$package->order_package_number}}</h4>
                            
                            <br/>
                            <div class="alertSuccessSingleOrder" style="display: none;">
                                <div class="alert alert-success alert-success-single-order validation" style="display: none;">
                                    <button type="button" class="close alert-close"><span>×</span></button>
                                    <p class="text-left-single-order mb-0"></p>
                                </div>
                            </div>
                            <div class="alertDangerSingleOrder" style="display: none;">
                                <div class="alert alert-danger alert-danger-single-order validation" style="display: none;">
                                    <button type="button" class="close alert-close"><span>×</span></button>
                                    <p class="text-left-single-order mb-0"></p>
                                </div>
                            </div>    
                            <br/>
                                <div class="single_processing_on" style="text-align: center;padding-bottom:20px;display:none;">
                                    <strong style="color:#0c0c0c;z-index:99999;background-color:#f9f9f9;padding:3px 5px;border-radious:3px solidg gray;">
                                        Processing...
                                    </strong>
                                </div>
                            <br/>    

                            <div class="order_products_details_data">
                                @include('admin.order-bigbuy.single-order-place.display_product')
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