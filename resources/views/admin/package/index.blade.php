@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __('PACKAGING') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Packagings') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>

                        <li>
                            <a href="javascript:;">{{ __('General Settings') }}</a>
                        </li>

                        <li>
                            <a href="{{ route('admin-package-index') }}">{{ __('Packagings') }}</a>
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
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Price') }}</th>
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
                    <img src="{{ asset('assets/images/xloading.gif') }}" alt="">
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
    {{-- DELETE MODAL --}}
    @include('includes.delete-modal', ['type' => 'Packaging'])
    {{-- Resote Modal --}}
    @include('includes.restore_modal', ['type' => 'Packaging'])

    <input type="hidden" id="can_add" value="{{ \Auth::guard('admin')->user()->role->permissionCheck('general_settings|add') }}">
@endsection

@section('scripts')

    <script type="text/javascript">
        var table = $('#geniustable').DataTable({
            ordering: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin-package-datatables') }}',
            columns: [{
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'action',
                    searchable: false,
                    orderable: false
                }

            ],
            language: {
                processing: '<img src="{{ asset('assets/images/xloading.gif') }}">'
            }
        });

        if($('#can_add').val())
        $(function() {
            $(".btn-area").append('<div class="col-sm-4 table-contents">' +
                '<a class="add-btn" data-href="{{ route('admin-package-create') }}" id="add-data" data-toggle="modal" data-target="#modal1">' +
                '<i class="fas fa-plus"></i> {{ __('Add New Packaging') }}' +
                '</a>' +
                '</div>');
        });

    </script>

@endsection
