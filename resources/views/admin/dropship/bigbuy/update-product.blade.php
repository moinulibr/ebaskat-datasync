@extends('layouts.admin')
@section('styles')

<link href="{{asset('assets/admin/css/product.css')}}" rel="stylesheet"/>
@endsection
@section('content')

<div class="content-area">
	<div class="mr-breadcrumb">
		<div class="row">
			<div class="col-lg-12">
					<h4 class="heading">{{ __("Dropshipping Bigbuy Update") }}</h4>
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
					
					@include('includes.form-both')

						
						<div class="row" >
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
                        </div>
				</div>
		</div>
	</div>
</div>

<input type="hidden" value="{{route('admin.dropship.bigbuy.update.imported.single.product.by.sku')}}" class="bigbuyBigbuyImportedSingleProduct"/>
<input type="hidden" value="{{route('admin.dropship.bigbuy.get.updated.row.when.updating.single.product.by.sku.for.progress.bar')}}" class="getUpdatedValueAfterUpdatingBigbuyProductForProgressBar"/>
<input type="hidden" value="{{route('admin.dropship.bigbuy.update.value.after.completed.progress.bar.when.single.product.update.by.sku')}}" class="updatedValueAfterUpdateCompletedProgressBar"/>
@endsection

@section('scripts')
<script src="{{asset('custom_js/dropship/bigbuy-product-update.js')}}"></script>
<script>
	
	$('#sku').select2({
		tags: true,
		tokenSeparators: [',', ' '],
		'minimumInputLength': 1,
	});


</script>
@endsection
