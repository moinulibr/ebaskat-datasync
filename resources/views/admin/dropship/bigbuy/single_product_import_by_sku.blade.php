@extends('layouts.admin')
@section('styles')

<link href="{{asset('assets/admin/css/product.css')}}" rel="stylesheet"/>
@endsection
@section('content')

<div class="content-area">
	<div class="mr-breadcrumb">
		<div class="row">
			<div class="col-lg-12">
					<h4 class="heading">{{ __("Dropshipping Bigbuy Import") }}</h4>
					<ul class="links">
						<li>
							<a href="{{ route('admin.dashboard') }}">{{ __("Dashboard") }} </a>
						</li>
                        <li>
                            <a href="javascript:;">{{ __("Products") }} </a>
                        </li>
                        <li>
                            <a href="{{ route('adminDropshippingBigbuyIndex') }}">{{ __("Dropship Bigbuy") }}</a>
                        </li>
					</ul>
			</div>
		</div>
	</div>
	<div class="add-product-content">
		<div class="row">
			<div class="col-lg-12 p-5">
				<div class="gocover" style="background: url({{asset('assets/images/xloading.gif')}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
					
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
                                <button class="mybtn1 mr-5 disabledAttr update" type="submit" >{{ __("Import Product From Bigbuy") }}</button>
                            </div>
                        </div>

				</div>
		</div>
	</div>
</div>

<input type="hidden" value="{{route('admin.bigbuy.single.product.importing.by.sku')}}" class="importingSingleProductByProductSkuUrl"/>
@endsection

@section('scripts')
<script src="{{asset('custom_js/dropship/bigbuy-single-product-import.js')}}"></script>
<script>
	
	$('#sku').select2({
		tags: true,
		tokenSeparators: [',', ' '],
		'minimumInputLength': 1,
	});


</script>
@endsection
