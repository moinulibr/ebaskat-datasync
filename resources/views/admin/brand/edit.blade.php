
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content ">
                <div class="submit-loader">
                    <img class="loading" src="{{ asset('assets/images/xloading.gif') }}" alt="">
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Brand</h5>
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
                                            <form action="{{route('admin.brand.update')}}" method="POST" class="editBrand" id="formResetId" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" value="{{$brand->id}}" name="id" />
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="left-area">
                                                            <h4 class="heading">{{ __('Name') }} *</h4>
                                                            <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <input type="text" class="input-field" name="name"
                                                            placeholder="{{ __('Enter Name') }}" required="" 
                                                            value="{{$brand->name}}"
                                                            onload="convertToSlugForEdit(this.value)" onkeyup="convertToSlugForEdit(this.value)" >
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
                                                     <textarea name="slug"  class="input-field" id="slug-text-edit" placeholder="Enter  Slug">{{ $brand->slug }}</textarea>
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
                                                    <input type="text" class="input-field" name="email"
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
                                                            <div id="image-preview" class="img-preview" style="background: url({{ $brand->logo ? asset('storage/brand/'.$brand->logo):asset('storage/no-image-found/noimage.png') }});">
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
                                                    <select name="merchant_id[]" class="form-control" multiple="multiple" >
                                                        <option value="">Please Select Merchant</option>
                                                        @foreach ($merchants as $item)
                                                            
                                                            @php $c = 0 @endphp
                                                            @foreach($brand->merchants as $bms)
                                                            @if($bms->id == $item->id)
                                                            @php $c = 1 @endphp
                                                            @break
                                                            @endif
                                                            @endforeach  
                                                            
                                                            @if($c == 1)
                                                            <option selected value="{{$item->id}}">{{$item->shop_name}}</option>
                                                            @else
                                                            <option value="{{$item->id}}">{{$item->shop_name}}</option>
                                                            @endif
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
                                                            <button class="addProductSubmit-btn" type="submit">{{ __('Update') }}</button>
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