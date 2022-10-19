<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="eBaskat">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','eBaskat')</title>
    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="{{asset('assets/images/favicon.ipg')}}"/>
    <!-- Bootstrap -->
    <link href="{{asset('assets/admin/css/bootstrap-4.6.0.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/admin/css/daterangepicker.min.css')}}" rel="stylesheet"/>

    <!-- Fontawesome -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/fontawesome.css')}}">
    <!-- icofont -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/icofont.min.css')}}">
    <!-- Sidemenu Css -->
    <link href="{{asset('assets/admin/plugins/fullside-menu/css/dark-side-style.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/admin/plugins/fullside-menu/waves.min.css')}}" rel="stylesheet"/>

    <link href="{{asset('assets/admin/css/select2.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/admin/css/plugin.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/admin/css/jquery.tagit.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-coloroicker.css') }}">
    <!-- Main Css -->

    <!-- stylesheet -->
    
    <link href="{{asset('assets/admin/css/style.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/admin/css/custom.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/admin/css/responsive.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/admin/css/common.css')}}" rel="stylesheet"/>
    <style>
        .disable-click{
            pointer-events:none;
        }
    </style>
    @yield('styles')
    @stack('styles')
</head>
<body>
<div class="page">
    <div class="page-main">
        <!-- Header Menu Area Start -->
        <div class="header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between">
                    <a class="admin-logo" href="{{ env('CUSTOMER_APP_URL') }}" target="_blank">
                        <img src="{{ asset('storage/e-basket.png') }}" alt="logo">
                    </a>
                    <div class="menu-toggle-button">
                        <a class="nav-link" href="javascript:;" id="sidebarCollapse">
                            <div class="my-toggl-icon">
                                <span class="bar1"></span>
                                <span class="bar2"></span>
                                <span class="bar3"></span>
                            </div>
                        </a>
                    </div>

                    <div class="right-eliment">
                        <ul class="list">

                            <input type="hidden" id="all_notf_count" >

                            <li class="bell-area">
                                <a id="notf_conv" class="dropdown-toggle-1" target="_blank"
                                   href="https://ebaskat.com">
                                    <i class="fas fa-globe-americas"></i>
                                </a>
                            </li>


                            {{-- <li class="bell-area">
                                <a id="notf_conv" class="dropdown-toggle-1" href="javascript:;">
                                    <i class="far fa-envelope"></i>
                                    <span id="conv-notf-count">{{ App\Models\Notification::countConversation() }}</span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdownmenu-wrapper" data-href="{{ route('conv.notification.show') }}"
                                         id="conv-notf-show">
                                    </div>
                                </div>
                            </li>

                            <li class="bell-area">
                                <a id="notf_product" class="dropdown-toggle-1" href="javascript:;">
                                    <i class="icofont-cart"></i>
                                    <span id="product-notf-count">{{ App\Models\Notification::countProduct() }}</span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdownmenu-wrapper" data-href="{{ route('product.notification.show') }}"
                                         id="product-notf-show">
                                    </div>
                                </div>
                            </li>

                            <li class="bell-area">
                                <a id="notf_user" class="dropdown-toggle-1" href="javascript:;">
                                    <i class="far fa-user"></i>
                                    <span id="user-notf-count">{{ App\Models\Notification::countRegistration() }}</span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdownmenu-wrapper" data-href="{{ route('user.notification.show') }}"
                                         id="user-notf-show">
                                    </div>
                                </div>
                            </li>

                            <li class="bell-area">
                                <a id="notf_order" class="dropdown-toggle-1" href="javascript:;">
                                    <i class="far fa-newspaper"></i>
                                    <span id="order-notf-count">{{ App\Models\Notification::countOrder() }}</span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdownmenu-wrapper" data-href="{{ route('order.notification.show') }}"
                                         id="order-notf-show">
                                    </div>
                                </div>
                            </li> --}}

                            <li class="login-profile-area">
                                <a class="dropdown-toggle-1" href="javascript:;">
                                    <div class="user-img">
                                        <img
                                            src="{{ Auth::guard('admin')->user()->photo ? asset('storage/admins/'.Auth::guard('admin')->user()->photo ):asset('assets/images/e-basket-mobile.png') }}"
                                            alt="" style="background-color: white;">
                                    </div>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdownmenu-wrapper">
                                        <ul>
                                            <h5>{{ __('Welcome!') }}</h5>
                                            <li>
                                                <a href="{{ route('admin.profile') }}"><i
                                                        class="fas fa-user"></i> {{ __('Edit Profile') }}</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.password') }}"><i
                                                        class="fas fa-cog"></i> {{ __('Change Password') }}</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.logout') }}"><i
                                                        class="fas fa-power-off"></i> {{ __('Logout') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header Menu Area End -->
        <div class="wrapper">
            <!-- Side Menu Area Start -->
            <nav id="sidebar" class="nav-sidebar">
                <ul class="list-unstyled components" id="accordion">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="wave-effect">
                            <i class="fas fa-tachometer-alt"></i>{{ __('Dashboard') }}
                        </a>
                    </li>
                    @if(Auth::guard('admin')->user()->IsSuper())
                        @include('includes.sidebar.super')
                    @else
                        @include('includes.sidebar.normal')
                    @endif
                </ul>
            </nav>
            <!-- Main Content Area Start -->
        @yield('content')
        <!-- Main Content Area End -->
        </div>
    </div>
</div>



<!-- Dashboard Core -->
<script src="{{asset('assets/admin/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('assets/admin/js/moment.min.js')}}"></script>

<script src="{{asset('assets/admin/js/vendors/vue.js')}}"></script>
<script src="{{asset('assets/admin/js/bootstrap.bundle-4.6.0.min.js')}}"></script>
<script src="{{asset('assets/admin/js/jqueryui.min.js')}}"></script>

{{-- daterange picker picker url : https://www.daterangepicker.com/ --}}
<script src="{{asset('assets/admin/js/daterangepicker.min.js')}}"></script>
<script>
    $('.datetime').daterangepicker({
        // timePicker: true,
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        locale: {
            format: 'YYYY-MM-DD'
        },
        autoApply:true
    });

    $('#from').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        startDate: moment().subtract(365, 'days'),
        minYear: 1901,
        locale: {
            format: 'YYYY-MM-DD'
        },
        autoApply:true
    });
</script>

<!-- Fullside-menu Js-->
<script src="{{asset('assets/admin/plugins/fullside-menu/jquery.slimscroll.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/fullside-menu/waves.min.js')}}"></script>

<script src="{{asset('assets/admin/js/plugin.js')}}"></script>
<script src="{{asset('assets/admin/js/tag-it.js')}}"></script>
<script src="{{asset('assets/admin/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{asset('assets/admin/js/notify.js') }}"></script>

<script src="{{asset('assets/admin/js/load.js')}}"></script>
<!-- Custom Js-->
<script src="{{asset('assets/admin/js/custom.js')}}"></script>
<!-- AJAX Js-->
<script src="{{asset('assets/admin/js/myscript.js')}}"></script>
{{-- select 2 --}}
<script src="{{asset('assets/admin/js/select2.full.min.js')}}"></script>
<script>
    $('.select2').select2();
</script>

<script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
</script>

@yield('scripts')
@stack('scripts')

</body>

</html>
