@extends('layouts.admin')
@section('styles')

<link href="{{asset('assets/admin/css/product.css')}}" rel="stylesheet" />
<link href="{{asset('assets/admin/css/jquery.Jcrop.css')}}" rel="stylesheet" />
<link href="{{asset('assets/admin/css/Jcrop-style.css')}}" rel="stylesheet" />

@endsection
@section('content')

<div class="content-area">
	<div class="mr-breadcrumb">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="heading">{{ __("Digital Product") }} <a class="add-btn float-right btn-sm mt-3"
						href="{{ route('admin.product.types') }}"><i class="fas fa-arrow-left"></i> {{ __("Back") }}</a>
				</h4>
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
						<a href="{{ route('admin.product.types') }}">{{ __("Add Product") }}</a>
					</li>
					<li>
						<a href="{{ route('admin.prod.digital.create') }}">{{ __("Digital Product") }}</a>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<form id="geniusform" action="{{route('admin.product.store')}}" method="POST"
									enctype="multipart/form-data">
									{{csrf_field()}}
<!-- Digital product (main content) starts -->
	<div class="row">
	<!-- Left side content starts -->
		<div class="col-lg-8">

			<div class="add-product-content">
				<div class="row">
					<div class="col-lg-12">
						<div class="product-description">
							<div class="body-area">

								<div class="gocover"
									style="background: url({{asset('assets/images/xloading.gif')}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
								</div>


									@include('includes.form-both')

								<!-- Product name row-->
									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading">{{ __("Product Name") }}* </h4>
												<p class="sub-heading">{{ __("(In Any Language)") }}</p>
											</div>
										</div>
										<div class="col-lg-12">
											<input type="text" class="input-field" placeholder="{{ __("Enter Product Name") }}"
												name="name" required="">
										</div>
									</div>

								<!-- Product category and sub-category row-->
									<div class="row">
										<!-- Product category-->
										<div class="col-lg-6">
											<div class="left-area">
												<h4 class="heading">{{ __("Category") }}*</h4>
											</div>

											<select id="cat" name="category_id" required="">
												<option value="">{{ __("Select Category") }}</option>
												@foreach($cats as $cat)
												<option data-href="{{ route('admin-subcat-load',$cat->id) }}"
													value="{{ $cat->id }}">{{$cat->name}}</option>
												@endforeach
											</select>
										</div>
										<!-- Product sub-category-->
										<div class="col-lg-6">
											<div class="left-area">
												<h4 class="heading">{{ __("Sub Category") }}*</h4>
											</div>
											<select id="subcat" name="subcategory_id" disabled="">
												<option value="">{{ __("Select Sub Category") }}</option>
											</select>

										</div>
									</div>

									<!-- Child Category and select upload type row-->

									<div class="row">
										<!-- Child Category -->
										<div class="col-lg-6">
											<div class="left-area">
												<h4 class="heading">{{ __("Child Category") }}*</h4>
											</div>

											<select id="childcat" name="childcategory_id" disabled="">
												<option value="">{{ __("Select Child Category") }}</option>
											</select>
										</div>
										<!-- Select upload type -->
										<div class="col-lg-6">
											<div class="left-area">
												<h4 class="heading">{{ __("Select Upload Type") }}*</h4>
											</div>

											<select id="type_check" name="type_check">
												<option value="1">{{ __("Upload By File") }}</option>
												<option value="2">{{ __("Upload By Link") }}</option>
											</select>
										</div>
									</div>
									<!-- For file uploading -->
									<div class="row file">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading">{{ __("Select File") }}*</h4>
											</div>
										</div>
										<div class="col-lg-12">
											<input type="file" name="file" required="">
										</div>
									</div>
									<!-- For link uploading -->
									<div class="row link hidden">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading">{{ __("Link") }}*</h4>
											</div>
										</div>
										<div class="col-lg-12">
											<textarea class="input-field" rows="2" name="link"
												placeholder="{{ __("Link") }}"></textarea>
										</div>
									</div>

								<!-- Product description and return/buy policy starts -->
								<!-- Product description -->
								<div class="row">
                                <div class="col-lg-12">
                                    <div class="left-area">
                                        <h4 class="heading">
                                            {{ __('Product Description') }}*
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="text-editor">
                                        <textarea class="nic-edit-p" name="details"></textarea>
                                    </div>
                                </div>
                            </div>


							<!-- Product buy/return policy -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="left-area">
                                        <h4 class="heading">
                                            {{ __('Product Buy/Return Policy') }}*
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="text-editor">
                                        <textarea class="nic-edit-p" name="policy"></textarea>
                                    </div>
                                </div>
                            </div>
								<!-- Product description and return/buy policy ends -->

								<!-- Allow product SEO starts -->
									<div class="row">
										<div class="col-lg-12">
											<div class="checkbox-wrapper">
												<input type="checkbox" name="seo_check" class="checkclick" id="allowProductSEO"
													value="1">
												<label for="allowProductSEO">{{ __("Allow Product SEO") }}</label>
											</div>
										</div>
									</div>

									<div class="showbox">
										<div class="row">
											<div class="col-lg-12">
												<div class="left-area">
													<h4 class="heading">{{ __("Meta Tags") }} *</h4>
												</div>
											</div>
											<div class="col-lg-12">
												<ul id="metatags" class="myTags">
												</ul>
											</div>
										</div>

										<div class="row">
											<div class="col-lg-12">
												<div class="left-area">
													<h4 class="heading">
														{{ __("Meta Description") }} *
													</h4>
												</div>
											</div>
											<div class="col-lg-12">
												<div class="text-editor">
													<textarea name="meta_description" class="input-field"
														placeholder="{{ __("Meta Description") }}"></textarea>
												</div>
											</div>
										</div>
									</div>
								<!-- Allow product SEO ends -->

								<!-- Create product(button) starts -->
									<div class="row">
										<div class="col-lg-12 text-center">
											<button class="addProductSubmit-btn" type="submit">Create Product</button>
										</div>
									</div>
								<!-- Create product(button) ends -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- Left side content ends -->

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
												<h4 class="heading">{{ __("Feature Image") }} *</h4>
											</div>
										</div>
										<div class="col-lg-12">
												<div class="panel panel-body">
													<div class="span4 cropme text-center" id="landscape"
													style="width: 100%; height: 285px; border: 1px dashed #ddd; background: #f1f1f1;">
													<a href="javascript:;" id="crop-image" class="d-inline-block mybtn1">
														<i class="icofont-upload-alt"></i> {{ __("Upload Image Here") }}
													</a>
													</div>
											</div>




										</div>
									</div>

									<input type="hidden" id="feature_photo" name="photo" value="">



									<input type="file" name="gallery[]" class="hidden" id="uploadgallery" accept="image/*"
										multiple>
									<div class="row mb-4">
										<div class="col-lg-12 mb-2">
											<div class="left-area">
												<h4 class="heading">
													{{ __("Product Gallery Images") }} *
												</h4>
											</div>
										</div>
										<div class="col-lg-12">
											<a href="#" class="set-gallery" data-toggle="modal" data-target="#setgallery">
												<i class="icofont-plus"></i> {{ __("Set Gallery") }}
											</a>
										</div>
									</div>




									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading">
													{{ __("Product Current Price") }}*
												</h4>
												<p class="sub-heading">
													({{ __("In EURO") }})
												</p>
											</div>
										</div>
										<div class="col-lg-12">
											<input name="price" type="number" class="input-field"
												placeholder="{{ __("e.g 20") }}" step="0.01" required="" min="0">
										</div>
									</div>

									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading">{{ __("Product Previous Price") }}*</h4>
												<p class="sub-heading">{{ __("(Optional)") }}</p>
											</div>
										</div>
										<div class="col-lg-12">
											<input name="previous_price" step="0.01" type="number" class="input-field"
												placeholder="{{ __("e.g 20") }}" min="0">
										</div>
									</div>




									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading">{{ __("Youtube Video URL") }}*</h4>
												<p class="sub-heading">{{ __("(Optional)") }}</p>
											</div>
										</div>
										<div class="col-lg-12">
											<input name="youtube" type="text" class="input-field"
												placeholder="{{ __("Enter Youtube Video URL") }}">
										</div>
									</div>


									<div class="row">
										<div class="col-lg-12">
											<div class="featured-keyword-area">
												<div class="left-area">
													<h4 class="heading">{{ __('Feature Tags') }}</h4>
												</div>
												<div class="feature-tag-top-filds" id="feature-section">
													<div class="feature-area">
														<span class="remove feature-remove"><i class="fas fa-times"></i></span>
														<div class="row">
															<div class="col-lg-6">
																<input type="text" name="features[]" class="input-field"
																	placeholder="{{ __("Enter Your Keyword") }}">
															</div>

															<div class="col-lg-6">
																<div class="input-group colorpicker-component cp">
																	<input type="text" name="colors[]" value="#000000"
																		class="input-field cp" />
																	<span class="input-group-addon"><i></i></span>
																</div>
															</div>
														</div>
													</div>
												</div>

												<a href="javascript:;" id="feature-btn" class="add-fild-btn"><i
														class="icofont-plus"></i> {{ __("Add More Field") }}</a>
											</div>
										</div>
									</div>


									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading">{{ __('Tags') }} *</h4>
											</div>
										</div>
										<div class="col-lg-12">
											<ul id="tags" class="myTags">
											</ul>
										</div>
									</div>
									<input type="hidden" name="type" value="Digital">

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- Right side content ends -->
	</div>

	</form>
</div>

<div class="modal fade" id="setgallery" tabindex="-1" role="dialog" aria-labelledby="setgallery" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle">Image Gallery</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="top-area">
					<div class="row">
						<div class="col-sm-6 text-right">
							<div class="upload-img-btn">
								<label for="image-upload" id="prod_gallery"><i class="icofont-upload-alt"></i>Upload
									File</label>
							</div>
						</div>
						<div class="col-sm-6">
							<a href="javascript:;" class="upload-done" data-dismiss="modal"> <i
									class="fas fa-check"></i> Done</a>
						</div>
						<div class="col-sm-12 text-center">( <small>You can upload multiple Images.</small> )</div>
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

@endsection

@section('scripts')

<script src="{{asset('assets/admin/js/jquery.Jcrop.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery.SimpleCropper.js')}}"></script>

<script type="text/javascript">
	// Gallery Section Insert

	$(document).on('click', '.remove-img', function () {
		var id = $(this).find('input[type=hidden]').val();
		$('#galval' + id).remove();
		$(this).parent().parent().remove();
	});

	$(document).on('click', '#prod_gallery', function () {
		$('#uploadgallery').click();
		$('.selected-image .row').html('');
		$('#geniusform').find('.removegal').val(0);
	});


	$("#uploadgallery").change(function () {
		var total_file = document.getElementById("uploadgallery").files.length;
		for (var i = 0; i < total_file; i++) {
			$('.selected-image .row').append('<div class="col-sm-6">' +
				'<div class="img gallery-img">' +
				'<span class="remove-img"><i class="fas fa-times"></i>' +
				'<input type="hidden" value="' + i + '">' +
				'</span>' +
				'<a href="' + URL.createObjectURL(event.target.files[i]) + '" target="_blank">' +
				'<img src="' + URL.createObjectURL(event.target.files[i]) + '" alt="gallery image">' +
				'</a>' +
				'</div>' +
				'</div> '
			);
			$('#geniusform').append('<input type="hidden" name="galval[]" id="galval' + i +
				'" class="removegal" value="' + i + '">')
		}

	});

	// Gallery Section Insert Ends
</script>

<script type="text/javascript">
	$('.cropme').simpleCropper();
</script>


<script src="{{asset('assets/admin/js/product.js')}}"></script>
@endsection
