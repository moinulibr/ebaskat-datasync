@extends('layouts.load')

@section('content')
            <div class="content-area">

              <div class="add-product-content1">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                        @include('includes.form-error') 
                      <form id="geniusformdata" action="{{route('admin.global.vat.tax.update',$data->id)}}" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
                        

                        <div class="row">
                            <div class="col-lg-4">
                              <div class="left-area">
                                  <h4 class="heading">{{ __('Country Name') }} *</h4>
                              </div>
                            </div>
                            <div class="col-lg-7">
                              <input type="text" class="input-field" name="country_name" placeholder="{{ __('Country Name') }}" value="{{ $data->country_name }}">
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-4">
                              <div class="left-area">
                                  <h4 class="heading">{{ __('Country Code') }} *</h4>
                              </div>
                            </div>
                            <div class="col-lg-7">
                              <input type="text" class="input-field" name="country_code" placeholder="{{ __('Country Code') }}" value="{{ $data->country_code }}">
                            </div>
                          </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('percentage') }} *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="number" step="any" class="input-field" name="percentage" placeholder="{{ __('percentage') }}" value="{{ $data->percentage }}">
                          </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                              <div class="left-area">
                                  <h4 class="heading">{{ __('Status') }} *</h4>
                              </div>
                            </div>
                            <div class="col-lg-7">
                              <select name="active_status" id="" class="input-field">
                                <option value="active" {{ $data->active_status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $data->active_status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                              </select>
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