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
                    <h4 class="heading">{{ __('Product Report') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.all.report') }}">{{ __('Reports') }}</a>
                        </li>
                        <li>
                            {{ __('Product Report') }}
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="btn-group float-right" role="group" aria-label="Order Status">
                        <a href="{{ route('admin.report.generel.customer') }}" class="btn btn-secondary">Customer</a>
                        <a href="{{ route('admin.report.generel.product') }}" class="btn btn-info">Product</a>
                        <a href="{{ route('admin.report.generel.merchant') }}" class="btn btn-secondary">Merchant</a>
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
                        <label class="font-weight-bold">Merchant</label>
                        <select id="merchant_id" class="form-control">
                          <option value="">Select One</option>
                          @foreach ($merchant_list as $item)
                              <option value="{{$item->user_id}}">{{$item->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      
                      <div class="form-group col-md-3">
                        <label class="font-weight-bold">Category</label>
                        <select id="category_id" class="form-control">
                          <option value="">Select One</option>
                          @foreach ($category as $item)
                          <option value="{{$item->id}}">{{$item->name}}</option>
                          @endforeach
                      </select>
                      </div>

                      <div class="form-group col-md-3">
                        <label class="font-weight-bold">Sub Category</label>
                        <select id="subcategory_id" class="form-control">
                              <option value="">Select One</option>
                              @foreach ($subcategory as $item)
                              <option value="{{$item->id}}">{{$item->name}}</option>
                              @endforeach
                        </select>
                      </div>
                      
                      <div class="form-group col-md-3">
                        <label class="font-weight-bold">Status</label>
                        <select id="status" class="form-control">
                              <option value="">Select One</option>
                              <option value="1">Active</option>
                              <option value="0">In Active</option>
                        </select>
                      </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Account Type</label>
                        <select id="type" class="form-control">
                            <option value=""> Select One</option>
                            @foreach ($types as $item)
                                <option value="{{$item}}"> {{$item}}</option>
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
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Sku') }}</th>
                                            <th>{{ __('Slug') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th>{{ __('Category') }}</th>
                                            <th>{{ __('Subcategory') }}</th>
                                            <th>{{ __('Color') }}</th>
                                            <th>{{ __('Price') }}</th>
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
                url: "{{ route('admin.report.generel.product') }}",
                data: function(data){
                    data.from_date = $('#from').val();
                    data.to_date = $('#to').val();
                    data.type = $('#type :selected').val();
                    data.merchant_id = $('#merchant_id :selected').val();
                    data.category_id = $('#category_id :selected').val();
                    data.subcategory_id = $('#subcategory_id :selected').val();
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
                    data: 'name'
                },
                {
                    data: 'sku'
                },
                {
                    data: 'slug'
                },
                {
                    data: 'product_type'
                },
                {
                    data: 'cat_name'
                },
                {
                    data: 'subcat_name'
                },
                {
                    data: 'color'
                },
                {
                    data: 'price'
                },
                {
                    data: 'status',
                    render: function(data){
                        return data? "Active": "Deactive";
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
                title: 'Product Report',
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
