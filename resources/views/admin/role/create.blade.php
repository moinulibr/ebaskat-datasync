@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{asset('assets/admin/css/smart_tab_all.min.css')}}">
@endpush

@section('content')

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Add Role') }} <a class="add-btn float-right btn-sm mt-3"
                            href="{{ route('admin-role-index') }}"><i class="fas fa-arrow-left"></i>
                            {{ __('Back') }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-role-index') }}">{{ __('Manage Roles') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-role-create') }}">{{ __('Add Role') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="add-product-content1">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="gocover"
                                style="background: url({{ asset('assets/images/xloading.gif') }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <form id="geniusform" action="{{ route('admin-role-create') }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                @include('includes.form-both')

                                <div class="row">
                                    <div class="col-lg-2">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Name') }} *</h4>
                                            <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-10">
                                        <input type="text" class="input-field" name="name"
                                            placeholder="{{ __('Name') }}" required="" value="">
                                    </div>
                                </div>

                                <hr>
                                <h5 class="text-center">{{ __('Permissions') }}</h5>
                                <hr>

                                {{-- @foreach ($permissions as $item)
                                    <div class="row justify-content-center main_div">
                                        <div class="col-lg-3 d-flex justify-content-between">
                                            <label class="control-label">{{ $item->name }} </label>
                                            <label class="switch">
                                                <input type="checkbox" class="main_check_btn" name="{{ $item->name }}[]">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-lg-7 d-flex justify-content-between sub_check_btns">
                                            @if (!$item->is_special)
                                                <label for="">Add</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="{{ $item->name }}[]" value="add">
                                                    <span class="slider round"></span>
                                                </label>

                                                <label for="">Edit</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="{{ $item->name }}[]" value="edit">
                                                    <span class="slider round"></span>
                                                </label>
                                                <label for="">Delete</label>
                                                <label class="switch">
                                                    <input type="checkbox" name="{{ $item->name }}[]" value="delete">
                                                    <span class="slider round"></span>
                                                </label>
                                            @endif

                                        </div>
                                    </div>
                                @endforeach --}}

                                <div id="smarttab">
                                    <ul class="nav">
                                        @foreach ($permissions as $item)
                                        <li>
                                            <a class="nav-link" href="#{{$item->name}}">
                                                {{$item->name}}
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                 
                                    <div class="tab-content">

                                        @foreach ($permissions as $item)
                                            <div id="{{$item->name}}" class="tab-pane main_div" role="tabpanel">
                                                <div>
                                                    <label class="switch">
                                                        <input class="main_check_btn" type="checkbox" name="{{$item->name}}[]" value="head">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label for="">Head</label>
                                                </div>
                                                @foreach ($item->allowed as $allowed)
                                                <div>
                                                    <label class="switch">
                                                        <input type="checkbox" class="sub_check_btns" name="{{$item->name}}[]" value="{{$allowed}}">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label for="">{{$allowed}}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-5">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <button class="addProductSubmit-btn"
                                            type="submit">{{ __('Create Role') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- http://techlaboratory.net/jquery-smarttab --}}
    <script src="{{asset('assets/admin/js/jquery.smartTab.min.js')}}"></script>
    <script>
        // if main check box is not checked the all sub check box will un-check
        $('.main_check_btn').click(function(e) {
            if (!$(this).is(':checked')) {
                let checkboxes = $(this).closest('.main_div').find('.sub_check_btns');
                // console.log(checkboxes);
                $.each(checkboxes, function(i, el) {
                    //  console.log(el);
                    $(el).prop('checked', false);
                });
            }

        });

        // if any sub check box is checked then main check box will be checked
        $('.sub_check_btns').click(function() {
            let main_checkbox = $(this).closest('.main_div').find('.main_check_btn')[0];
            $(main_checkbox).prop('checked', true);
        });

        $('#smarttab').smartTab({
            transition: {
                animation: 'slide-horizontal', // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
            }
        });
    </script>
@endpush
