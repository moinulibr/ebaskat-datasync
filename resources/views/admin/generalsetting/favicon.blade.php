@extends('layouts.admin')
@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Website Favicon') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:">{{ __('General Settings') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="add-logo-area shadow">
            <div class="gocover"
                 style="background: url({{asset('assets/images/xloading.gif')}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
            <div class="row justify-content-center">
                <div class="col-lg-6">

                    @include('includes.form-both')

                    <form class="uplogo-form" id="geniusform" action="{{ route('admin-gs-update') }}" method="POST"
                          enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="currrent-logo">
                            <h4 class="title">
                                {{ __('Current Favicon') }} :
                            </h4>
                            <img
                                src="{{ $gs->favicon ? asset('assets/images/favicon.ipg'):asset('assets/images/noimage.png')}}"
                                alt="">
                        </div>
                        <div class="set-logo">
                            <h4 class="title">
                                {{ __('Set New Favicon') }} :
                            </h4>
                            <input class="img-upload1" type="file" name="favicon">
                        </div>
                        <div class="submit-area">
                            <button type="submit" class="submit-btn">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
