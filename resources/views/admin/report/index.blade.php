@extends('layouts.admin')
@section('content')

<input type="hidden" id="headerdata" value="{{ __('All Report') }}">
    <div class="content-area">

        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-6">
                    <h4 class="heading">{{ __('All Report') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.all.report') }}">{{ __('All Report') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <h4 class="card-title mt-4">Product Report</h4>
        <div class="card mb-4 p-2">
            <nav class="nav nav-pills nav-fill">
                <a class="btn btn-primary font-weight-bold mr-2 mb-2" href="{{route('admin.report.product.list')}}">Product List</a>
                <a class="btn btn-success font-weight-bold mr-2 mb-2" href="{{route('admin.report.product.specific')}}">Specific Product Report</a>
                {{-- <a class="btn btn-info font-weight-bold mr-2 mb-2" href="{{route('admin.report.product.merchant-wise')}}">Merchant Wise Product Report</a>
                <a class="btn btn-warning font-weight-bold mr-2 mb-2" href="{{route('admin.report.product.stock-wise')}}">Stock Wise Product Report</a> --}}
                <a class="btn btn-danger font-weight-bold mr-2 mb-2" href="{{route('admin.report.product.best-sell')}}">Best Sales Product Report</a>
            </nav>
        </div>
        <hr>
        <h4 class="card-title mt-4">Order Report</h4>
        <div class="card mb-4 p-2">
            <nav class="nav nav-pills nav-fill">
                <a class="btn btn-primary font-weight-bold mr-2 mb-2" href="{{route('admin.report.order.customized')}}">Customized Order Report</a>
                {{-- <a class="btn btn-success font-weight-bold mr-2 mb-2" href="{{route('admin.report.order.merchant')}}">Merchant Wize Order Report</a>
                <a class="btn btn-info font-weight-bold mr-2 mb-2" href="{{route('admin.report.order.customer')}}">Customer Wize Order Report</a> --}}
                <a class="btn btn-warning font-weight-bold mr-2 mb-2" href="{{route('admin.report.order.with-chart')}}">Order Report with Chart</a>
            </nav>
        </div>
        <hr>
        <h4 class="card-title">Merchant Report</h4>
        <div class="card mb-4 p-2">
            <nav class="nav nav-pills nav-fill">
                <a class="btn btn-primary font-weight-bold mr-2 mb-2" href="{{route('admin.report.merchant.details')}}">Merchant Details</a>
                <a class="btn btn-success font-weight-bold mr-2 mb-2" href="{{route('admin.report.merchant.subscription_history')}}">Merchant Subscription Plan</a>
                {{-- <a class="btn btn-info font-weight-bold mr-2 mb-2" href="{{route('admin.report.merchant.product_history')}}">Merchant Total Product</a> --}}
                <a class="btn btn-warning font-weight-bold mr-2 mb-2" href="{{route('admin.report.merchant.order_report')}}">Merchant Order Report</a>
            </nav>
        </div>
        <hr>
        <h4 class="card-title mt-4">General Report</h4>
        <div class="card mb-4 p-2">
            <nav class="nav nav-pills nav-fill"> 
                <a class="btn btn-primary font-weight-bold mr-2 mb-2" href="{{route('admin.report.generel.customer')}}">Customer Report</a>
                {{-- <a class="btn btn-success font-weight-bold mr-2 mb-2" href="{{route('admin.report.generel.product')}}">Product Report</a>
                <a class="btn btn-info font-weight-bold mr-2 mb-2" href="{{route('admin.report.generel.merchant')}}">Merchant Report</a> --}}
                <a class="btn btn-warning font-weight-bold mr-2 mb-2" href="{{route('admin.report.generel.subscription')}}">Subscription Report</a>
                <a class="btn btn-danger font-weight-bold mr-2 mb-2" href="{{route('admin.report.generel.coupon')}}">Coupon Report</a>
            </nav>
        </div>
        <hr>
        <h4 class="card-title mt-4">Customer Report</h4>
        <div class="card mb-4 p-2">
            <nav class="nav nav-pills nav-fill">
                <a class="btn btn-primary font-weight-bold mr-2 mb-2" href="{{route('admin.report.customer.full_report')}}">Customer Full Report</a>
            </nav>
        </div>
        

        
    </div>

@endsection