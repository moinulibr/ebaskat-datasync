@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')

    <input type="hidden" id="headerdata" value="{{ __('General Report') }}">

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-6">
                    <h4 class="heading">{{ __('Coupon Report') }}</h4>
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
                            {{ __('Coupon Report') }}
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="btn-group float-right" role="group" aria-label="Order Status">
                        <a href="{{ route('admin.report.generel.customer') }}" class="btn btn-secondary">Customer</a>
                            {{-- <a href="{{ route('admin.report.generel.product') }}" class="btn btn-secondary">Product</a>
                            <a href="{{ route('admin.report.generel.merchant') }}" class="btn btn-secondary">Merchant</a> --}}
                        <a href="{{ route('admin.report.generel.subscription') }}" class="btn btn-secondary">Subscription</a>
                        <a href="{{ route('admin.report.generel.coupon') }}" class="btn btn-info">Coupon</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Start Date (from)</label>
                        <input type="text" class="form-control datetime" id="start_from">
                    </div>
                    <!-- <div class="form-group col-md-3">
                        <label class="font-weight-bold">Start Date (to)</label>
                        <input type="text" class="form-control datetime" id="start_to">
                    </div> -->
                    <!-- <div class="form-group col-md-3">
                        <label class="font-weight-bold">End Date (from)</label>
                        <input type="text" class="form-control datetime" id="end_from">
                    </div> -->
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">End Date (to)</label>
                        <input type="text" class="form-control datetime" id="end_from">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Account Type</label>
                        <select id="status" class="form-control">
                            <option value=""> Select One</option>
                            <option value="1">Active</option>
                            <option value="0">Deactive</option>
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
                                            <th>{{ __('Code') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th>{{ __('Price') }}</th>
                                            <th>{{ __('Used') }}</th>
                                            <th>{{ __('Min Order') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Start Date') }}</th>
                                            <th>{{ __('End Date') }}</th>
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
                url: "{{ route('admin.report.generel.coupon') }}",
                data: function(data){
                    data.start_from = $('#start_from').val();
                    data.start_to = $('#start_to').val();
                    
                    data.end_from = $('#end_from').val();
                    data.end_to = $('#end_to').val();
                    
                    data.status = $('#status :selected').val();
                    return data;
                }
            },
            columns: [
                {
                    data: null,
                    render: function(data,type,row){
                        return row.DT_RowIndex;
                    }
                },
                {
                    data: 'code'
                },
                {
                    data: 'type',
                    render: function(data){
                        return data ? "Persentage": "Amount";
                    }
                },
                {
                    data: 'price'
                },
                {
                    data: 'used'
                },
                {
                    data: 'min_order_value',
                    render: function(data){
                        if(! data)
                        {
                            return 'N/A';
                        }
                        return data;
                    }
                },
                {
                    data: 'status',
                    render: function(data){
                        return data? "Active": "Deactive";
                    }
                },
                {
                    data: 'start_date'
                },
                {
                    data: 'end_date'
                }
            ],
            buttons: [{
                extend: 'csvHtml5',
                text: '<i class="icofont-upload-alt"></i> CSV',
                titleAttr: 'CSV',
                title: 'Coupon Report',
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
