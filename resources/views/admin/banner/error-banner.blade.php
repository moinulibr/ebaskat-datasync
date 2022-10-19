@extends('layouts.admin')
@section('content')

<div class="content-area">
  <div class="mr-breadcrumb">
    <div class="row">
      <div class="col-lg-5">
          <h4 class="heading">{{ __('Error Page Banner') }} </h4>
          <ul class="links">
            <li>
              <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
            </li>
            <li>
              <a href="javascript:;">{{ __('Home Page Settings') }} </a>
            </li>
            <li>
              <a href="javascript:;">{{ __('Error Page Banner') }} </a>
            </li>
          </ul>
      </div>
      <div class="col-lg-7">
        <div class="mr-breadcrumb">
          <div class="row">
              <div class="col-md-12 mt-3">
                  <div class="btn-group float-right" role="group">
                    <a href="{{ route('slider-three-banner') }}" class="btn btn-secondary">Slider 3 Banners</a>
                    <a href="{{ route('three-promotional-banner') }}" class="btn btn-secondary">Promotional 3 Banners</a>
                    <a href="{{ route('top-flase-banner') }}" class="btn btn-secondary">Top Flase Banners</a>
                    <a href="{{ route('admin-popup') }}" class="btn btn-secondary">Popup Banners</a>
                    <a href="{{ route('admin-error-banner') }}" class="btn btn-info">Error Banners</a>
                  </div>
              </div>
          </div>
      </div>
    </div>
    </div>
  </div>
  <div class="add-product-content1">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="body-area">

          <div class="gocover" style="background: url({{asset('assets/images/xloading.gif')}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
            <form id="geniusform" action="{{ route('admin-error-banner-update') }}" method="POST" enctype="multipart/form-data">
              {{csrf_field()}}

              @include('includes.form-both')

                <div class="row">
                  <div class="col-lg-4">
                    <div class="left-area">
                        <h4 class="heading"> {{ __('Banner Image') }} *</h4>
                        <small>{{ __('(Preferred Size: 600 X 600 Pixel)') }}</small>
                    </div>
                  </div>

                  <div class="col-lg-8">
                    <div class="img-upload">
                        <div id="image-preview" class="img-preview" style="background: url({{ $errorBanner->photo ? $errorBanner->photo : asset('storage/no-image-found/noimage.png') }});">
                            <label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                            <input type="file" name="error_banner" class="img-upload" id="image-upload">
                        </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-4">
                    <div class="left-area">

                    </div>
                  </div>
                  <div class="col-lg-7">
                    <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
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
