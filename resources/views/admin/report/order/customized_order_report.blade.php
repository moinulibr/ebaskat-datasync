@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{asset('custom_css/select-picker.css')}}">
@endsection

@section('content')

    <input type="hidden" id="headerdata" value="{{ __('Customized Order Report') }}">

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-5">
                    <h4 class="heading">{{ __('Customized Order Report') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.all.report') }}">{{ __('Reports') }}</a>
                        </li>
                        <li>
                            {{ __('Customized Order Report') }}
                        </li>
                    </ul>
                </div>
                <div class="col-md-7 mt-3">
                    <div class="btn-group float-right" role="group" aria-label="Order Status">
                        <a href="{{ route('admin.report.order.customized') }}" class="btn btn-info">Customized Order</a>
                        {{-- <a href="{{ route('admin.report.order.merchant') }}" class="btn btn-secondary">Merchant Order</a>
                        <a href="{{ route('admin.report.order.customer') }}" class="btn btn-secondary">Customer Order</a> --}}
                        <a href="{{ route('admin.report.order.with-chart') }}" class="btn btn-secondary">Order with chart</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <form id="form_csv" action="{{ route('admin.report.order.customized') }}">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Date (from)</label>
                        <input type="text" class="form-control datetime" name="from_date" id="from">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Date (to)</label>
                        <input type="text" class="form-control datetime" name="to_date" id="to">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Order Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="">Select One</option>
                            @foreach (main_orders_status_hh() as $index => $value)
                                <option value="{{$index}}">{{$value}}</option>
                            @endforeach
                            {{-- <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="completed">Completed</option>
                            <option value="declined">Declined</option>
                            <option value="on delivery">On delivery</option> --}}
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Merchant List</label>
                        <select id="merchant_list" class="form-control" name="merchant_id">
                            <option value="">Select Merchant</option>
                            @foreach ($merchant as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Select Customer</label>
                        <select id="user_id" name="customer_id" class="search-select">
                            <option  value="">Select Customer</option>
                            @foreach ($users as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                      <label class="font-weight-bold">Report</label> <br>
                      <button id="filter_btn" class="btn btn-md btn-info">Filter Now</button>
                      <button id="filter_btn_clear" class="btn btn-md btn-danger">Filter Clear</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mr-table allproduct">
                            <div>
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="width: 30%">
                                            <select class="form-control paginate" id="paginate" name="paginate" style="font-size: 12px;width:30%;">
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
                                        </td>
                                        
                                        <td style="width: 40%">
                                            <input type="text" class="search form-control" name="search" autofocus>
                                        </td>
                                        <td class="w-25 text-right">
                                            
                                            <input type="text" id="csv" name="csv" hidden>
                                            <button class="btn btn-secondary btn-sm" id="csv_button"> <i class="fa fa-download"></i> CSV</button>
                                            
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                            <div class="orderListAjaxResult" style="font-family: sans-serif">
                                <div class="table-responsive">
                                    <table id="report_table" class="table table-hover dt-responsive" cellspacing="0"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                {{-- <th>{{ __('Sl') }}</th> --}}
                                                <th>{{ __('Order Date') }}</th>
                                                <th>{{ __('Order Number') }}</th>
                                                <th>{{ __('Customer Name') }}</th>
                                                <th>{{ __('customer Email') }}</th>
                                                <th>{{ __('Method') }}</th>
                                                <th>{{ __('Pay_amount') }}</th>
                                                <th>{{ __('Txnid') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" class="orderListByAjaxResponseUrl" value="{{ route('admin.report.order.ajaxresponse') }}">
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/buttons.html5.min.js') }}"></script>

    <script src="{{asset('custom_js/order/main-order/index.js')}}"></script>
    <script src="{{asset('custom_js/select-picker.js')}}"></script>

    <script type="text/javascript">
        $( document ).ready(function() {
            $('#csv').val(0);
        });
        //pagination,custom search , event
        $(document).on('click','#filter_btn',function(e){
            e.preventDefault();

            var defaultUrl =  $('.orderListByAjaxResponseUrl').val();

            var pagination      = $('#paginate :selected').val();
            var search          = $('.search').val();

            var page_no         = $('.page_no').val();
            var status  = $('#status').val();
            var from_date= $('#from').val();
            var to_date = $('#to').val();
            var merchant_id = $('#merchant_list').val();
            var customer_id = $('#user_id').val();
            $.ajax({
                url: defaultUrl,
                type: "GET",
                datatype:"HTML",
                data:{
                    pagination:pagination,search:search,status:status,page_no:page_no,from_date:from_date,to_date:to_date,merchant_id:merchant_id,customer_id:customer_id
                },
                success: function(response){
                    if(response.status == true)
                    {
                        $('.orderListAjaxResult').html(response.data);
                    }
                },
            });
        });
        //pagination,custom search , event

         //pagination,custom search , event
         $(document).on('click','#filter_btn_clear',function(e){
            e.preventDefault();

            var defaultUrl =  $('.orderListByAjaxResponseUrl').val();

            var pagination      = $('#paginate :selected').val();
            $('.search').val('');

            var page_no         = $('.page_no').val();

            $('.datetime').daterangepicker({
                // timePicker: true,
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 1901,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                autoApply:true
            });

            $('#from').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                startDate: moment().subtract(365, 'days'),
                minYear: 1901,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                autoApply:true
            });

            $('#status').val('');
            // $('#from').val('');
            // $('#to').val('');
            $('#merchant_list').val('');
            $('#user_id').val('');
            $.ajax({
                url: defaultUrl,
                type: "GET",
                datatype:"HTML",
                data:{
                    pagination:pagination,page_no:page_no
                },
                success: function(response){
                    if(response.status == true)
                    {
                        $('.orderListAjaxResult').html(response.data);
                    }
                },
            });
        });
        //pagination,custom search , event
        // csv file download
        $(document).on('click','#csv_button',function(e){
            e.preventDefault();
            $('#csv').val(1);
            // var csv = $('#csv')
            $('#form_csv').submit();
        });
    </script>
@endpush
