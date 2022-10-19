@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')

<input type="hidden" id="headerdata" value="{{ __('Specific Product Report') }}">

<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-5">
                <h4 class="heading">{{ __('Merchant Product History Report') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.all.report') }}">{{ __('Reports') }}</a>
                    </li>
                    <li>
                        {{ __('Merchant Product History Report') }}
                    </li>
                </ul>
            </div>
            <div class="col-md-7 mt-3">
                <div class="btn-group float-right" role="group" aria-label="Order Status">
                    <a href="{{ route('admin.report.merchant.details') }}" class="btn btn-secondary">Merchant Details</a>
                    <a href="{{ route('admin.report.merchant.subscription_history') }}" class="btn btn-secondary">Subscription history</a>
                    <a href="{{ route('admin.report.merchant.product_history') }}" class="btn btn-info">Product history</a>
                    <a href="{{ route('admin.report.merchant.order_report') }}" class="btn btn-secondary">Order report</a>
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
                    <label class="font-weight-bold">Merchant List</label>
                    <select name="user_id" id="user_id" class="form-control">
                        <option value="" >Select One</option>
                        @foreach ($merchants as $item)
                            <option value="{{$item->user_id}}">{{$item->name}}</option>
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
                                        <th>{{ __('Vendor Name') }}</th>
                                        <th>{{ __('Product Name') }}</th>
                                        <th>{{ __('SKU') }}</th>
                                        <th>{{ __('Stock') }}</th>
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
    <script>
        let table = $('#report_table').DataTable({
            dom: 'lBfrtip',
            ordering: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.report.merchant.product_history') }}",
                data: function(data){
                    data.from_date = $('#from').val();
                    data.to_date = $('#to').val();
                    data.country = $('#country').val() || null;
                    data.email = $('#email').val() || null;
                    data.phone = $('#phone').val() || null;
                    data.user_id = $('#user_id :selected').val();
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
                    data: 'user_name'
                },
                {
                    data: 'product_name'
                },
                {
                    data: 'sku'
                },
                {
                    data: 'stock'
                },
                {
                    data: 'status'
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
