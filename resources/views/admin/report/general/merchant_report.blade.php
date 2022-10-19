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
                    <h4 class="heading">{{ __('Merchant Report') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.all.report') }}">{{ __('Reports') }}</a>
                        </li>
                        <li>
                            {{ __('Merchant Report') }}
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="btn-group float-right" role="group" aria-label="Order Status">
                        <a href="{{ route('admin.report.generel.customer') }}" class="btn btn-secondary">Customer</a>
                        <a href="{{ route('admin.report.generel.product') }}" class="btn btn-secondary">Product</a>
                        <a href="{{ route('admin.report.generel.merchant') }}" class="btn btn-info">Merchant</a>
                        <a href="{{ route('admin.report.generel.subscription') }}" class="btn btn-secondary">Subscription</a>
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
                        <label class="font-weight-bold">Country</label>
                        <input type="text" id="country" class="form-control">
                        </div>
                        <div class="form-group col-md-3">
                        <label class="font-weight-bold">Email</label>
                        <input type="text" id="email" class="form-control">
                        </div>
                        <div class="form-group col-md-3">
                        <label class="font-weight-bold">Phone</label>
                        <input type="text" id="phone" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Account Type</label>
                        <select id="ban" class="form-control">
                            <option value="">Select One</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
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
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th>{{ __('Phone') }}</th>
                                            <th>{{ __('City') }}</th>
                                            <th>{{ __('Country') }}</th>
                                            <th>{{ __('Affilate Income') }}</th>
                                            <th>{{ __('Balance') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Is Ban') }}</th>
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
                url: "{{ route('admin.report.generel.merchant') }}",
                data: function(data){
                    data.from_date = $('#from').val();
                    data.to_date = $('#to').val();
                    data.country = $('#country').val() || null;
                    data.email = $('#email').val() || null;
                    data.phone = $('#phone').val() || null;
                    data.ban = $('#ban :selected').val();
                    return data;

                }
            },
            columns: [{
                    data: 'name'
                },
                {
                    data: 'email'
                },
                {
                    data: 'phone' 
                },
                {
                    data: 'city'
                },
                {
                    data: 'country'
                },
                {
                    data: 'affilate_income'
                },
                {
                    data: 'current_balance'
                },
                {
                    data: 'date'
                },
                {
                    data: 'ban',
                    render: function(data){
                        return data ? 'Yes' : "No";
                    }
                },
            ],
            buttons: [{
                extend: 'csvHtml5',
                text: '<i class="icofont-upload-alt"></i> CSV',
                titleAttr: 'CSV',
                title: 'Merchant Report',
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
