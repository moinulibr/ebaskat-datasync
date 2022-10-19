@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __('PRODUCT') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Promotional Products') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Products') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.product.promotion') }}">{{ __('Promotional Products') }}</a>
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
                                        <select class="form-control paginate" id="paginate" style="font-size: 12px;width:100%;">
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
                                        <input type="text" name="search" class="search form-control" autofocus>
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
                        <div class="productListAjaxResult" style="font-family: sans-serif">
                            <div class="table-responsiv">
                                <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Just in') }}</th>
                                            <th>{{ __('Weekly Deals') }}</th>
                                            <th>{{ __('Trending Products') }}</th>
                                            <th>{{ __('Top Kids & Baby Products') }}</th>
                                            <th>{{ __('Featured Phones & Accessories') }}</th>
                                            <th>{{ __('The Beauty Editors Pick') }}</th>
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
    <input type="hidden" class="subCategoryByCategoryUrl" value="{{ route('admin.product.subcat.by.categoryid') }}">
    <input type="hidden" class="productListByAjaxResponseUrl" value="{{ route('admin.product.promotion.ajax') }}">

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
        //subcategory by category when change category from details product
        $(document).on('change','.categoryId',function(){
            var catid = $('#category_id option:selected').val();
            var url = $('.subCategoryByCategoryUrl').val();
            $.ajax({
                url: url,
                data: {catid:catid},
                success: function(response){
                    if(response.status == true)
                    {
                        $('#subcategory_id').html(response.html);
                    }
                },
                error: function (data) { 
                    alert('error happened');
                }
            });
        });
        //subcategory by category when change category from details product

        //update category
        $(document).on("submit",'.updateSingleCategory',function(e){
            e.preventDefault();
            var form = $(this);
            var url = form.attr("action");
            var type = form.attr("method");
            var data = form.serialize();
            $.ajax({
                url: url,
                data: data,
                type: type,
                datatype:"JSON",
                beforeSend:function(){
                    //$('.loading').fadeIn();
                },
                success: function(response){
                    // productListLoading();
                    if(response.status == true)
                    {
                        $('.categoryName').text(response.cat_name);
                        $('.subCategoryName').text(response.sub_cat_name);
                        $('.categoryName').css({
                            'color':'green'
                        });
                        $('.subCategoryName').css({
                            'color':'green'
                        });
                    }
                    $.notify("Category and Sub-category updated successfully!", "success");
                },
                complete:function(){
                    //$('.loading').fadeOut();
                },
            });
        });
        //end category , sub category update
    </script>
@endsection
