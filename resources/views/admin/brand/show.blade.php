  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content ">
                <div class="submit-loader">
                    <img class="loading" src="{{ asset('assets/images/xloading.gif') }}" alt="">
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Show Brand</h5>
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
                                            
                                            @include('includes.form-error')
                                         
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="left-area">
                                                            <h4 class="heading">{{ __('Name') }} *</h4>
                                                            <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <input type="text"  disabled="disabled" class="input-field" name="name"
                                                            placeholder="{{ __('Enter Name') }}" required="" value="{{$brand->name}}">
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
                                                        <input type="text"  disabled="disabled" class="input-field" name="slug"
                                                            placeholder="{{ __('Enter Slug') }}" required="" value="{{$brand->slug}}">
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
                                                    <input type="text"  disabled="disabled" class="input-field" name="web_address"
                                                            placeholder="{{ __('Web Address') }}" required="" value="{{$brand->web_address}}">
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
                                                    <input type="text"  disabled="disabled" class="input-field" name="email"
                                                            placeholder="{{ __('Email') }}" required="" value="{{$brand->email}}">
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
                                                            @if ($brand->logo)
                                                                <img src="{{asset('storage/brand/'.$brand->logo)}}"  id="image-preview" class="img-preview">
                                                                @else
                                                                <img src="{{asset('storage/no-image-found/noimage.png')}}"  id="image-preview" class="img-preview">
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="left-area">
                                                            <h4 class="heading">{{ __('Merchants') }}*</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        @foreach($brand->merchants as $merchant)
                                                        <strong style="margin-right:5xp" class="badge badge-primary">
                                                            {{$merchant->shop_name}}
                                                        </strong>
                                                        @endforeach  
                                                    </div>
                                                </div>
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