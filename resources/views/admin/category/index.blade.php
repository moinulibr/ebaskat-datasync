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
                            <div class="row">
                                <div class="col-sm-8 form-group">
                                    <form action="{{ route('admin.category.index') }}" id="form_search">
                                    <input type="text" name="search" class="form-control w-50" id="search" value="{{ $search }}" placeholder="Write & Enter">
                                    </form>
                                </div>
                                <div class="col-sm-4 table-contents pt-2">
                                    <a class="add-btn add_control" data-href="{{route('admin.category.create')}}" id="add-data" data-toggle="modal" data-target="#modal1">
                                    <i class="fas fa-plus"></i> {{ __('Add New Category') }}
                                    </a>
                                </div>
                            </div>
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
                                @foreach ($categorys as $categorie)
                                <tr>
                                    <td>{{ $categorie->name }}</td>
                                    <td>{{ $categorie->slug }}</td>
                                    <td>
                                        <div class="action-list">
                                            <a data-href="{{ route('admin.attr.create.category', $categorie->id) }}" class="attribute" data-toggle="modal" data-target="#attribute"> <i class="fas fa-edit"></i>Create</a>
                                            @if ($categorie->attributes()->count() > 0)
                                                <a href="{{ route('admin.attr.manage', $categorie->id).'?type=category'}}" class="edit"> <i class="fas fa-edit"></i>Manage</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $class = $categorie->status == 1 ? 'drop-success' : 'drop-danger';
                                            $s = $categorie->status == 1 ? 'selected' : '';
                                            $ns = $categorie->status == 0 ? 'selected' : '';
                                        @endphp
                                        <div class="action-list">
                                            <select class="process select droplinks {{ $class }}'">
                                                <option data-val="1" value="{{ route('admin.category.status', ['id1' => $categorie->id, 'id2' => 1]) }}"  {{ $s }}>Activated</option>
                                                <option data-val="0" value="{{ route('admin.category.status', ['id1' => $categorie->id, 'id2' => 0]) }}" {{ $ns }}>Deactivated</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-list">
                                            <a data-href="{{ route('admin.category.edit', $categorie->id) }}" class="edit edit_control" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a>
                                            @if(! $categorie->deleted_at)
                                                @if(Auth::guard('admin')->user()->role->permissionCheck('categories|delete'))
                                                    <a href="javascript:;" data-href="{{ route('admin.category.delete', $categorie->id) }}" data-toggle="modal" data-target="#delete_modal" class="delete bg-danger"><i class="fas fa-trash-alt"></i></a>
                                                @endif
                                            @else
                                                <a href="javascript:;" data-href="{{ route('admin.cat.restore', $categorie->id) }}" data-toggle="modal" data-target="#restore_modal" title="restore"><i class="fas fa-reply-all"></i></a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                </thead>
                            </table>
                            {{ $categorys->links('vendor.pagination.custom') }}
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
    @include('includes.delete-modal-1', ['type' => 'CATEGORY'])
    {{-- Resote Modal --}}
    @include('includes.restore_modal-1', ['type' => 'CATEGORY'])

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
