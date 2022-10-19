@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __('PRODUCT') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Products') }}</h4>
                    <ul class="links" style="width:60%;float:left;">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Products') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.product.index') }}">{{ __('All Products') }}</a>
                        </li>
                    </ul>
                    <ul class="links" style="width:40%;float:right;text-align:right">
                        <li>
                            <a class="add-btn" style="color:white" href="{{route('admin.product.physical.create')}}"><i class="fas fa-plus"></i> <span class="remove-mobile">Add New Product<span></span></span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">
                        @include('includes.form-success')
                        <div>
                            <table  style="width: 100%;">
                                <tr>
                                    <td style="width: 10%">
                                        <label for="">&nbsp;</label>
                                        <select class="form-control paginate" id="paginate" name="paginate" style="font-size: 12px;width:100%;">
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                            <option value="40">40</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="200">200</option>
                                            <option value="300">300</option>
                                            <option value="500">500</option>
                                            <option value="1000">1000</option>
                                        </select>
                                    </td>
                                    <td style="width: 20%"></td>
                                    <td style="width: 30%">
                                        <label for="">Search</label>
                                        <input type="text" class="search form-control" name="search" autofocus autocomplete="off">
                                    </td>
                                    <td style="width: 10%"></td>
                                    <td style="width: 30%">
                                        <label for="">Category</label>
                                        <select name="category" id="category_filter_id" class="category_filter_id form-control">
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option> 
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="on_processing" style="text-align: center;padding-bottom:20px;display:none;">
                            <strong style="color:#0c0c0c;z-index:99999;background-color:#f9f9f9;padding:3px 5px;border-radious:3px solidg gray;">
                                Processing...
                            </strong>
                        </div>

                        <div class="productListAjaxResult" style="font-family: sans-serif">
                            <div class="table-responsiv">
                                <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Photo') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Category') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th>{{ __('Stock') }}</th>
                                            <th>{{ __('Price') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Options') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{-- HIGHLIGHT MODAL --}}
    <div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="modal2" aria-hidden="true">
        <div class="modal-dialog highlight" role="document">
            <div class="modal-content">
                <div class="submit-loader">
                    <img src="{{ asset('assets/images/xloading.gif') }}" alt="">
                </div>
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- HIGHLIGHT ENDS --}}


    {{-- CATALOG MODAL --}}
    <div class="modal fade" id="catalog-modal" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-block text-center">
                    <h4 class="modal-title d-inline-block">{{ __('Update Status') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <p class="text-center">{{ __('You are about to change the status of this Product.') }}</p>
                    <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-success btn-ok">{{ __('Proceed') }}</a>
                </div>
            </div>
        </div>
    </div>
    {{-- CATALOG MODAL ENDS --}}



    {{-- DELETE and Unpublished MODAL --}}
    @include('includes.delete-unpublished-modal', ['type' => 'Products'])
    {{-- Resote and published Modal --}}
    @include('includes.restore_published_modal', ['type' => 'Products'])



    {{-- GALLERY MODAL --}}
    <div class="modal fade" id="setgallery" tabindex="-1" role="dialog" aria-labelledby="setgallery" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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
                                <a href="javascript:;" class="upload-done" data-dismiss="modal"> <i
                                        class="fas fa-check"></i> {{ __('Done') }}</a>
                            </div>
                            <div class="col-sm-12 text-center">(
                                <small>{{ __('You can upload multiple Images') }}.</small> )</div>
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
    {{-- GALLERY MODAL ENDS --}}


    {{-- detail MODAL --}}
    <div class="modal fade" id="productShortDetails" tabindex="-1" role="dialog" aria-labelledby="productShortDetails" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> {{--modal-xl--}}
            <div class="modal-content">
                <div class="submit-loader">
                    <img src="{{ asset('assets/images/xloading.gif') }}" alt="">
                </div>
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="containt-data"></div>
                        
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- detail ENDS --}}

    {{-- promotion level MODAL --}}
    <div class="modal fade" id="promotionLevel" tabindex="-1" role="dialog" aria-labelledby="promotionLevel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> {{--modal-xl--}}
            <div class="modal-content">
                <div class="submit-loader">
                    <img src="{{ asset('assets/images/xloading.gif') }}" alt="">
                </div>
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="promotion-data"></div>
                        
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- promotion level MODAL ENDS --}}

    {{-- category subcategory edit MODAL --}}
    <div class="modal fade" id="categorySubCategoryEditModal" tabindex="-1" role="dialog" aria-labelledby="productShortDetails" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> {{--modal-xl--}}
            <div class="modal-content">
                <div class="submit-loader">
                    <img src="{{ asset('assets/images/xloading.gif') }}" alt="">
                </div>
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="containt-data-category-edit"></div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- category subcategory edit ENDS --}}


    {{-- permissions --}}
    <input type="hidden" id="can_add" value="{{ \Auth::guard('admin')->user()->role->permissionCheck('products|add') }}">
    <input type="hidden" id="can_edit" value="{{ \Auth::guard('admin')->user()->role->permissionCheck('products|edit') }}">
    
    <input type="hidden" class="productListByAjaxResponseUrl" value="{{ route('admin.product.list.ajaxresponse') }}">
    <input type="hidden" class="editCategoryUrlForOpeningModal" value="{{ route('admin.product.category.edit') }}">
    <input type="hidden" class="subCategoryByCategoryUrl" value="{{ route('admin.product.subcat.by.categoryid') }}">
@endsection


@section('scripts')
    {{---product list by ajax---}}
    <script src="{{asset('custom_js/product/index.js')}}"></script>
    {{---product list by ajax---}}


    {{---product short description---}}
    <script>
        $(document).on('click','.productShortDetail',function(){
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.product.detail') }}",
                data: {id:id},
                success: function(response){
                    if(response.status == true)
                    {
                        $('.containt-data').html(response.data);
                        $('#productShortDetails').modal('show'); 
                    }
                },
                error: function (data) { 
                    alert('error happened');
                }
            });
        });
    </script>
    {{---product short description---}}

    <script>
        $(document).on('click','.promotion_level',function(){
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.product.promotion-level') }}",
                data: {id:id},
                success: function(response){
                    if(response.status == true)
                    {
                        $('.promotion-data').html(response.data);
                        $('#promotionLevel').modal('show'); 
                    }
                },
                error: function (data) { 
                    alert('error happened');
                }
            });
        });

        $(document).on('click','.changePromotionStatus',function(){
            var id = $(this).data('id');
            var value = $(this).data('value');
            $.ajax({
                url: "{{ route('admin.product.promotion.change') }}",
                data: {id:id, value:value},
                success: function(response){
                    if(response.status == true)
                    {
                        if(response.data == 0){
                            $('.promotion_status_'+response.value).html("Inactive");
                            $('.promotion_status_'+response.value).removeClass('success');
                            $('.promotion_status_'+response.value).addClass('danger');
                            $('.btn_active_'+response.value).prop("disabled",false) ;
                            $('.btn_inactive_'+response.value).prop("disabled",true);
                            $.notify("Promotion Status Change to Incative", "erro");
                        }
                        else{
                            $('.promotion_status_'+response.value).html("Active");
                            $('.promotion_status_'+response.value).removeClass('danger');
                            $('.promotion_status_'+response.value).addClass('success');
                            $('.btn_active_'+response.value).prop("disabled",true);
                            $('.btn_inactive_'+response.value).prop("disabled",false);
                            $.notify("Promotion Status Change to Active", "success");
                        }
                    }
                },
                error: function (data) { 
                    alert('error happened');
                }
            });
        });
    </script>
    

  
@endsection
