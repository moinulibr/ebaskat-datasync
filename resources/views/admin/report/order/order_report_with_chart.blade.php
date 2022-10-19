@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')

    <input type="hidden" id="headerdata" value="{{ __('Order Report with Chart') }}">

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-5">
                    <h4 class="heading">{{ __('Order Report with Chart') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.all.report') }}">{{ __('Reports') }}</a>
                        </li>
                        <li>
                            {{ __('Order Report with Chart') }}
                        </li>
                    </ul>
                </div>
                <div class="col-md-7 mt-3">
                    <div class="btn-group float-right" role="group" aria-label="Order Status">
                        <a href="{{ route('admin.report.order.customized') }}" class="btn btn-secondary">Customized Order</a>
                        {{-- <a href="{{ route('admin.report.order.merchant') }}" class="btn btn-secondary">Merchant Order</a>
                        <a href="{{ route('admin.report.order.customer') }}" class="btn btn-secondary">Customer Order</a> --}}
                        <a href="{{ route('admin.report.order.with-chart') }}" class="btn btn-info">Order with chart</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Filters</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12"></div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-6">
                        <canvas id="myChart" width="100" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script src="{{asset('assets/admin/js/chartv2.8.0.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/chartjs-plugin-datalabels@0.7.0.js')}}"></script>
    <script>
        let ctx = document.getElementById('myChart').getContext('2d');
        let myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Processing', 'Completed', 'Declined', 'On delivery'],
                datasets: [{
                    data: [12, 19, 3, 5, 2],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ]
                }],
            },
            options: {
                legend: {
                    position: 'right',
                    labels: {
                        filter: function (one, two) {
                            one.text =one.text +":"+ two.datasets[0].data[one.index];
                            return two;
                        }
                    }
                },
                plugins: {
                    datalabels: {
                        color: 'white',
                        font: {
                            weight: 'bold'
                        },
                        formatter: function(value, context) {
                            return value;
                        }
                    }
                },
            }
        });
    </script>
@endpush
