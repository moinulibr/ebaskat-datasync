@extends('layouts.admin') 

@section('content')  
<input type="hidden" id="headerdata" value="{{ __('Order Shipment') }}">
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                    <h4 class="heading">{{ __('Order Shipment - Ready to Shipped') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.order.shipment.ready.to.shipped') }}">{{ __('Ready to Shipped') }}</a>
                        </li>
                    </ul>
            </div>
        </div>
    </div>
    <div class="product-area">
        <div class="row">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    @include('includes.form-success') 
                    <table class="table table-sm">
                        <thead>
                          <tr>
                            <th scope="col">Order Package Number</th>
                            <th scope="col">Merchant Name</th>
                            <th scope="col">Payment Status</th>
                            <th scope="col">Delivery Status</th>
                            <th scope="col">Shipment</th>
                            <th scope="col">Shipping</th>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach($order_package as $package)
                          <tr>
                            <th scope="row">{{ $package->order_package_number }}</th>
                            <td>{{ $package->merchantInfo->shop_name }}</td>
                            <td>
                                <span class="badge badge-light">{{ $package->payment_status }}</span>
                            </td>
                            <td>
                                <span class="badge badge-light">{{ $package->delivery_status }}</span>
                            </td>
                            <td><button type="button" class="btn btn-primary btn-sm">Assign</button></td>
                            <td><button type="button" class="btn btn-info btn-sm">Invoice</button></td>
                          </tr>
                        @endforeach
                        </tbody>
                      </table>
                      <div class="d-flex justify-content-between border-top">
                          <div class="mt-2">
                            Showing: <b>{{$order_package->total() }}</b> to <b>{{$order_package->count() }}</b> Entries
                          </div>
                          <div class="mt-2">
                            {{ $order_package->links() }}
                          </div>
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection