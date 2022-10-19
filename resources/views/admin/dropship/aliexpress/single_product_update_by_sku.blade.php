@extends('layouts.admin')
@section('styles')

<link href="{{asset('assets/admin/css/product.css')}}" rel="stylesheet"/>
@endsection
@section('content')

<div class="content-area">
	<div class="mr-breadcrumb">
		<div class="row">
			<div class="col-lg-12">
					<h4 class="heading">{{ __("Dropshipping Aliexpress Update") }}</h4>
					<ul class="links">
						<li>
							<a href="{{ route('admin.dashboard') }}">{{ __("Dashboard") }} </a>
						</li>
                        <li>
                            <a href="javascript:;">{{ __("Products") }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin_dropship_aliexpress_index') }}">{{ __("Dropship Aliexpress") }}</a>
                        </li>
					</ul>
			</div>
		</div>
	</div>
	<div class="add-product-content">
		<div class="row">
			<div class="col-lg-12 p-5">
				<div class="gocover" style="background: url({{asset('assets/images/xloading.gif')}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
					

						
						{{-- <div class="row" >
							<div class="col-lg-12 d-flex justify-content-center text-center" id="process" style="display:none; margin-bottom:2%;">
								<div class="progress" style="width:90%;display:none;">
									<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" ></div>
									<strong class="parcentage" style="padding-top: 5px;"></strong>
								</div>
							</div> 
						</div>
						<div class="row">
							<div class="col-lg-12 d-flex justify-content-center text-center">
								<strong class="currentSkuNo" style="display: none;">Updating SKU : </strong> <strong class="current_sku"></strong>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-12 mt-4 text-center">
								<strong class="message"></strong>
									<img src="{{ asset('assets/images/xloading.gif') }}" alt="" class="loading mr-5" style="display: none">
							</div>
						</div>



						<div class="row">
                            <div class="col-lg-12 mt-4 text-center">
                                <div class="row">
                                    <div class="col-lg-1 text-center">
                                        <strong>Bigbuy SKU</strong>
                                    </div>
                                    <div class="col-lg-11  text-center">
										<select id="sku" class="form-control sku" multiple="multiple" name="sku[]"></select>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="row disabled">
                            <div class="col-lg-12 mt-4 text-center">
                                <button class="mybtn1 mr-5 disabledAttr import" type="submit" >{{ __("Update Product From Bigbuy") }}</button>
                            </div>
                        </div> --}}

                        <div class="row" style="width: 100%;">
							<div class="col-lg-12 d-flex justify-content-center text-center">
								<div class="alert alert-custom-success alert-success validation" style="display: none;width: 50%;">
                                    <button type="button" class="close alert-close"><span>×</span></button>
                                    <p class="message text-center mb-0"></p>
                                </div>
                                <br/>
                                <div class="alert alert-custom-error alert-danger validation" style="display: none;width: 50%;">
                                    <button type="button" class="close alert-close"><span>×</span></button>
                                    <p class="message text-center mb-0"></p>
                                </div>
							</div> 
						</div>

                        <div class="row">
                            <div class="col-lg-12 mt-4 text-center">
                                <div class="row loading" style="text-align: center;display:none;margin-bottom:2px;">
                                    <div class="col-md-12">
                                        <strong class="">Processing...</strong>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 text-center">
                                        <strong>Product SKU</strong>
                                    </div>
                                    <div class="col-lg-10  text-center">
                                        <input type="text" class="form-control sku">
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="row disabled">
                            <div class="col-lg-12 mt-4 text-center">
                                <button class="mybtn1 mr-5 disabledAttr update" type="submit" >{{ __("Update Product From Aliexpress") }}</button>
                            </div>
                        </div>

				</div>
		</div>
	</div>
</div>

<input type="hidden" value="{{route('admin.aliexpress.single.product.updating.by.sku')}}" class="updatingSingleProductByProductSkuUrl"/>
@endsection

@section('scripts')
<script src="{{asset('custom_js/dropship/aliexpress-single-product-update.js')}}"></script>
<script>
	
	$('#sku').select2({
		tags: true,
		tokenSeparators: [',', ' '],
		'minimumInputLength': 1,
	});


</script>
@endsection
