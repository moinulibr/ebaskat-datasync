@extends('layouts.admin')

@section('styles')
    <link href="{{asset('assets/multi_select/css/fselect.css')}}" rel="stylesheet"/>
    <script src="{{asset('assets/admin/js/vendors/jquery-1.12.4.min.js')}}"></script>
    <script src="{{asset('assets/multi_select/js/fselect.js')}}"></script>
    <script>
    (function($) {
        $(function() {
            $('.test').fSelect();
        });
    })(jQuery);
    </script>
@endsection

@section('content')
    <input type="hidden" id="headerdata" value="{{ __('BRAND') }}">
    <input type="hidden" id="attribute_data" value="{{ __('ADD NEW BRAND') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Brand') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li><a href="javascript:">{{ __('Manage Brand') }}</a></li>
                        <li>
                            <a href="{{ route('admin.brand.index') }}">{{ __('Brand') }}</a>
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

                        {{-- <div id="geniustable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="row btn-area">
                                <div class="col-sm-4 table-contents">
                                    <a class="add-btn addNewBrandModal" data-url="{{ route('admin.brand.create') }}">
                                        <i class="fas fa-plus"></i> Add New Brand
                                    </a>
                                </div>
                            </div>
                        </div> --}}
                        <div class="table-responsiv">

                            <p class="mess" style="text-align:center;"></p>

                            <table id="geniustable" class="table table-hover dt-responsive dataTable no-footer dtr-inline" cellspacing="0"
                            width="100%" role="grid" aria-describedby="geniustable_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting_asc">Sl</th>
                                        <th class="sorting_asc">Name</th>
                                        <th class="sorting" tabindex="0">Slug</th>
                                        <th class="sorting" tabindex="0">Web Address</th>
                                        <th class="sorting_disabled">Email Address </th>
                                        <th class="sorting_disabled">Action</th>
                                    </tr>
                                </thead>
                            </table>
    
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ADD Brand MODAL --}}
    <div class="modal fade" id="addNewBrandModal" tabindex="-1" role="dialog" aria-labelledby="addNewBrandModal" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content ">
                <div class="submit-loader">
                    <img class="loading" src="{{ asset('assets/images/xloading.gif') }}" alt="">
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Add New Brand</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                    <div class="modal-body">
                        <div class="add-product-content1">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="product-description">

                                        <div class="body-area">

                                            <p class="message" style="text-align: center;padding-bottom: 13px;font-size: 15px;color: green;"></p>

                                            @include('includes.form-error')
                                            <form action="{{route('admin.brand.store')}}" method="POST" class="createBrand" id="formResetId" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="left-area">
                                                            <h4 class="heading">{{ __('Name') }} *</h4>
                                                            <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <input type="text" class="input-field" name="name"
                                                            placeholder="{{ __('Enter Name') }}" required="" value=""
                                                           onload="convertToSlug(this.value)" onkeyup="convertToSlug(this.value)" autocomplete="off">
                                                            <strong class="name_err color-red"></strong>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="left-area">
                                                            <h4 class="heading">{{ __('Slug') }} *</h4>
                                                            <p class="sub-heading">{{ __('(In English)') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <textarea name="slug"  class="input-field" id="slug-text" placeholder="Enter  Slug">{{ old('slug') }}</textarea>
                                                            <strong class="slug_err color-red"></strong>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="left-area">
                                                            <h4 class="heading">{{ __('Web Address') }}*</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-7">
                                                    <input type="text" class="input-field" name="web_address"
                                                            placeholder="{{ __('Web Address') }}" required="" value="">
                                                            <strong class="web_address_err color-red"></strong>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="left-area">
                                                            <h4 class="heading">{{ __('Email') }}*</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-7">
                                                    <input type="text" class="input-field" name="email"
                                                            placeholder="{{ __('Email') }}" required="" value="">
                                                            <strong class="email_err color-red"></strong>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="left-area">
                                                            <h4 class="heading">{{ __('Logo') }} *</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <div class="img-upload">
                                                            <div id="image-preview" class="img-preview" >{{---style="background: url(http://localhost/Laravel_8/Akib_bhai/ecom/ebaskat-admin/public/assets/admin/images/upload.png);"--}}
                                                                <label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i>Upload Icon</label>
                                                                <input type="file" name="logo" class="img-upload" id="image-upload">
                                                                <strong class="logo_err color-red"></strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="left-area">
                                                            <h4 class="heading">{{ __('Merchant') }}*</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-7">
                                                    <select name="merchant_id[]" class="test" multiple="multiple">
                                                        <option value="">Please Select Merchant</option>
                                                        @foreach ($merchants as $item)
                                                            <option value="{{$item->id}}">{{$item->shop_name}}</option>
                                                        @endforeach
                                                    </select>
                                                        <strong class="merchant_id_err color-red"></strong>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="left-area">

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <div class="col-lg-7">
                                                            <button class="addProductSubmit-btn" type="submit">{{ __('Create') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- ADD Brand MODAL ENDS --}}

    {{-- Edit Brand MODAL --}}
    <input type="hidden" value="{{route('admin.brand.edit')}}" class="editModalUrl">
    <div class="modal fade" id="editBrandModal" tabindex="-1" role="dialog" aria-labelledby="editBrandModal" aria-hidden="true">
    </div>
    {{-- Edit Brand MODAL ENDS --}}

    {{-- Show Brand MODAL --}}
    <input type="hidden" value="{{route('admin.brand.show')}}" class="showModalUrl">
    <div class="modal fade" id="editBrandModal" tabindex="-1" role="dialog" aria-labelledby="editBrandModal" aria-hidden="true">
    </div>
    {{-- Show Brand MODAL ENDS --}}

    <input type="hidden" value="{{route('admin.brand.delete')}}" class="deleteModalUrl">



    {{-- ATTRIBUTE MODAL --}}
    <div class="modal fade" id="attribute" tabindex="-1" role="dialog" aria-labelledby="attribute" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
    {{-- ATTRIBUTE MODAL ENDS --}}

    {{-- DELETE MODAL --}}
    @include('includes.delete-modal', ['type' => 'Brand'])
    {{-- Resote Modal --}}
    @include('includes.restore_modal', ['type' => 'Brand'])

    {{-- DELETE MODAL ENDS --}}
    <input type="hidden" id="can_add"
        value="{{ Auth::guard('admin')->user()->role->permissionCheck('brands_manage|add') }}">
    <input type="hidden" id="can_edit"
        value="{{ Auth::guard('admin')->user()->role->permissionCheck('brands_manage|edit') }}">

@endsection


@section('scripts')
    <script src="{{asset('custom_js/brand/index.js?a=1')}}" ></script>
    <script src="{{asset('custom_js/brand/edit.js?a=1')}}" ></script>
    {{-- DATA TABLE --}}

    <script>
        var table = $('#geniustable').DataTable({
            ordering: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.brand.index') }}',
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
                    data: 'slug'
                },
                {
                    data: 'web_address'
                },
                {
                    data: 'email'
                },
                {
                    data: 'action'
                }
            ],
            language: {
                processing: '<img src="{{asset('assets/images/xloading.gif')}}">'
            },
            drawCallback: function (settings) {
                $('.select').niceSelect();
            }
        });

        if($('#can_add').val())
        $(function () {
            $(".btn-area").append(`<div class="col-sm-4 table-contents">` +
                `<a class="add-btn addNewBrandModal" data-url="{{ route('admin.brand.create') }}">
                    <i class="fas fa-plus"></i> Add New Brand</a>` +
                `</div>`);
        });
    </script>

    {{-- <script type="text/javascript">

        var table = $('#geniustable').DataTable({
            ordering: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin-subcat-datatables') }}',
            columns: [
                {data: 'category', searchable: false, orderable: false},
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
            table.column(4).visible(0);
        }

        


    </script> --}}

@endsection
