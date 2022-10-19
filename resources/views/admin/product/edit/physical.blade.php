@extends('layouts.admin')
@section('styles')

    <link href="{{asset('assets/admin/css/product.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/admin/css/jquery.Jcrop.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/admin/css/Jcrop-style.css')}}" rel="stylesheet"/>

@endsection
@section('content')

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading"> {{ __('Edit Product') }} <a class="add-btn float-right btn-sm mt-3" href="{{ url()->previous() }}"><i
                                class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.product.index') }}">{{ __('Products') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Physical Product') }}</a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Edit') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <form id="geniusform" action="{{route('admin.product.update',$data->id)}}" method="POST"
              enctype="multipart/form-data">
        {{csrf_field()}}
        <!-- Physical product (main content) starts -->
            <div class="row">
                <!-- Left side content starts -->
                <div class="col-lg-8">

                    <div class="add-product-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="product-description">
                                    <div class="body-area">

                                        <div class="gocover"
                                             style="background: url({{asset('assets/images/xloading.gif')}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>


                                    @include('includes.form-both')
                                    <!-- Edit product name and vendor name -->
                                        <div class="row">
                                            <!-- Edit product name -->
                                            <div class="col-lg-6">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Product Name') }}* </h4>
                                                    <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                                                </div>
                                                {{-- <input type="text" class="input-field"
                                                       placeholder="{{ __('Enter Product Name') }}" name="name"
                                                       required="" value="{{ $data->name }}"> --}}
                                                <textarea class="form-control"  name="name" id="" required>{{ $data->name }}</textarea>
                                            </div>
                                            <!-- Edit product slug -->
                                            <div class="col-lg-6">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Product Slug') }}* </h4>
                                                </div>
                                                <textarea class="form-control"  name="slug" id="" required>{{ $data->slug }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Vendor name') }}*</h4>
                                                </div>
                                                {{-- <select  name="user_id" required class="merchant_id_cro_brand">
                                                    <option value="">{{ __('Select Vendor') }}</option>
                                                    @foreach($merchants as $merchant)
                                                        <option value="{{ $merchant->user_id }}"
                                                            {{$merchant->user_id == $data->user_id ? "selected":""}} >{{ $merchant->shop_name }}</option>
                                                    @endforeach
                                                </select> --}}
                                                <input type="text" class="input-field" value="{{ $data->merchantShop->shop_name }}" readonly>
                                            </div>
                                            <!-- Edit sku -->
                                            <div class="col-lg-6">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Product Sku') }}* </h4>
                                                </div>

                                                <input type="text" class="input-field"
                                                       placeholder="{{ __('Enter Product Sku') }}" name="sku"
                                                        value="{{ $data->sku }}" readonly>
                                            </div>
                                        </div>

                                        <!-- Edit product sku and category starts -->
                                        <div class="row mb-0">
                                            
                                            <!-- Edit product category -->
                                            <div class="col-lg-6">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Category') }}*</h4>
                                                </div>

                                                <select id="cat" name="category_id" required="">
                                                    <option>{{ __('Select Category') }}</option>

                                                    @foreach($cats as $cat)
                                                        <option data-href="{{ route('admin-subcat-load',$cat->id) }}"
                                                                value="{{$cat->id}}" {{$cat->id == $data->category_id ? "selected":""}} >{{$cat->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <!-- Edit sub-category -->
                                            <div class="col-lg-6">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Sub Category') }}*</h4>
                                                </div>

                                                <select id="subcat" name="subcategory_id">
                                                    <option value="">{{ __('Select Sub Category') }}</option>
                                                    @if($data->subcategory_id == null)
                                                        @foreach($data->category->subs as $sub)
                                                            <option
                                                                data-href="{{ route('admin-childcat-load',$sub->id) }}"
                                                                value="{{$sub->id}}">{{$sub->name}}</option>
                                                        @endforeach
                                                    @else
                                                        @foreach($data->category->subs as $sub)
                                                            <option
                                                                data-href="{{ route('admin-childcat-load',$sub->id) }}"
                                                                value="{{$sub->id}}" {{$sub->id == $data->subcategory_id ? "selected":""}} >{{$sub->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Edit product sku and category ends -->

                                        <!-- Product sub-category and child-category starts -->
                                        <div class="row mb-0">
                                            
                                            <!-- Edit child-category -->
                                            {{-- <div class="col-lg-6">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Child Category') }}*</h4>
                                                </div>

                                                <select id="childcat"
                                                        name="childcategory_id" {{$data->subcategory_id == null ? "disabled":""}}>
                                                    <option value="">{{ __('Select Child Category') }}</option>
                                                    @if($data->subcategory_id != null)
                                                        @if($data->childcategory_id == null)
                                                            @foreach($data->subcategory->childs as $child)
                                                                <option value="{{$child->id}}">{{$child->name}}</option>
                                                            @endforeach
                                                        @else
                                                            @foreach($data->subcategory->childs as $child)
                                                                <option
                                                                    value="{{$child->id}} " {{$child->id == $data->childcategory_id ? "selected":""}}>{{$child->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                </select>
                                            </div> --}}
                                        </div>
                                        <!-- Product sub-category and child-category ends -->

                                        <!-- Edit allow product condition and estimated shipping time starts -->
                                        {{-- <div class="row mb-0">
                                            <!-- Product condition -->
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="checkbox-wrapper">
                                                            <input type="checkbox" name="product_condition_check"
                                                                   class="checkclick" id="conditionCheck" value="1"
                                                                {{ $data->product_condition != 0 ? "checked":"" }}>
                                                            <label
                                                                for="conditionCheck">{{ __('Allow Product Condition') }}</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="{{ $data->product_condition == 0 ? "showbox":"" }}">

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="left-area">
                                                                <h4 class="heading">{{ __('Product Condition') }}*</h4>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <select name="product_condition">
                                                                <option value="2" {{$data->product_condition == 2
													? "selected":""}}>{{ __('New') }}</option>
                                                                <option value="1" {{$data->product_condition == 1
													? "selected":""}}>{{ __('Used') }}</option>
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Estimated shipping time -->
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-12 mb-1">
                                                        <div class="left-area">

                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12">
                                                        <ul class="list">
                                                            <li>
                                                                <input class="checkclick1" name="shipping_time_check"
                                                                       type="checkbox" id="check1"
                                                                       value="1" {{$data->ship != null ? "checked":""}}>
                                                                <label
                                                                    for="check1">{{ __('Allow Estimated Shipping Time') }}</label>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                </div>

                                                <div class="{{ $data->ship != null ? "":"showbox" }}">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="left-area">
                                                                <h4 class="heading">{{ __('Product Estimated Shipping Time') }}
                                                                    * </h4>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <input type="text" class="input-field"
                                                                   placeholder="{{ __('Estimated Shipping Time') }}"
                                                                   name="ship"
                                                                   value="{{ $data->ship == null ? "" : $data->ship }}">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div> --}}
                                        <!-- Edit allow product condition and estimated shipping time ends -->


                                        <!-- Attributes of category starts -->
                                        @php
                                            $selectedAttrs = json_decode($data->attributes, true);
                                            // dd($selectedAttrs);
                                        @endphp


                                        {{-- <div id="catAttributes">
                                            @php
                                                $catAttributes = !empty($data->category->attributes) ? $data->category->attributes : '';
                                            @endphp
                                            @if (!empty($catAttributes))
                                                @foreach ($catAttributes as $catAttribute)
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="left-area">
                                                                <h4 class="heading">{{ $catAttribute->name }} *</h4>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            @php
                                                                $i = 0;
                                                            @endphp
                                                            @foreach ($catAttribute->attribute_options as $optionKey => $option)
                                                                @php
                                                                    $inName = $catAttribute->input_name;
                                                                    $checked = 0;
                                                                @endphp


                                                                <div class="row">
                                                                    <div class="col-lg-5">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input type="checkbox"
                                                                                   id="{{ $catAttribute->input_name }}{{$option->id}}"
                                                                                   name="{{ $catAttribute->input_name }}[]"
                                                                                   value="{{$option->name}}"
                                                                                   class="custom-control-input attr-checkbox"
                                                                                   @if (is_array($selectedAttrs) && array_key_exists($catAttribute->input_name,$selectedAttrs))
                                                                                   @if (is_array($selectedAttrs["$inName"]["values"]) && in_array($option->name, $selectedAttrs["$inName"]["values"]))
                                                                                   checked
                                                                                @php
                                                                                    $checked = 1;
                                                                                @endphp
                                                                                @endif
                                                                                @endif
                                                                            >
                                                                            <label class="custom-control-label"
                                                                                   for="{{ $catAttribute->input_name }}{{$option->id}}">{{ $option->name }}</label>
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="col-lg-7 {{ $catAttribute->price_status == 0 ? 'd-none' : '' }}">
                                                                        <div class="row">
                                                                            <div class="col-2">
                                                                                +
                                                                            </div>
                                                                            <div class="col-10">
                                                                                <div class="price-container">
                                                                                    <span
                                                                                        class="price-curr">€</span>
                                                                                    <input type="text"
                                                                                           class="input-field price-input"
                                                                                           id="{{ $catAttribute->input_name }}{{$option->id}}_price"
                                                                                           data-name="{{ $catAttribute->input_name }}_price[]"
                                                                                           placeholder="0.00 (Additional Price)"
                                                                                           value="{{ !empty($selectedAttrs["$inName"]['prices'][$i]) && $checked == 1 ? $selectedAttrs["$inName"]['prices'][$i] : '' }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                @php
                                                                    if ($checked == 1) {
                                                                    $i++;
                                                                    }
                                                                @endphp
                                                            @endforeach
                                                        </div>

                                                    </div>
                                                @endforeach
                                            @endif
                                        </div> --}}
                                        <!-- Attributes of category ends -->


                                        <!-- Attributes of sub-category starts -->
                                        {{-- <div id="subcatAttributes">
                                            @php
                                                $subAttributes = !empty($data->subcategory->attributes) ? $data->subcategory->attributes : '';
                                            @endphp
                                            @if (!empty($subAttributes))
                                                @foreach ($subAttributes as $subAttribute)
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="left-area">
                                                                <h4 class="heading">{{ $subAttribute->name }} *</h4>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            @php
                                                                $i = 0;
                                                            @endphp
                                                            @foreach ($subAttribute->attribute_options as $option)
                                                                @php
                                                                    $inName = $subAttribute->input_name;
                                                                    $checked = 0;
                                                                @endphp

                                                                <div class="row">
                                                                    <div class="col-lg-5">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input type="checkbox"
                                                                                   id="{{ $subAttribute->input_name }}{{$option->id}}"
                                                                                   name="{{ $subAttribute->input_name }}[]"
                                                                                   value="{{$option->name}}"
                                                                                   class="custom-control-input attr-checkbox"
                                                                                   @if (is_array($selectedAttrs) && array_key_exists($subAttribute->input_name,$selectedAttrs))
                                                                                   @php
                                                                                       $inName = $subAttribute->input_name;
                                                                                   @endphp
                                                                                   @if (is_array($selectedAttrs["$inName"]["values"]) && in_array($option->name, $selectedAttrs["$inName"]["values"]))
                                                                                   checked
                                                                                @php
                                                                                    $checked = 1;
                                                                                @endphp
                                                                                @endif
                                                                                @endif
                                                                            >
                                                                            <label class="custom-control-label"
                                                                                   for="{{ $subAttribute->input_name }}{{$option->id}}">{{ $option->name }}</label>
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="col-lg-7 {{ $subAttribute->price_status == 0 ? 'd-none' : '' }}">
                                                                        <div class="row">
                                                                            <div class="col-2">
                                                                                +
                                                                            </div>
                                                                            <div class="col-10">
                                                                                <div class="price-container">
                                                                                    <span
                                                                                        class="price-curr">€</span>
                                                                                    <input type="text"
                                                                                           class="input-field price-input"
                                                                                           id="{{ $subAttribute->input_name }}{{$option->id}}_price"
                                                                                           data-name="{{ $subAttribute->input_name }}_price[]"
                                                                                           placeholder="0.00 (Additional Price)"
                                                                                           value="{{ !empty($selectedAttrs["$inName"]['prices'][$i]) && $checked == 1 ? $selectedAttrs["$inName"]['prices'][$i] : '' }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @php
                                                                    if ($checked == 1) {
                                                                        $i++;
                                                                    }
                                                                @endphp
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div> --}}
                                        <!-- Attributes of sub-category ends -->


                                        <!-- Attributes of childcategory starts -->
                                        {{-- <div id="childcatAttributes">
                                            @php
                                                $childAttributes = !empty($data->childcategory->attributes) ? $data->childcategory->attributes : '';
                                            @endphp
                                            @if (!empty($childAttributes))
                                                @foreach ($childAttributes as $childAttribute)
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="left-area">
                                                                <h4 class="heading">{{ $childAttribute->name }} *</h4>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            @php
                                                                $i = 0;
                                                            @endphp
                                                            @foreach ($childAttribute->attribute_options as $optionKey => $option)
                                                                @php
                                                                    $inName = $childAttribute->input_name;
                                                                    $checked = 0;
                                                                @endphp
                                                                <div class="row">
                                                                    <div class="col-lg-5">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input type="checkbox"
                                                                                   id="{{ $childAttribute->input_name }}{{$option->id}}"
                                                                                   name="{{ $childAttribute->input_name }}[]"
                                                                                   value="{{$option->name}}"
                                                                                   class="custom-control-input attr-checkbox"
                                                                                   @if (is_array($selectedAttrs) && array_key_exists($childAttribute->input_name,$selectedAttrs))
                                                                                   @php
                                                                                       $inName = $childAttribute->input_name;
                                                                                   @endphp
                                                                                   @if (is_array($selectedAttrs["$inName"]["values"]) && in_array($option->name, $selectedAttrs["$inName"]["values"]))
                                                                                   checked
                                                                                @php
                                                                                    $checked = 1;
                                                                                @endphp
                                                                                @endif
                                                                                @endif
                                                                            >
                                                                            <label class="custom-control-label"
                                                                                   for="{{ $childAttribute->input_name }}{{$option->id}}">{{ $option->name }}</label>
                                                                        </div>
                                                                    </div>


                                                                    <div
                                                                        class="col-lg-7 {{ $childAttribute->price_status == 0 ? 'd-none' : '' }}">
                                                                        <div class="row">
                                                                            <div class="col-2">
                                                                                +
                                                                            </div>
                                                                            <div class="col-10">
                                                                                <div class="price-container">
                                                                                    <span
                                                                                        class="price-curr">€</span>
                                                                                    <input type="text"
                                                                                           class="input-field price-input"
                                                                                           id="{{ $childAttribute->input_name }}{{$option->id}}_price"
                                                                                           data-name="{{ $childAttribute->input_name }}_price[]"
                                                                                           placeholder="0.00 (Additional Price)"
                                                                                           value="{{ !empty($selectedAttrs["$inName"]['prices'][$i]) && $checked == 1 ? $selectedAttrs["$inName"]['prices'][$i] : '' }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @php
                                                                    if ($checked == 1) {
                                                                        $i++;
                                                                    }
                                                                @endphp
                                                            @endforeach
                                                        </div>

                                                    </div>
                                                @endforeach
                                            @endif
                                        </div> --}}
                                        <!-- Attributes of childcategory ends -->


                                        <!-- Edit allow product sizes starts -->
                                        {{-- <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">

                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <ul class="list">
                                                    <li>
                                                        <input name="size_check" type="checkbox" id="size-check"
                                                               value="1" {{ !empty($data->size) ? "checked":"" }}>
                                                        <label for="size-check">{{ __('Allow Product Sizes') }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="{{ !empty($data->size) ? "":"showbox" }}" id="size-display">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="product-size-details" id="size-section">
                                                        @if(!empty($data->size))
                                                            @foreach($data->size as $key => $data1)
                                                                <div class="size-area">
                                                                    <span class="remove size-remove"><i
                                                                            class="fas fa-times"></i></span>
                                                                    <div class="row">
                                                                        <div class="col-md-4 col-sm-6">
                                                                            <label>
                                                                                {{ __('Size Name') }} :
                                                                                <span>
																			{{ __('(eg. S,M,L,XL,XXL,3XL,4XL)') }}
																		</span>
                                                                            </label>
                                                                            <input type="text" name="size[]"
                                                                                   class="input-field"
                                                                                   placeholder="Size Name"
                                                                                   value="{{ $data->size[$key] }}">
                                                                        </div>
                                                                        <div class="col-md-4 col-sm-6">
                                                                            <label>
                                                                                {{ __('Size Qty') }} :
                                                                                <span>
																				{{ __('(Number of quantity of this size)') }}
																			</span>
                                                                            </label>
                                                                            <input type="number" name="size_qty[]"
                                                                                   class="input-field"
                                                                                   placeholder="Size Qty" min="1"
                                                                                   value="{{ $data->size_qty[$key] > 0 ? $data->size_qty[$key] : 1  }}" step="any">
                                                                        </div>
                                                                        <div class="col-md-4 col-sm-6">
                                                                            <label>
                                                                                {{ __('Size Price') }} :
                                                                                <span>
																				{{ __('(This price will be added with base price)') }}
																			</span>
                                                                            </label>
                                                                            <input type="number" name="size_price[]"
                                                                                   class="input-field"
                                                                                   placeholder="{{ __('Size Price') }}"
                                                                                   min="0"
                                                                                   value="{{round($data->size_price[$key] , 2)}}" step="any">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="size-area">
                                                                <span class="remove size-remove"><i
                                                                        class="fas fa-times"></i></span>
                                                                <div class="row">
                                                                    <div class="col-md-4 col-sm-6">
                                                                        <label>
                                                                            {{ __('Size Name') }} :
                                                                            <span>
																			{{ __('(eg. S,M,L,XL,XXL,3XL,4XL)') }}
																		</span>
                                                                        </label>
                                                                        <input type="text" name="size[]"
                                                                               class="input-field"
                                                                               placeholder="Size Name">
                                                                    </div>
                                                                    <div class="col-md-4 col-sm-6">
                                                                        <label>
                                                                            {{ __('Size Qty') }} :
                                                                            <span>
																				{{ __('(Number of quantity of this size)') }}
																			</span>
                                                                        </label>
                                                                        <input type="number" name="size_qty[]"
                                                                               class="input-field"
                                                                               placeholder="Size Qty" value="1" min="1" step="any">
                                                                    </div>
                                                                    <div class="col-md-4 col-sm-6">
                                                                        <label>
                                                                            {{ __('Size Price') }} :
                                                                            <span>
																				{{ __('(This price will be added with base price)') }}
																			</span>
                                                                        </label>
                                                                        <input type="number" name="size_price[]"
                                                                               class="input-field"
                                                                               placeholder="Size Price" value="0"
                                                                               min="0" step="any">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <a href="javascript:" id="size-btn" class="add-more"><i
                                                            class="fas fa-plus"></i>{{ __('Add More Size') }} </a>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <!-- Edit allow product sizes ends -->

                                        <!-- Edit allow product colors starts -->
                                        {{-- <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">

                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <ul class="list">
                                                    <li>
                                                        <input class="checkclick1" name="color_check" type="checkbox"
                                                               id="check3"
                                                               value="1" {{ !empty($data->color) ? "checked":"" }}>
                                                        <label for="check3">{{ __('Allow Product Colors') }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div> --}}


                                        {{-- <div class="{{ !empty($data->color) ? "":"showbox" }}">

                                            <div class="row">
                                                @if(!empty($data->color))
                                                    <div class="col-lg-12">
                                                        <div class="left-area">
                                                            <h4 class="heading">
                                                                {{ __('Product Colors') }}*
                                                            </h4>
                                                            <p class="sub-heading">
                                                                {{ __('(Choose Your Favorite Colors)') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="select-input-color" id="color-section">
                                                            @foreach($data->color as $key => $data1)
                                                                <div class="color-area">
                                                                    <span class="remove color-remove"><i
                                                                            class="fas fa-times"></i></span>
                                                                    <div class="input-group colorpicker-component cp">
                                                                        <input type="text" name="color[]"
                                                                               value="{{ $data->color[$key] }}"
                                                                               class="input-field cp"/>
                                                                        <span class="input-group-addon"><i></i></span>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <a href="javascript:" id="color-btn"
                                                           class="add-more mt-4 mb-3"><i
                                                                class="fas fa-plus"></i>{{ __('Add More Color') }} </a>
                                                    </div>
                                                @else
                                                    <div class="col-lg-12">
                                                        <div class="left-area">
                                                            <h4 class="heading">
                                                                {{ __('Product Colors') }}*
                                                            </h4>
                                                            <p class="sub-heading">
                                                                {{ __('(Choose Your Favorite Colors)') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="select-input-color" id="color-section">
                                                            <div class="color-area">
                                                                <span class="remove color-remove"><i
                                                                        class="fas fa-times"></i></span>
                                                                <div class="input-group colorpicker-component cp">
                                                                    <input type="text" name="color[]" value="#000000"
                                                                           class="input-field cp"/>
                                                                    <span class="input-group-addon"><i></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <a href="javascript:" id="color-btn"
                                                           class="add-more mt-4 mb-3"><i
                                                                class="fas fa-plus"></i>{{ __('Add More Color') }} </a>
                                                    </div>


                                                @endif
                                            </div>

                                        </div> --}}
                                        <!-- Edit allow product color ends -->

                                        <!-- Edit allow product whole sell starts -->
                                        {{-- <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">

                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <ul class="list">
                                                    <li>
                                                        <input class="checkclick1" name="whole_check" type="checkbox"
                                                               id="whole_check"
                                                               value="1" {{ !empty($data->whole_sell_qty) ? "checked":"" }}>
                                                        <label
                                                            for="whole_check">{{ __('Allow Product Whole Sell') }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="{{ !empty($data->whole_sell_qty) ? "":"showbox" }}">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="left-area">

                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="featured-keyword-area">
                                                        <div class="feature-tag-top-filds" id="whole-section">
                                                            @if(!empty($data->whole_sell_qty))

                                                                @foreach($data->whole_sell_qty as $key => $data1)

                                                                    <div class="feature-area">
                                                                        <span class="remove whole-remove"><i
                                                                                class="fas fa-times"></i></span>
                                                                        <div class="row">
                                                                            <div class="col-lg-6">
                                                                                <input type="number"
                                                                                       name="whole_sell_qty[]"
                                                                                       class="input-field"
                                                                                       placeholder="{{ __('Enter Quantity') }}"
                                                                                       min="0"
                                                                                       value="{{ $data->whole_sell_qty[$key] }}"
                                                                                       required="" step="any">
                                                                            </div>

                                                                            <div class="col-lg-6">
                                                                                <input type="number"
                                                                                       name="whole_sell_discount[]"
                                                                                       class="input-field"
                                                                                       placeholder="{{ __('Enter Discount Percentage') }}"
                                                                                       min="0"
                                                                                       value="{{ $data->whole_sell_discount[$key] }}"
                                                                                       required="" step="any">
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                @endforeach
                                                            @else


                                                                <div class="feature-area">
                                                                    <span class="remove whole-remove"><i
                                                                            class="fas fa-times"></i></span>
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <input step="any" type="number" name="whole_sell_qty[]"
                                                                                   class="input-field"
                                                                                   placeholder="{{ __('Enter Quantity') }}"
                                                                                   min="0">
                                                                        </div>

                                                                        <div class="col-lg-6">
                                                                            <input step="any" type="number"
                                                                                   name="whole_sell_discount[]"
                                                                                   class="input-field"
                                                                                   placeholder="{{ __('Enter Discount Percentage') }}"
                                                                                   min="0"/>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            @endif
                                                        </div>

                                                        <a href="javascript:" id="whole-btn" class="add-fild-btn"><i
                                                                class="icofont-plus"></i> {{ __('Add More Field') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <!-- Edit allow product whole sell ends -->

                                        <!-- Edit stock and measurement starts -->

                                        {{-- <div class="{{ !empty($data->size) ? "showbox":"" }}" id="stckprod">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Product Stock') }}*</h4>
                                                        <p class="sub-heading">{{ __('(Leave Empty will Show Always Available)') }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <input name="stock" type="number" class="input-field"
                                                           placeholder="e.g 20" value="{{ $data->stock }}" step="any">
                                                    <div class="checkbox-wrapper">
                                                        <input type="checkbox" name="measure_check" class="checkclick1"
                                                               id="allowProductMeasurement"
                                                               value="1" {{ $data->measure == null ? '' : 'checked' }}>
                                                        <label
                                                            for="allowProductMeasurement">{{ __('Allow Product Measurement') }}</label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div> --}}

                                   
                                        <input type="number" name="measure_check" value="{{ $data->measure == null ? 0 : 1 }}" hidden>
                                        @if ($data->measure != null)
                                            
                                        <div class="{{ $data->measure == null ? 'showbox' : '' }}">

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Product Measurement') }}</h4>
                                                    </div>
                                                </div>
                                                @php
                                                    $product_measure = json_decode(json_decode(json_encode($data->measure)))
                                                @endphp
                                                <div class="col-lg-12">
                                                    <div class="row m-1 p-2 border">
                                                        <div class="col-lg-6">
                                                            <div class="left-area">
                                                                <h4 class="heading">{{ __('Weight') }}</h4>
                                                            </div>
                                                            <input name="measure_weight" type="text"
                                                                   class="input-field" placeholder="Enter Unit"
                                                                   value="{{$product_measure->weight}}">
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="left-area">
                                                                <h4 class="heading">{{ __('Length') }}</h4>
                                                            </div>
                                                            <input name="measure_length" type="text"
                                                                   class="input-field" placeholder="Enter Unit"
                                                                   value="{{$product_measure->length}}">
                                                        </div>
                                                        <div class="col-lg-6 mt-2">
                                                            <div class="left-area">
                                                                <h4 class="heading">{{ __('Width') }}</h4>
                                                            </div>
                                                            <input name="measure_width" type="text"
                                                                   class="input-field" placeholder="Enter Unit"
                                                                   value="{{$product_measure->width}}">
                                                        </div>
                                                        <div class="col-lg-6 mt-2">
                                                            <div class="left-area">
                                                                <h4 class="heading">{{ __('Height') }}</h4>
                                                            </div>
                                                            <input name="measure_height" type="text"
                                                                   class="input-field" placeholder="Enter Unit"
                                                                   value="{{$product_measure->height}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-lg-6">
                                                    <select id="product_measure">
                                                        <option
                                                            value="" {{$data->measure == null ? 'selected':''}}>{{ __('None') }}</option>
                                                        <option
                                                            value="Gram" {{$data->measure == 'Gram' ? 'selected':''}}>{{ __('Gram') }}</option>
                                                        <option
                                                            value="Kilogram" {{$data->measure == 'Kilogram' ? 'selected':''}}>{{ __('Kilogram') }}</option>
                                                        <option
                                                            value="Litre" {{$data->measure == 'Litre' ? 'selected':''}}>{{ __('Litre') }}</option>
                                                        <option
                                                            value="Pound" {{$data->measure == 'Pound' ? 'selected':''}}>{{ __('Pound') }}</option>
                                                        <option
                                                            value="Custom" {{ in_array($data->measure,explode(',', 'Gram,Kilogram,Litre,Pound')) ? '' : 'selected' }}>{{ __('Custom') }}</option>
                                                    </select>
                                                </div>

                                                <div class="col-lg-6 {{ in_array($data->measure,explode(',', 'Gram,Kilogram,Litre,Pound')) ? 'hidden' : '' }}"
                                                    id="measure">
                                                    <input name="measure" type="text" id="measurement"
                                                           class="input-field" placeholder="Enter Unit"
                                                           value="{{$data->measure}}">
                                                </div> --}}
                                            </div>

                                        </div>
                                        @endif
                                        <!-- Edit product stock and measurement ends -->

                                        <h6>Product Variation Section</h6>
                                        <div class="row mb-0">
                                            <style>
                                                td img {
                                                        max-height: 100%;
                                                        max-width: 100%;
                                                    }
                                            </style>
                                            <div class="col-12">
                                                @foreach ($data->productVariations as $item)
                                                    <table class="mt-2" style="border-top: 1px solid #726f6f; border-bottom: 1px solid e3d6d6; width:100%;" id="variant_{{ $item->id }}"> 
                                                        <tr>
                                                            <td style="width: 48%">
                                                                <div class="left-area">
                                                                    <h4 class="heading">{{ __('Variation Id') }}</h4>
                                                                </div>
                                                                <input type="text" name="variation_id[]" value="{{$item->id}}" class="form-control" readonly> 
                                                                <div class="left-area">
                                                                    <h4 class="heading">{{ __('Variation SKU') }}</h4>
                                                                </div>
                                                                <input type="text" name="variation_sku[]" value="{{$item->variation_sku}}" class="form-control" readonly>
                                                                <div class="left-area">
                                                                    <h4 class="heading">{{ __('Variation Current Price') }}</h4>
                                                                </div>
                                                                <input type="text" name="variation_price[]" value="{{$item->variation_price}}" class="form-control">  
                                                                <div class="left-area">
                                                                    <h4 class="heading">{{ __('Variation Previous Price') }}</h4>
                                                                </div>
                                                                <input type="text" name="variation_previous_price[]" value="{{$item->variation_previous_price}}" class="form-control"> 
                                                                
                                                                <div class="left-area">
                                                                    <h4 class="heading">{{ __('Variation Images') }}</h4>
                                                                </div>
                                                                <div style="width: 100%;">
                                                                    <img src="{{$item->variation_photo}}" alt="" style="width:100%;" height="100">
                                                                </div> 
                                                            </td>

                                                            <td style=" width:3%;border-right: 1px solid #e3d6d6"></td>
                                                            <td style="width: 50%;">
                                                                <table style="width:95%;margin-left:5%;">
                                                                    {{-- 
                                                                        <tr>
                                                                            <td>
                                                                                <div class="left-area">
                                                                                    <span >{{ __('Variation Price') }}</span>
                                                                                </div>
                                                                                <input type="text" value="{{$item->variation_price}}" class="form-control"> 
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="left-area">
                                                                                    <span >{{ __('Variation Sale Price') }}</span>
                                                                                </div>
                                                                                <input type="text" value="{{$item->variation_sale_price}}" class="form-control"> 
                                                                            </td>
                                                                        </tr> 
                                                                    --}}
                                                                    <tr>
                                                                        <td>
                                                                            <div class="left-area">
                                                                                <h4 class="heading">{{ __('Variation Stock Quantity') }}</h4>
                                                                            </div>
                                                                            <input type="text" name="variation_stock_quantity[]" value="{{$item->variation_stock_quantity}}" class="form-control"> 
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="left-area">
                                                                                <h4 class="heading">{{ __('Variation Stock Status') }}</h4>
                                                                            </div>
                                                                            <input type="text" name="variation_stock_status[]" value="{{$item->variation_stock_status}}" class="form-control"> 
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="py-4">
                                                                            <div class="left-area">
                                                                                
                                                                                <button class="btn btn-info edit_variation w-100" data-value="#edit_variation_{{$item->id}}"  data-bs-toggle="modal" data-bs-target="#edit_variation_{{$item->id}}"><span >{{ __('Variation Attribute') }}</span> <i class="fas fa-edit"></i></button>
                                                                                <div class="modal" id="edit_variation_{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="edit_variation_{{$item->id}}" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header bg-dark">
                                                                                                <h5 class="modal-title text-white" id="exampleModalCenterTitle">{{ __('Edit Variation Atribute') }}</h5>
                                                                                                <button type="button" class="close close_variation text-white" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">×</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body bg-light">
                                                                                            <div class="card">
                                                                                                <div class="crard-body">
                                                                                                    <div class="row px-4 pt-4">
                                                                                                        @foreach (json_decode(json_decode(json_encode($item->attributes))) as $item1)
                                                                                                            <div class="col-md-6">
                                                                                                            <div class="left-area">
                                                                                                                <h4 class="heading">{{ $item1->name }}*</h4>
                                                                                                                <input type="text" name="atr_name_{{ $item->id }}[]" value="{{ $item1->name }}" hidden>
                                                                                                            </div>
                                                                                                                <input type="text" name="atr_value_{{ $item->id }}[]" value="{{ $item1->value }}" class="form-control">
                                                                                                            </div>
                                                                                                        @endforeach
                                                                                                        
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="text-center pt-3">
                                                                                                    <button class="btn btn-success" id="variant_save" data-id="{{ $item->id }}">Save</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            {{-- <input type="text" value="{{$item->attributes}}" class="form-control">  --}}
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <td class="py-3">
                                                                            <div class="left-area">
                                                                                
                                                                                <button class="btn btn-secondary edit_dimension w-100" data-value="#edit_dimension_{{$item->id}}"  ><span >{{ __('Variation Dimension') }}</span> <i class="fas fa-edit"></i></button>
                                                                                <div class="modal" id="edit_dimension_{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="edit_dimension_{{$item->id}}" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header bg-dark">
                                                                                                <h5 class="modal-title text-white" id="exampleModalCenterTitle">{{ __('Edit Variation Dimension') }}</h5>
                                                                                                <button type="button" class="close close_variation text-white" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">×</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <div class="modal-body bg-light">
                                                                                            <div class="card">
                                                                                                <div class="crard-body">
                                                                                                    <div class="row px-4 pt-4">
                                                                                                        @if ($item->variation_dimension != null)
                                                                                                            @php
                                                                                                                $variant_dimension = json_decode(json_decode(json_encode($item->variation_dimension)))
                                                                                                            @endphp
                                                                                                            <div class="col-md-6">
                                                                                                                <div class="left-area">
                                                                                                                    <h4 class="heading">{{ __('Length') }}*</h4>
                                                                                                                </div>
                                                                                                                <input type="text" name="dimension_length_{{ $item->id }}" value="{{ $variant_dimension->length }}" class="form-control">
                                                                                                            </div>
                                                                                                            <div class="col-md-6">
                                                                                                                <div class="left-area">
                                                                                                                    <h4 class="heading">{{ __('Width') }}*</h4>
                                                                                                                </div>
                                                                                                                <input type="text" name="dimension_width_{{ $item->id }}" value="{{ $variant_dimension->width }}" class="form-control">
                                                                                                            </div>
                                                                                                            <div class="col-md-6">
                                                                                                                <div class="left-area">
                                                                                                                    <h4 class="heading">{{ __('Height') }}*</h4>
                                                                                                                </div>
                                                                                                                <input type="text" name="dimension_height_{{ $item->id }}" value="{{ $variant_dimension->height }}" class="form-control">
                                                                                                            </div>
                                                                                                        @endif
                                                                                                    </div>
                                                                                                    
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="text-center pt-3">
                                                                                                    <button class="btn btn-success" id="dimension_save" data-id="{{ $item->id }}">Save</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            {{-- <input type="text" value="{{$item->variation_dimension}}" class="form-control">  --}}
                                                                            
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="left-area">
                                                                                <h4 class="heading">{{ __('Upload Variation Images') }}</h4>
                                                                            </div>
                                                                            <div style="width: 100%;100px">
                                                                                <input type="hidden" name="vids[]" value="{{$item->id}}">
                                                                                <input type="file" name="v_p_{{$item->id}}">
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table> 
                                                            </td>
                                                        </tr>
                                                            {{-- 
                                                                @if (count($data->productVariations) > 1)
                                                                    <tr>
                                                                        <td colspan="3" class="text-right py-3">
                                                                            <button class="btn btn-danger btn-sm pull-right variation_remove" data-id="{{ $item->id }}"><i class="fas fa-trash"></i></button>
                                                                        </td>
                                                                    </tr>
                                                                @endif 
                                                            --}}
                                                    </table>
                                                @endforeach
                                            </div>
                                        </div>
                                        <!------product variation section end--->
                                        <br/>


                                        <!-- Edit product description and return/buy policy starts -->

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <ul class="nav nav-tabs">
                                                    <!-- Product description -->
                                                    <li class="nav-item">
                                                        <a href="#product_description" class="nav-link active"
                                                           role="tab" data-toggle="tab"><b>Product Description</b></a>
                                                    </li>
                                                    <!-- Product Buy/Return Policy -->
                                                    <li class="nav-item">
                                                        <a href="#product_policy" class="nav-link" role="tab"
                                                           data-toggle="tab"><b>Product Buy/Return Policy</b></a>
                                                    </li>
                                                </ul>

                                                <div class="tab-content">
                                                    <!-- Product description -->
                                                    <div role="tabpanel" class="tab-pane active"
                                                         id="product_description">
                                                        <p style="text-align:right;color:blue;">Add description</p>
                                                        <div class="text-editor">
                                                            <textarea id="article-ckeditor"
                                                                      name="details">{!!$data->details!!}</textarea>
                                                        </div>
                                                    </div>
                                                    <!-- Product Buy/Return Policy -->
                                                    <div role="tabpanel" class="tab-pane" id="product_policy">
                                                        <p style="text-align:right;color:blue;">Add policy</p>
                                                        <div class="text-editor">
                                                            <textarea id="article-ckeditor-two"
                                                                      name="policy">{!! $data->policy !!}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <!-- Edit product description and return/buy policy ends-->

                                        <!-- Edit allow product SEO check starts -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="checkbox-wrapper">
                                                    <input type="checkbox" name="seo_check" value="1" class="checkclick"
                                                           id="allowProductSEO" {{ ($data->meta_tag != null || strip_tags($data->meta_description) != null) ? 'checked':'' }}>
                                                    <label for="allowProductSEO">{{ __('Allow Product SEO') }}</label>
                                                </div>
                                            </div>
                                        </div>


                                        <div
                                            class="{{ ($data->meta_tag == null && strip_tags($data->meta_description) == null) ? "showbox":"" }}">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Meta Tags') }} *</h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <ul id="metatags" class="myTags">
                                                        @if(!empty($data->meta_tag))
                                                            @foreach ($data->meta_tag as $element)
                                                                <li>{{  $element }}</li>
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="left-area">
                                                        <h4 class="heading">
                                                            {{ __('Meta Description') }} *
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="text-editor">
                                                        <textarea name="meta_description" class="input-field"
                                                                  placeholder="{{ __('Details') }}">{{ $data->meta_description }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Edit allow product SEO check ends -->


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Right side content starts -->
                <div class="col-lg-4">

                    <div class="add-product-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="product-description">
                                    <div class="body-area">


                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Feature Image') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">

                                                <div class="panel panel-body">
                                                    <div style="padding:2px;border:1px solid #d6d6d7;width:100%;">
                                                        <img src="{{$data->photo}}"  style="padding:4px !important;margin-bottom:5px;width:100%;"  height="200px" alt=""><br/>
                                                        <input type="file" name="product_photo" style="margin-top:5px " accept="image/*"> 
                                                    </div>
                                                    {{-- <div class="span4 cropme text-center" id="landscape"
                                                         style="width: 100%; height: 285px; border: 1px dashed #ddd; background: #f1f1f1;">
                                                        <a href="javascript:" id="crop-image"
                                                           class="d-inline-block mybtn1">
                                                            <i class="icofont-upload-alt"></i> {{ __('Upload Image Here') }}
                                                        </a>
                                                    </div> --}}
                                                </div>


                                            </div>
                                        </div>

                                        {{-- <input type="hidden" id="feature_photo" name="photo" value="{{ $data->photo }}"
                                               accept="image/*"> --}}

                                        {{-- <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">
                                                        {{ __('Product Gallery Images') }} *
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <a href="javascript" class="set-gallery" data-toggle="modal"
                                                   data-target="#setgallery">
                                                    <input type="hidden" value="{{$data->id}}">
                                                    <i class="icofont-plus"></i> {{ __('Set Gallery') }}
                                                </a>
                                            </div>
                                        </div> --}}

                                        <!-- Edit product current price -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">
                                                        {{ __('Product Current Price') }}*
                                                    </h4>
                                                    <p class="sub-heading">
                                                        ({{ __('In EURO') }})
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input name="price" type="number" class="input-field"
                                                       placeholder="e.g 20" step="0.01" min="0"
                                                       value="{{round($data->price , 2)}}" required="">
                                            </div>
                                        </div>
                                        <!-- Edit product previous price -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Product Previous Price') }}*</h4>
                                                    <p class="sub-heading">{{ __('(Optional)') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input name="previous_price" step="0.01" type="number"
                                                       class="input-field" placeholder="e.g 20"
                                                       value="{{round($data->previous_price , 2)}}"
                                                       min="0">
                                            </div>
                                        </div>

                                        <!-- Edit product commission -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Product Commission') }}*</h4>
                                                    <p class="sub-heading">({{ __('In EURO') }})</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input name="product_commission" step="0.01" type="number"
                                                       class="input-field"
                                                       value="{{round($data->commission , 2)}}" min="0">
                                            </div>
                                        </div>

                                        <!-- Product Shipping cost -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">
                                                        {{ __('Shipping Cost') }}*
                                                    </h4>
                                                    <p class="sub-heading">
                                                        ({{ __('In EURO') }})
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input value="{{ round($data->shipping_cost , 2) }}" name="shipping_cost" type="number" class="input-field"
                                                    placeholder="{{ __('e.g 20') }}" step="0.01" required="" min="0">
                                            </div>
                                        </div>

                                        <!-- Youtube video url -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Youtube Video URL') }}*</h4>
                                                    <p class="sub-heading">{{ __('(Optional)') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input name="youtube" type="text" class="input-field"
                                                       value="{{ $data->youtube }}"
                                                       placeholder="{{ __('Enter Youtube Video URL') }}">
                                            </div>
                                        </div>


                                        <!-- Edit feature tags -->
                                        {{-- <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">

                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="featured-keyword-area">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Feature Tags') }}</h4>
                                                    </div>
                                                    <div class="feature-tag-top-filds" id="feature-section">
                                                        @if(!empty($data->features))
                                                            @foreach($data->features as $key => $data1)
                                                                <div class="feature-area">
                                                                    <span class="remove feature-remove"><i
                                                                            class="fas fa-times"></i></span>
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <input type="text" name="features[]"
                                                                                   class="input-field"
                                                                                   placeholder="{{ __('Enter Your Keyword') }}"
                                                                                   value="{{ $data->features[$key] }}">
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <div class="input-group colorpicker-component cp">
                                                                                <input type="text" name="colors[]"
                                                                                       value="{{ $data->colors[$key] }}"
                                                                                       class="input-field cp"/>
                                                                                <span class="input-group-addon"><i></i></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="feature-area">
                                                                <span class="remove feature-remove"><i
                                                                        class="fas fa-times"></i></span>
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <input type="text" name="features[]"
                                                                               class="input-field"
                                                                               placeholder="{{ __('Enter Your Keyword') }}">
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="input-group colorpicker-component cp">
                                                                            <input type="text" name="colors[]"
                                                                                   value="#000000"
                                                                                   class="input-field cp"/>
                                                                            <span
                                                                                class="input-group-addon"><i></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <a href="javascript:" id="feature-btn" class="add-fild-btn"><i
                                                            class="icofont-plus"></i> {{ __('Add More Field') }}</a>
                                                </div>
                                            </div>
                                        </div> --}}


                                        
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">

                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="featured-keyword-area">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Tags') }}*</h4>
                                                    </div>
                                                    <div class="feature-tag-top-filds" id="tag-section">
                                                        <div class="feature-area">
                                                            <span class="remove tag-remove"><i class="fas fa-times"></i></span>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <select id="tags" class="form-control" multiple="multiple" name="tags[]">
                                                                        @foreach ( ($data->tags ? $data->tags : [])  as $item)
                                                                            <option value="{{$item}}" selected>{{$item}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <a href="javascript:;" id="tag" class="add-fild-btn"><i
                                                            class="icofont-plus"></i> {{ __('Add More Tag') }}</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Brand') }} *</h4>
                                                </div>
                                            </div>
                                            <input type="hidden" value="{{$data->brand_id}}" class="brandSelectedId" />
                                            <div class="col-lg-12">
                                                <select name="brand_id" required="" id="brand_id" class="brand_id">
                                                    <option value="">{{ __('Select Brand') }}</option>
                                                     @foreach($brands as $brand)
                                                    <option {{$data->brand_id == $brand->id ? 'selected' :''}} value="{{ $brand->id }}">{{$brand->name}}</option>
                                                    @endforeach 
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Save -->
                                        <div class="row">
                                            <div class="col-lg-12 text-center">
                                                <button class="addProductSubmit-btn"
                                                        type="submit">{{ __('Update') }}</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <div class="modal fade" id="setgallery" tabindex="-1" role="dialog" aria-labelledby="setgallery" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{ __('Image Gallery') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="top-area">
                        <div class="row">
                            <div class="col-sm-6 text-right">
                                <div class="upload-img-btn">
                                    <form method="POST" enctype="multipart/form-data" id="form-gallery">
                                        {{ csrf_field() }}
                                        <input type="hidden" id="pid" name="product_id" value="">
                                        <input type="file" name="gallery[]" class="hidden" id="uploadgallery"
                                               accept="image/*" multiple>
                                        <label for="image-upload" id="prod_gallery"><i
                                                class="icofont-upload-alt"></i>{{ __('Upload File') }}</label>
                                    </form>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <a href="javascript:" class="upload-done" data-dismiss="modal"> <i
                                        class="fas fa-check"></i> {{ __('Done') }}</a>
                            </div>
                            <div class="col-sm-12 text-center">(
                                <small>{{ __('You can upload multiple Images.') }}</small> )
                            </div>
                        </div>
                    </div>
                    <div class="gallery-images">
                        <div class="selected-image">
                            <div class="row">


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" value="{{route('admin.get.brands.merchant')}}"  class="getBrandsByMerchant"/>
@endsection

@push('scripts')
<!-- CKEDITOR  -->
<script src="{{asset('/assets/ckeditor/ckeditor.js')}}"></script>
<script>
    CKEDITOR.replace( 'details',{
    filebrowserUploadUrl: "{{route('product.create.Ckeditor.upload.image',['_token' => csrf_token()])}}",
    filebrowserUploadMethod: 'form',
    } );
</script>
<script>
    CKEDITOR.replace( 'policy',{
    filebrowserUploadUrl: "{{route('product.create.Ckeditor.upload.image',['_token' => csrf_token()])}}",
    filebrowserUploadMethod: 'form',
    } );
</script>

    <script type="text/javascript">
        // Gallery Section Update
        $(document).on("click", ".set-gallery", function () {
            var pid = $(this).find('input[type=hidden]').val();
            $('#pid').val(pid);
            $('.selected-image .row').html('');
            $.ajax({
                type: "GET",
                url: "{{ route('admin-gallery-show') }}",
                data: {id: pid},
                success: function (data) {
                    if (data[0] == 0) {
                        $('.selected-image .row').addClass('justify-content-center');
                        $('.selected-image .row').html('<h3>{{ __('No Images Found.') }}</h3>');
                    } else {
                        $('.selected-image .row').removeClass('justify-content-center');
                        $('.selected-image .row h3').remove();
                        var arr = $.map(data[1], function (el) {
                            return el
                        });

                        for (var k in arr) {
                            $('.selected-image .row').append('<div class="col-sm-6">' +
                                '<div class="img gallery-img">' +
                                '<span class="remove-img"><i class="fas fa-times"></i>' +
                                '<input type="hidden" value="' + arr[k]['id'] + '">' +
                                '</span>' +
                                '<a href="'+ arr[k]['photo'] + '" target="_blank">' +
                                '<img src="'+ arr[k]['photo'] + '" alt="gallery image">' +
                                '</a>' +
                                '</div>' +
                                '</div>');
                                // + '{{asset('storage/galleries').'/'}}' +
                        }
                    }

                }
            });
        });


        $(document).on('click', '.remove-img', function () {
            var id = $(this).find('input[type=hidden]').val();
            $(this).parent().parent().remove();
            $.ajax({
                type: "GET",
                url: "{{ route('admin-gallery-delete') }}",
                data: {id: id}
            });
        });

        // $(document).on('click', '#prod_gallery', function () {
        //     $('#uploadgallery').click();
        // });

        $(document).on('click', '.edit_variation', function (e) {
            e.preventDefault();
            var id = $(this).data('value');
            $('.modal').hide();
            $(id).show();
        });
        
        $(document).on('click', '.edit_dimension', function (e) {
            e.preventDefault();
            var id = $(this).data('value');
            $('.modal').hide();
            $(id).show();
        });
        $(document).on('click', '.close_variation', function (e) {
            // e.preventDefault();
            $('.modal').hide();
        });
        
        $(document).on('click','#variant_save',function(e){
            e.preventDefault();
            var id = $(this).data('id');
            var atr_name = $("input[name='atr_name_"+id+"[]']").map(function(){return $(this).val();}).get();
            var atr_value = $("input[name='atr_value_"+id+"[]']").map(function(){return $(this).val();}).get();
            
            $.ajax({
                url: "{{ route('product.variation_atribute.edit') }}",
                data: {id:id, atr_name:atr_name, atr_value:atr_value},
                success: function(response){
                    if(response.status == true)
                    {
                        $('.modal').hide();
                    }
                },
                error: function (data) { 
                    alert('error happened');
                }
            });
        });

        $(document).on('click','#dimension_save',function(e){
            e.preventDefault();
            var id = $(this).data('id');
            var length = $("input[name='dimension_length_"+id+"']").val();
            var width = $("input[name='dimension_width_"+id+"']").val();
            var height = $("input[name='dimension_height_"+id+"']").val();
            
            $.ajax({
                url: "{{ route('product.variation_dimension.edit') }}",
                data: {id:id, length:length, width:width, height:height},
                success: function(response){
                    if(response.status == true)
                    {
                        $('.modal').hide();
                        // console.log(response.data);
                    }
                },
                error: function (data) { 
                    alert('error happened');
                }
            });
        });
        $("#uploadgallery").change(function () {
            $("#form-gallery").submit();
        });

        $(document).on('submit', '#form-gallery', function () {
            $.ajax({
                url: "{{ route('admin-gallery-store') }}",
                method: "POST",
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data != 0) {
                        $('.selected-image .row').removeClass('justify-content-center');
                        $('.selected-image .row h3').remove();
                        var arr = $.map(data, function (el) {
                            return el
                        });
                        for (var k in arr) {
                            $('.selected-image .row').append('<div class="col-sm-6">' +
                                '<div class="img gallery-img">' +
                                '<span class="remove-img"><i class="fas fa-times"></i>' +
                                '<input type="hidden" value="' + arr[k]['id'] + '">' +
                                '</span>' +
                                '<a href="'+ arr[k]['photo'] + '" target="_blank">' +
                                '<img src="'+ arr[k]['photo'] + '" alt="gallery image">' +
                                '</a>' +
                                '</div>' +
                                '</div>');
                        }
                    }

                }

            });
            return false;
        });


        // Gallery Section Update Ends

    </script>

    <script src="{{asset('assets/admin/js/jquery.Jcrop.js')}}"></script>

    <script src="{{asset('assets/admin/js/jquery.SimpleCropper.js')}}"></script>

    <script type="text/javascript">

        // $('.cropme').simpleCropper();
    </script>


    <script type="text/javascript">
        $(document).ready(function () {

                let html = `<img src="{{ empty($data->photo) ? asset('storage/no-image-found/noimage.png') : (filter_var($data->photo, FILTER_VALIDATE_URL) ? $data->photo : asset('storage/products/'.$data->photo)) }}" alt="">`;
                $(".span4.cropme").html(html);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            });
        /* $(document).ready(function () {

            let html = `<img src="{{ empty($data->photo) ? asset('storage/no-image-found/noimage.png') : (filter_var($data->photo, FILTER_VALIDATE_URL) ? $data->photo : asset('storage/products/'.$data->photo)) }}" alt="">`;
            $(".span4.cropme").html(html);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });


        $('.ok').on('click', function () {

            setTimeout(
                function () {

                    var img = $('#feature_photo').val();

                    $.ajax({
                        url: "{{route('admin.product.upload.update',$data->id)}}",
                        type: "POST",
                        data: {"image": img},
                        success: function (data) {
                            if (data.status) {
                                $('#feature_photo').val(data.file_name);
                            }
                            if ((data.errors)) {
                                for (var error in data.errors) {
                                    $.notify(data.errors[error], "danger");
                                }
                            }
                        }
                    });

                }, 1000);

        }); */
       
        

    </script>

    <script type="text/javascript">

        $('#imageSource').on('change', function () {
            var file = this.value;
            if (file == "file") {
                $('#f-file').show();
                $('#f-link').hide();
            }
            if (file == "link") {
                $('#f-file').hide();
                $('#f-link').show();
            }
        });

    </script>

    <script src="{{asset('assets/admin/js/product.js')}}"></script>
    <script>
        // $(document).on('change', '.merchant_id_cro_brand', function () {
        //     var merchant_id 	= $('.merchant_id_cro_brand option:selected').val();
        //     if(merchant_id.length > 0)
        //     {
        //         var url = $('.getBrandsByMerchant').val();
        //         console.log(url);
        //         $.ajax({
        //             url: url,
        //             type: "GET",
        //             data:{merchant_id:merchant_id},
        //             datatype:"HTML",
        //             success: function(response){
        //                 if(response.status == true)
        //                 {
        //                     $('.brand_id').html(response.html);
        //                 }else{
        //                     $('.brand_id').html('<option value=""> Please Select Valid Data</option>');
        //                 }
        //             },
        //         });
        //     }else{
        //         $('.brand_id').html('<option value=""> Please Select Valid Data</option>');
        //     }   
        // });
        // $(document).ready(function(){
        //     var merchant_id 	= $('.merchant_id_cro_brand option:selected').val();
        //     var selectedBrandId = $('.brandSelectedId').val();
        //     if(merchant_id.length > 0)
        //     {
        //         var url = $('.getBrandsByMerchant').val();
        //         console.log(url);
        //         $.ajax({
        //             url: url,
        //             type: "GET",
        //             data:{merchant_id:merchant_id},
        //             datatype:"HTML",
        //             success: function(response){
        //                 if(response.status == true)
        //                 {
        //                     $('.brand_id').html(response.html);
        //                     $('#brand_id option[value="'+selectedBrandId+'"]').attr("selected", "selected");
        //                 }else{
        //                     $('.brand_id').html('<option value=""> Please Select Valid Data</option>');
        //                 }
        //             },
        //         });
        //     }else{
        //         $('.brand_id').html('<option value=""> Please Select Valid Data</option>');
        //     }   
        // });

    // taging
	$('#tags').select2({
		tags: true,
		tokenSeparators: [',', ' '],
		'minimumInputLength': 1,
		ajax: {
			url: "{{ route('admin.products.tags.get') }}",
			data: function(params) {
				return {
					search: params.term
				};
			},
			processResults: function(data) {
				return {
					results: data
				};
			}
		}
	});
    </script>
    
@endpush
