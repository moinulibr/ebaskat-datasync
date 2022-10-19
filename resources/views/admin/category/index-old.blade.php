@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __('CATEGORY') }}">
    <input type="hidden" id="attribute_data" value="{{ __('ADD NEW ATTRIBUTE') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Main Categories') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li><a href="javascript:">{{ __('Manage Categories') }}</a></li>
                        <li>
                            <a href="{{ route('admin.category.index') }}">{{ __('Main Categories') }}</a>
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
                                    <th width="20%">{{ __('Name') }}</th>
                                    <th width="20%">{{ __('Slug') }}</th>
                                    <th>{{ __('Attributes') }}</th>
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

    {{-- ATTRIBUTE MODAL --}}

    <div class="modal fade" id="attribute" tabindex="-1" role="dialog" aria-labelledby="attribute" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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

    {{-- ATTRIBUTE MODAL ENDS --}}

    {{-- DELETE MODAL --}}
    @include('includes.delete-modal', ['type' => 'CATEGORY'])
    {{-- Resote Modal --}}
    @include('includes.restore_modal', ['type' => 'CATEGORY'])

    {{-- DELETE MODAL ENDS --}}
    <input type="hidden" id="can_add" value="{{ Auth::guard('admin')->user()->role->permissionCheck('categories|add') }}">
    <input type="hidden" id="can_edit" value="{{ Auth::guard('admin')->user()->role->permissionCheck('categories|edit') }}">

@endsection


@section('scripts')

    {{-- DATA TABLE --}}

    <script type="text/javascript">

        var table = $('#geniustable').DataTable({
            ordering: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.category.datatables') }}',
            columns: [
                {data: 'name', name: 'name'},
                {data: 'slug', name: 'slug'},
                {data: 'attributes', name: 'attributes', searchable: false, orderable: false},
                {data: 'status', searchable: false, orderable: false},
                {data: 'action', searchable: false, orderable: false}

            ],
            language: {
                processing: '<img src="{{asset('assets/images/xloading.gif')}}">'
            },
            drawCallback: function (settings) {
                $('.select').niceSelect();
            }
        });

        if (! +$('#can_edit').val() ) {
            // status -> 4
            table.column(3).visible(0);
        }

        if($('#can_add').val())
        $(function () {
            $(".btn-area").append('<div class="col-sm-4 table-contents">' +
                '<a class="add-btn" data-href="{{route('admin.category.create')}}" id="add-data" data-toggle="modal" data-target="#modal1">' +
                '<i class="fas fa-plus"></i> Add New Category' +
                '</a>' +
                '</div>');
        });


    </script>

@endsection
