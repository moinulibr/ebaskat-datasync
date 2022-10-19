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
                        {{ __('Ebaskat Orders') }}
                        </span>
                    </h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.order.index') }}">{{ __('All Orders') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('ebaskat.admin.order.index') }}">
                                    {{ __('All Ebaskat Orders') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-7">
                    <div class="btn-group float-right" role="group" aria-label="Order Status">
                        {{-- 
                            <a data-status="all_orders" id="" class="order_status btn btn-primary" name="name" >All Orders</a>
                            <a data-status="pending" id="" class="order_status btn btn-secondary"  name="name">Pending</a>
                            <a data-status="processing" id="" class="order_status btn btn-secondary" name="name">Processing</a>
                            <a data-status="completed"  id="" class="order_status btn btn-secondary" name="name">Completed</a>
                            <a data-status="declined"  id="" class="order_status btn btn-secondary" name="name">Declined</a>
                            <input type="hidden"  class="selectedStatus"  id="selectedStatus">
                        --}}
                         <a data-status="all_orders" id="" class="order_status btn btn-primary" name="name" >Orders</a>
                        @foreach (order_packages_status_hh() as  $index => $item)
                        <a data-status="{{$index}}" id="" class="order_status btn btn-secondary"  name="name">
                            {{$item}}
                        </a>
                        @endforeach
                        <input type="hidden"  class="selectedStatus"  id="selectedStatus">
                    </div>
                </div>
            </div>
        </div>

        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">
                        @include('includes.form-success')
                        @include('includes.form-error')
                        <div class="loading" style="text-align: center;padding-bottom:20px;display:none;">
                            <strong style="color:#57837c;">
                                Processing...
                            </strong>
                        </div>
                        <div class="row" >
                            <div class="col-md-2">
                                <label for="">	&nbsp;</label>
                               <select class="form-control paginate" id="paginate" style="font-size: 12px">
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
                                <label for="">Order Package</label>
                                <input type="text" class="form-control custom_search" placeholder="Order Package" style="font-size: 12px">
                            </div>
                            <div class="col-lg-3">
                                <label for="">Start Date</label>
                                <div class="form-group input-group input-daterange">
                                    <input type="date" class="form-control end_date" style="font-size: 12px" />
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label for="">
                                    To Date
                                </label>
                                <div class="form-group input-group input-daterange">
                                    <input type="date" class="form-control start_date"  style="font-size: 12px"/>
                                </div>
                            </div>
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


    <input type="hidden" value="{{route('ebaskat.admin.order.list.by.ajax')}}" class="orderListByAjax" />
@endsection

@section('scripts')
    <script src="{{asset('custom_js/order/ebaskat-order/ebaskat_order_list.js')}}"></script>
@endsection
