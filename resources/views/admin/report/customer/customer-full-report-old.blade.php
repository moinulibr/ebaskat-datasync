@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')

    <input type="hidden" id="headerdata" value="{{ __('Customer Full Report') }}">

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="heading">{{ __('Customer Full Report') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.all.report') }}">{{ __('Reports') }}</a>
                        </li>
                        <li>
                            {{ __('Customer Full Report') }}
                        </li>
                    </ul>
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
                    {{-- <div class="form-group col-md-3">
                        <label class="font-weight-bold">Country</label>
                        <input type="text" id="country" class="form-control">
                      </div>
                      <div class="form-group col-md-3">
                        <label class="font-weight-bold">Email</label>
                        <input type="email" id="email" class="form-control">
                      </div>
                      <div class="form-group col-md-3">
                        <label class="font-weight-bold">Phone</label>
                        <input type="number" id="phone" class="form-control">
                    </div> --}}
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
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th>{{ __('Total Order') }}</th>
                                            <th>{{ __('Pending') }}</th>
                                            <th>{{ __('Complete') }}</th>
                                            <th>{{ __('Decline') }}</th>
                                            <th>{{ __('Processing') }}</th>
                                            <th>{{ __('On Delivered') }}</th>
                                            <th>{{ __('Partial Delivered') }}</th>
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
            ordering: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.report.customer.full_report') }}",
                data: function(data){
                    data.from_date = $('#from').val() || null;
                    data.to_date = $('#to').val() || null;
                    data.country = $('#country').val() || null;
                    data.email = $('#email').val() || null;
                    data.phone = $('#phone').val() || null;
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
                    data: 'customer_name'
                },
                {
                    data: 'customer_email'
                },
                {
                    data: 'total'
                },
                {
                    data: 'pending'
                },
                {
                    data: 'processing'
                },
                {
                    data: 'declined'
                },
                {
                    data: 'on_delivery'
                },
                {
                    data: 'partial_delivered'
                },
                {
                    data: 'complete'
                }
            ],
            buttons: [{
                extend: 'csvHtml5',
                text: '<i class="icofont-upload-alt"></i> CSV',
                titleAttr: 'CSV',
                title: 'Merchant Order Report',
            }],
            language: {
                processing: `<img src="{{ asset('assets/images/xloading.gif') }}">`
            },
            lengthMenu: [
                [10, 25, 100, -1],
                [10, 25, 100, "All"]
            ]
        });

        $('#filter_btn').click(function() {
            table.draw();
        });
    </script>
@endpush
