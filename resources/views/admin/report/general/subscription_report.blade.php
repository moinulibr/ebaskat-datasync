@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')

    <input type="hidden" id="headerdata" value="{{ __('Subscription Report') }}">

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-6">
                    <h4 class="heading">{{ __('Subscription Report') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.all.report') }}">{{ __('Reports') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.all.report') }}">{{ __('General') }}</a>
                        </li>
                        <li>
                            {{ __('Subscription Report') }}
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="btn-group float-right" role="group" aria-label="Order Status">
                        <a href="{{ route('admin.report.generel.customer') }}" class="btn btn-secondary">Customer</a>
                        {{-- <a href="{{ route('admin.report.generel.product') }}" class="btn btn-secondary">Product</a>
                        <a href="{{ route('admin.report.generel.merchant') }}" class="btn btn-secondary">Merchant</a> --}}
                        <a href="{{ route('admin.report.generel.subscription') }}" class="btn btn-info">Subscription</a>
                        <a href="{{ route('admin.report.generel.coupon') }}" class="btn btn-secondary">Coupon</a>
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
                        <label class="font-weight-bold">Select Plan</label>
                        <select id="title">
                            <option value="">Select One</option>
                            @foreach ($title as $item)
                                <option value="{{$item}}">{{$item}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Select Currency</label>
                        <select id="currency"  class="form-control">
                            <option value="">Select One</option>
                            @foreach ($currency_code as $item)
                                <option value="{{$item}}">{{$item}}</option>
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
                                            <th>{{ __('User Id') }}</th>
                                            <th>{{ __('Title') }}</th>
                                            <th>{{ __('Currency Code') }}</th>
                                            <th>{{ __('Price') }}</th>
                                            <th>{{ __('Days') }}</th>
                                            <th>{{ __('Method') }}</th>
                                            <th>{{ __('Txn Id') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Date') }}</th>
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
        var table = $('#report_table').DataTable({
            dom: 'lBfrtip',
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.report.generel.subscription') }}",
                data: function(data){
                    data.title = $('#title :selected').val();
                    data.currency = $('#currency :selected').val();
                    data.from_date = $('#from').val();
                    data.to_date = $('#to').val();
                    return data;
                }
            },
            columns: [
                {
                    data: 'name',
                    render: function(data,type,row){
                        return row.DT_RowIndex;
                    }
                },
                {
                    data: 'name'
                },
                {
                    data: 'title'
                },
                {
                    data: 'currency_code'
                },
                {
                    data: 'price'
                },
                {
                    data: 'days'
                },
                {
                    data: 'method'
                },
                {
                    data: 'txnid'
                },
                {
                    data: 'status'
                },
                {
                    data: 'date'
                }
            ],
            buttons: [{
                extend: 'csvHtml5',
                text: '<i class="icofont-upload-alt"></i> CSV',
                titleAttr: 'CSV',
                title: 'Subscription Report',
            }],
            language: {
                processing: `<img src="{{ asset('assets/images/xloading.gif') }}">`
            },
            lengthMenu: [
                [10, 25, 100, -1],
                [10, 25, 100, "All"]
            ]
        });

        // filter button
        $('#filter_btn').click(function() {
            table.draw();
        });

    </script>
@endpush
