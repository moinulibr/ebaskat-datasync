@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')

    <input type="hidden" id="headerdata" value="{{ __('Specific Product Report') }}">

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-6">
                    <h4 class="heading">{{ __('Merchant Wise Product Report') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.all.report') }}">{{ __('Reports') }}</a>
                        </li>
                        <li>
                            {{ __('Merchant Wise Product Report') }}
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="btn-group float-right" role="group">
                        <a href="{{ route('admin.report.product.list') }}" class="btn btn-secondary">Product list</a>
                        <a href="{{ route('admin.report.product.specific') }}" class="btn btn-secondary">Specific</a>
                        <a href="{{ route('admin.report.product.merchant-wise') }}" class="btn btn-info">Merchant wise</a>
                        <a href="{{ route('admin.report.product.stock-wise') }}" class="btn btn-secondary">Stock wise</a>
                        <a href="{{ route('admin.report.product.best-sell') }}" class="btn btn-secondary">Best sell</a>
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
                      <label class="font-weight-bold">Select Merchant</label>
                      <select id="merchant_list" class="form-control">
                        <option value="">Type Product</option>
                        @foreach ($vendors as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                      <label class="font-weight-bold">Report</label> <br>
                      <button id="filter_btn" class="btn btn-sm btn-info">Filter Now</button>
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
                                            <th>{{ __('SKU') }}</th>
                                            <th>{{ __('Meta Description') }}</th>
                                            <th>{{ __('Buy Pirce') }}</th>
                                            <th>{{ __('Sell Price') }}</th>
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
        $('#merchant_list').select2();

        let table = $('#report_table').DataTable({
            dom: 'lBfrtip',
            ordering: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.report.product.merchant-wise') }}",
                data: function(data){
                    data.id = $('#merchant_list :selected').val();
                    data.from_date = $('#from').val();
                    data.to_date = $('#to').val();
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
                    data: 'name'
                },
                {
                    data: 'sku'
                },
                {
                    data: 'meta_description'
                },
                {
                    data: 'price'
                },
                {
                    data: 'commission',
                    render: function(data, temp, row) {
                        return row.price + row.commission || 0;
                    }
                },
                {
                    data: 'date'
                }
            ],
            buttons: [{
                extend: 'csvHtml5',
                text: '<i class="icofont-upload-alt"></i> CSV',
                titleAttr: 'CSV',
                title: 'Merchant Wise Product',
            }],
            language: {
                processing: `<img src="{{ asset('assets/images/xloading.gif') }}">`
            },
            lengthMenu: [
                [10, 25, 100, -1],
                [10, 25, 100, "All"]
            ]
        });

        $('#merchant_list').on('select2:select', function (e) {
            table.draw();
        });
        $('#filter_btn').click(function() {
            table.draw();
        });
    </script>
@endpush
