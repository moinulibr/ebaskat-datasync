@extends('layouts.admin')
@section('styles')

<link href="{{asset('assets/admin/css/product.css')}}" rel="stylesheet"/>

@endsection
@section('content')

<div class="content-area">
	<div class="mr-breadcrumb">
		<div class="row">
			<div class="col-lg-12">
					<h4 class="heading">{{ __("Product Bulk Update") }}</h4>
					<ul class="links">
						<li>
							<a href="{{ route('admin.dashboard') }}">{{ __("Dashboard") }} </a>
						</li>
					<li>
						<a href="javascript:;">{{ __("Products") }} </a>
					</li>
					<li>
						<a href="{{ route('admin.product.index') }}">{{ __("All Products") }}</a>
					</li>
						<li>
							<a href="{{ route('admin-prod-bulkEdit') }}">{{ __("Bulk Update") }}</a>
						</li>
					</ul>
			</div>
		</div>
	</div>
	<div class="add-product-content">
		<div class="row">
			<div class="col-lg-12 p-5">

				<div class="gocover" style="background: url({{asset('assets/images/xloading.gif')}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
					<form id="" action="{{route('admin-prod-update')}}" method="POST" enctype="multipart/form-data" class="submitCsvFile">
					{{csrf_field()}}

					@include('includes.form-both')  
                        <div class="row">
							<div class="col-lg-12 text-right">
								<span style="margin-top:10px;"><a class="btn btn-primary" href="{{asset('assets/csv-upload-format/product-edit-csv-format.csv')}}">{{ __("Download Sample CSV") }}</a></span>
							</div>
						</div>
						<hr>
						<div class="row justify-content-center">
							<div class="col-lg-12 d-flex justify-content-center text-center">
								<div class="csv-icon">
									<i class="fas fa-file-csv"></i>
								</div>
							</div>

 							<div class="col-lg-12 d-flex justify-content-center text-center" id="process" style="display:none; margin-bottom:2%;">
								<div class="progress" style="width:90%;display:none;">
									<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" ></div>
									<strong class="parcentage"></strong>
								</div>
							</div> 

							<div class="col-lg-12 d-flex justify-content-center text-center">
								<div class="left-area mr-4">
									<h4 class="heading">{{ __("Upload a File") }} *</h4>
								</div>
								<span class="file-btn">
									<input type="file" id="csvfile" name="csvfile" accept=".csv" class="emptyFile">
									<p class="csvfile_err color-red" style="text-align: left;"></p>
								</span>
							</div>
						</div>

						<input type="hidden" name="type" value="Physical">
						<div class="row">
							<div class="col-lg-12 mt-4 text-center">
								<button class="mybtn1 mr-5 disabledAttr"  type="submit">{{ __("Start Import") }}</button>
							</div>
						</div>
					</form>
			</div>
		</div>
	</div>
</div>
<input type="hidden" value="{{route('admin.processing.bulk.product.update.progress.bar')}}" class="adminProcessingBulkProductUpdateProgressBar"/>
@endsection

@section('scripts')

<script src="{{asset('assets/admin/js/product.js')}}"></script>
<script src="{{asset('custom_js/bulk-product/bulk_product_update.js')}}"></script>
@endsection