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
                <form id="form_csv" action="{{ route('admin.report.customer.full_report') }}">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Date (from)</label>
                        <input type="text" name="from_date" class="form-control datetime" id="from">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="font-weight-bold">Date (to)</label>
                        <input type="text" name="to_date" class="form-control datetime" id="to">
                    </div>
                    {{-- <div class="form-group col-md-3">
                        <label class="font-weight-bold">Active Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Select One</option>
                            <option value="0">Active</option>
                            <option value="1">Inactive</option>
                        </select>
                    </div> --}}
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
                      <button id="filter_btn_clear" class="btn btn-md btn-danger"> Clear Filter</button>
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
                            </form>
                            </div>
                            <div class="customerListAjaxResult" style="font-family: sans-serif">
                                <div class="table-responsive">
                                    <table id="report_table" class="table table-hover dt-responsive" cellspacing="0"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                {{-- <th>{{ __('Sl') }}</th> --}}
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
    </div>
    <input type="hidden" class="customerListByAjaxResponseUrl" value="{{ route('admin.report.customer.full_report.ajaxresponse') }}">
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/buttons.html5.min.js') }}"></script>

    {{---customer list by ajax---}}
    <script src="{{asset('custom_js/customer/report.js')}}"></script>
    {{---customer list by ajax---}}

    <script type="text/javascript">
         $( document ).ready(function() {
            $('#csv').val(0);
        });

        // csv file download
        $(document).on('click','#csv_button',function(e){
            e.preventDefault();
            $('#csv').val(1);
            $('#form_csv').submit();
        });
    </script>
@endpush
