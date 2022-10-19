@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __("SUB CATEGORY") }}">
    <input type="hidden" id="attribute_data" value="{{ __('ADD NEW ATTRIBUTE') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __("Sub Categories") }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __("Dashboard") }} </a>
                        </li>
                        <li><a href="javascript:">{{ __("Manage Categories") }}</a></li>
                        <li>
                            <a href="{{ route('admin-subcat-index') }}">{{ __("Sub Categories") }}</a>
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
                            <div class="row">
                                <div class="col-sm-8 form-group">
                                    <form action="{{ route('admin-subcat-index') }}" id="form_search">
                                    <input type="text" name="search" class="form-control w-50" id="search" value="{{ $search }}" placeholder="Write & Enter">
                                    </form>
                                </div>
                                <div class="col-sm-4 table-contents pt-2">
                                    <a class="add-btn add_control" data-href="{{route('admin-subcat-create')}}" id="add-data" data-toggle="modal" data-target="#modal1">
                                    <i class="fas fa-plus"></i> {{ __('Add New Sub Category') }}
                                    </a>
                                </div>
                            </div>
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>{{ __("Category") }}</th>
                                    <th>{{ __("Name") }}</th>
                                    <th>{{ __("Slug") }}</th>
                                    <th width="20%">{{ __('Attributes') }}</th>
                                    <th>{{ __("Status") }}</th>
                                    <th>{{ __("Options") }}</th>
                                </tr>
                                @foreach ($sub_categories as $sub_categorie)
                                <tr>
                                    <td>{{ $sub_categorie->category->name }}</td>
                                    <td>{{ $sub_categorie->name }}</td>
                                    <td>{{ $sub_categorie->slug }}</td>
                                    <td width="20%">
                                        <div class="action-list">
                                            <a data-href="{{ route('admin.attr.create.sub.category', $sub_categorie->id) }}" class="attribute" data-toggle="modal" data-target="#attribute"> <i class="fas fa-edit"></i>Create</a>
                                            @if ($sub_categorie->attributes()->count() > 0)
                                                <a href="{{ route('admin.attr.manage', $sub_categorie->id).'?type=subcategory'}}" class="edit"> <i class="fas fa-edit"></i>Manage</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $class = $sub_categorie->status == 1 ? 'drop-success' : 'drop-danger';
                                            $s = $sub_categorie->status == 1 ? 'selected' : '';
                                            $ns = $sub_categorie->status == 0 ? 'selected' : '';
                                        @endphp
                                        <div class="action-list">
                                            <select class="process select droplinks {{ $class }}'">
                                                <option data-val="1" value="{{ route('admin-subcat-status', ['id1' => $sub_categorie->id, 'id2' => 1]) }}"  {{ $s }}>Activated</option>
                                                <option data-val="0" value="{{ route('admin-subcat-status', ['id1' => $sub_categorie->id, 'id2' => 0]) }}" {{ $ns }}>Deactivated</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-list">
                                            <a data-href="{{ route('admin-subcat-edit', $sub_categorie->id) }}" class="edit edit_control" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a>
                                            @if(! $sub_categorie->deleted_at)
                                                @if(Auth::guard('admin')->user()->role->permissionCheck('categories|delete'))
                                                    <a href="javascript:;" data-href="{{ route('admin-subcat-delete', $sub_categorie->id) }}" data-toggle="modal" data-target="#delete_modal" class="delete bg-danger"><i class="fas fa-trash-alt"></i></a>
                                                @endif
                                            @else
                                                <a href="javascript:;" data-href="{{ route('admin.subcat.restore', $sub_categorie->id) }}" data-toggle="modal" data-target="#restore_modal" title="restore"><i class="fas fa-reply-all"></i></a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                </thead>
                            </table>
                            {{ $sub_categories->links('vendor.pagination.custom') }}
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Close") }}</button>
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
    @include('includes.delete-modal-1', ['type' => 'SUB CATEGORY'])
    {{-- Resote Modal --}}
    @include('includes.restore_modal-1', ['type' => 'SUB CATEGORY'])

    {{-- DELETE MODAL ENDS --}}
    <input type="hidden" id="can_add" value="{{ Auth::guard('admin')->user()->role->permissionCheck('categories|add') }}">
    <input type="hidden" id="can_edit" value="{{ Auth::guard('admin')->user()->role->permissionCheck('categories|edit') }}">

@endsection


@section('scripts')

    {{-- DATA TABLE --}}

    <script type="text/javascript">
        $(document).ready(function() {
            $('#search').keyup(function(event) {
                if (event.which === 13)
                {
                    event.preventDefault();
                    $('#form_search').submit();
                }
            });
        });
        if (! +$('#can_edit').val() ) {
            $('.edit_control').addClass("disable-click");
        }
        if($('#can_add').val() != 1){
            $('.add_control').addClass("disable-click")
        }
    </script>

@endsection
