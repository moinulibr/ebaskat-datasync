@extends('layouts.admin')

@section('styles')
    <style type="text/css">
        td img {
            max-height: 100px;
            max-width: 500px;
        }
    </style>
@endsection

@section('content')
    <input type="hidden" id="headerdata" value="{{ __('Global VAT TAX') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-5">
                    <h4 class="heading">{{ __('Global Vat Tax') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                        </li>
                        <li>
                            <a href="javascript:">{{ __('Global Vat Tax') }}</a>
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

                        <div class="table-responsiv">
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>{{ __('Country Name') }}</th>
                                    <th>{{ __('Country Code') }}</th>
                                    <th>{{ __('percentage') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Options') }}</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{-- ADD / EDIT MODAL --}}

    <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">


        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="submit-loader">
                    <img src="{{asset('assets/images/xloading.gif')}}" alt="">
                </div>
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ADD / EDIT MODAL ENDS --}}

@endsection



@section('scripts')


    {{-- DATA TABLE --}}

    <script type="text/javascript">

        var table = $('#geniustable').DataTable({
            ordering: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.globla.vat.tax.datatable') }}',
            columns: [
                {data: 'country_name', name: 'country_name'},
                {data: 'country_code', name: 'country_code'},
                {data: 'percentage', name: 'percentage'},
                {data: 'active_status', name: 'active_status'},
                {data: 'action', searchable: false, orderable: false}
            ],
            language: {
                processing: '<img src="{{asset('assets/images/xloading.gif')}}">'
            }
        });

        $(function () {
            $(".btn-area").append('<div class="col-sm-4 table-contents">' +
                '<a class="add-btn" data-href="{{route('admin.global.vat.tax.create')}}" id="add-data" data-toggle="modal" data-target="#modal1">' +
                '<i class="fas fa-plus"></i> {{ __('Add New Vat Tax') }}' +
                '</a>' +
                '</div>');
        });


    </script>


@endsection
