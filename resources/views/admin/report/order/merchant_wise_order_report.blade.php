@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')

    <input type="hidden" id="headerdata" value="{{ __('Merchant Wise Order Report') }}">

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-5">
                    <h4 class="heading">{{ __('Merchant Wise Order Report') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.all.report') }}">{{ __('Reports') }}</a>
                        </li>
                        <li>
                            {{ __('Merchant Wise Order Report') }}
                        </li>
                    </ul>
                </div>
                <div class="col-md-7 mt-3">
                    <div class="btn-group float-right" role="group" aria-label="Order Status">
                        <a href="{{ route('admin.report.order.customized') }}" class="btn btn-secondary">Customized Order</a>
                        <a href="{{ route('admin.report.order.merchant') }}" class="btn btn-info">Merchant Order</a>
                        <a href="{{ route('admin.report.order.customer') }}" class="btn btn-secondary">Customer Order</a>
                        <a href="{{ route('admin.report.order.with-chart') }}" class="btn btn-secondary">Order with chart</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Date (from)</label>
                        <input type="text" class="form-control datetime" id="from">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Date (to)</label>
                        <input type="text" class="form-control datetime" id="to">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Order Status</label>
                        <select id="status">
                            <option value="">Select One</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="completed">Completed</option>
                            <option value="declined">Declined</option>
                            <option value="on delivery">On delivery</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Merchant List</label>
                        <select id="merchant_list" class="form-control">
                            <option value="">Type Product</option>
                            @foreach ($merchant as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                      <label class="font-weight-bold">Report</label> <br>
                      <button id="filter_btn" class="btn btn-md btn-info">Filter Now</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mr-table allproduct">
                            <div class="table-responsive">
                                <table id="report_table" class="table table-hover dt-responsive" cellspacing="0"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Sl') }}</th>
                                            <th>{{ __('Order Date') }}</th>
                                            <th>{{ __('Order Number') }}</th>
                                            <th>{{ __('Merchant Name') }}</th>
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

@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/buttons.html5.min.js') }}"></script>
    <script type="text/javascript">

        let table = $('#report_table').DataTable({
            dom: 'lBfrtip',
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.report.order.merchant') }}",
                data: function(data){
                    data.from_date = $('#from').val();
                    data.to_date = $('#to').val();
                    data.status = $('#status :selected').val();
                    data.merchant_id = $('#merchant_list :selected').val();
                    return data;
                }
            },
            columns: [
                {
                    data: null,
                    render: function(data, type, row) {
                        return row.DT_RowIndex;
                    }
                },
                {
                    data: 'order_date'
                },
                {
                    data: 'order_number'
                },
                {
                    data: 'name'
                },
                {
                    data: 'method'
                },
                {
                    data: 'pay_amount'
                },
                {
                    data: 'txnid'
                },
                {
                    data: 'status',
                    render: function(data){
                        return data[0].toUpperCase() + data.slice(1);
                    }
                },
            ],
            buttons: [{
                extend: 'csvHtml5',
                text: '<i class="fa fa-file-text-o"></i> CSV',
                titleAttr: 'CSV',
                title: 'Order Report',
            }],
            language: {
                processing: `<img src="{{ asset('assets/images/xloading.gif') }}">`
            },
            lengthMenu: [
                [10, 25, 100, -1],
                [10, 25, 100, "All"]
            ]
        });

        $('#filter_btn').click(function(){
            table.draw();
        });
    </script>
@endpush
