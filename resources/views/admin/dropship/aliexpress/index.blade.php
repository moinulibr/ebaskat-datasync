@extends('layouts.admin')
@section('styles')

<link href="{{asset('assets/admin/css/product.css')}}" rel="stylesheet"/>

@endsection
@section('content')

<div class="content-area">
	<div class="mr-breadcrumb">
		<div class="row">
			<div class="col-lg-12">
					<h4 class="heading">{{ __("Dropshipping Aliexpress Import") }}</h4>
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
								<strong class="currentPageNo" style="display: none;">Importing Page No : </strong> <strong class="current_page"></strong>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-12 mt-4 text-center">
								<strong class="message"></strong>
									<img src="{{ asset('storage/xloading.gif') }}" alt="" class="loading mr-5" style="display: none">
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 mt-4 text-center">
								<div class="row">
									<div class="col-lg-5 text-center">
										<strong>Start Page</strong>
									</div>
									<div class="col-lg-7  text-center">
										<input type="number" class="input-field startPage" name="start_page" placeholder="{{ __("Starg Page") }}" required="" value="">
										<strong class="error_mess_startPage" style="text-align: left;color:red;"></strong>
									</div>
								</div>
							</div>
							<div class="col-lg-6 mt-4 text-center">
								<div class="row">
									<div class="col-lg-5 text-center">
										<strong>End Page</strong>
									</div>
									<div class="col-lg-7 text-center">
										<input type="number" class="input-field endPage" name="end_page" placeholder="{{ __("End Page") }}" required="" value="">
										<strong class="error_mess_endPage" style="text-align: left;color:red;"></strong>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 mt-4 text-center">
								<button class="mybtn1 mr-5 disabledAttr import" type="submit" disabled>{{ __("Import Product From Aliexpress") }}</button>
							</div>
						</div>
				</div>
		</div>
	</div>
</div>

	<input type="hidden" value="{{route('admin_import_product_from_aliexpress')}}" class="admin_import_product_from_aliexpress"/>
	<input type="hidden" value="{{route('admin.dropship.aliexpress.product.import.progress.bar')}}" class="aliexpressProductImportProgressingBar"/>
	<input type="hidden" value="{{route('admin.dropship.aliexpress.update.value.after.completed.progress.bar')}}" class="updateInsertedValueAfterCompletedProgressBar"/>
@endsection

@section('scripts')

<script src="{{asset('custom_js/dropship/aliexpress.js')}}"></script>

@endsection
