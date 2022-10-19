@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __('PRODUCT') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Un-published Products') }}</h4>
                    <ul class="links">
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
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5"></div>
                <div class="col-lg-2">
                    <img src="{{ asset('storage/xloading.gif') }}" alt="" class="loading mr-5" style="display: none;">
                </div>
                <div class="col-lg-5"></div>
            </div>
            
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">
                        @include('includes.form-success')
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <input type="text" class="pname form-control" autofocus placeholder="Search by product name / sku">
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                        
                            <div style="text-align: center">
                                <span class="loadingText">Please wait...</span>
                            </div>
                            
                        
                        <div class="result">
                            <div class="row" style="margin-top:.5% ">
                                <div class="col-md-6">
                                    <button class="publishedAllProduct btn btn-sm btn-primary" style="display: none;">Published All Product</button>
                                    <button class="deletedAllProduct btn btn-sm btn-danger" style="display: none;">Delete All Product</button>        
                                </div>
                                <div class="col-md-6"></div>
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
        {{-- detail MODAL --}}

        {{-- published MODAL --}}
        <div class="modal fade" id="published_modal" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
        
                    <div class="modal-header d-block text-center">
                        <h4 class="modal-title d-inline-block">{{ __('Confirm Published') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
        
                    <!-- Modal body -->
                    <div class="modal-body">
                        <p class="text-center">{{ __('You are about to published all.') }}</p>
                        <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                    </div>
        
                    <!-- Modal footer -->
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <a class="btn btn-primary btn-submit published-button">{{ __('Published') }}</a>
                    </div>
        
                </div>
            </div>
        </div>
        {{-- published MODAL --}}

        {{-- delete MODAL --}}
        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
        
                    <div class="modal-header d-block text-center">
                        <h4 class="modal-title d-inline-block">{{ __('Confirm Delete') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
        
                    <!-- Modal body -->
                    <div class="modal-body">
                        <p class="text-center">{{ __('You are about to delete all.') }}</p>
                        <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                    </div>
        
                    <!-- Modal footer -->
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <a class="btn btn-danger btn-submit delete-button">{{ __('Delete') }}</a>
                    </div>
        
                </div>
            </div>
        </div>
        {{-- delete MODAL --}}

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

@endsection



@section('scripts')
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

        $(document).on('click','.promotion_level',function(){
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.product.promotion') }}",
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
    {{---product short description---}}


    <script>
        //product list with pagination
            function defaultLoading(page_no)
            {
                var createUrl = "{{ route('admin.products.unpublished.list.ajax.response') }}";
                var url =  createUrl+"?page="+page_no;
                $.ajax({
                    url: url,
                    beforeSend:function(){
                        //$('.loading').fadeIn();
                    },
                    success: function(response){
                        if(response.status == true)
                        {
                            $('.result').html(response.data);
                            $('.loadingText').hide();
                        }
                    },
                complete:function(){
                        //$('.loading').fadeOut();
                    },
                    error: function (data) { 
                        alert('error happened');
                    },
                });
            }
            $(document).ready(function(){
                var page_no         = parseInt($('.page_no').val());
                defaultLoading(page_no);
            });


            $(document).on("click",".pagination li a",function(e){
                e.preventDefault();
                var page = $(this).attr('href');
                var pageNumber = page.split('?page=')[1];
                return getPagination(pageNumber);
            });

                function getPagination(pageNumber){
                    var createUrl = "{{ route('admin.products.unpublished.list.ajax.response') }}";
                    var url =  createUrl+"?page="+pageNumber;
                    var pname = $('.pname').val();
                    $.ajax({
                        url: url,
                        type: "GET",
                        datatype:"HTML",
                        data: {pname:pname},
                        success: function(response){
                            if(response.status == true)
                            {
                                $('.result').html(response.data);
                            }
                        },
                    });
                }

            $(document).on('keyup change','.pname',function(){
                var pname = $('.pname').val();
                $.ajax({
                    url: "{{ route('admin.products.unpublished.list.ajax.response') }}",
                    data: {pname:pname},
                    beforeSend:function(){
                        $('.loading').fadeIn();
                        $('.loadingText').show();
                    },
                    success: function(response){
                        if(response.status == true)
                        {
                            $('.result').html(response.data);
                        }
                    },
                    complete:function(){
                        $('.loading').fadeOut();
                        $('.loadingText').hide();
                    },
                    error: function (data) { 
                        alert('error happened');
                    }
                });
            });
        //product list with pagination


        // checked all order list 
            $(document).on('click','.check_all_class',function()
            {
                if (this.checked == false)
                {   
                    $('.publishedAllProduct').hide();
                    $('.deletedAllProduct').hide();
                    $('.check_single_class').prop('checked', false).change();
                    $(".check_single_class").each(function ()
                    {
                        var id = $(this).attr('id');
                        $(this).val('').change();
                    });
                }
                else
                {
                    $('.publishedAllProduct').show();
                    $('.deletedAllProduct').show();
                    $('.check_single_class').prop("checked", true).change();
                    $(".check_single_class").each(function ()
                    {
                        var id = $(this).attr('id');
                        $(this).val(id).change();
                    });
                }
            });
        // checked all order list 

        
        //check single order list
            $(document).on('click','.check_single_class',function()
            {
                var $b = $('input[type=checkbox]');
                if($b.filter(':checked').length <= 0)
                {
                    $('.publishedAllProduct').hide();
                    $('.deletedAllProduct').hide();
                    $('.check_all_class').prop('checked', false).change();
                }

                var id = $(this).attr('id');
                if (this.checked == false)
                {
                    $(this).prop('checked', false).change();
                    $(this).val('').change();
                }else{
                    $('.publishedAllProduct').show();
                    $('.deletedAllProduct').show();

                    $(this).prop("checked", true).change();
                    $(this).val(id).change();
                }
                
                var ids = [];
                $('input.check_single_class[type=checkbox]').each(function () {
                    if(this.checked){
                        var v = $(this).val();
                        ids.push(v);
                    }
                });
                if(ids.length <= 0)
                {
                    $('.publishedAllProduct').hide();
                    $('.deletedAllProduct').hide();
                    $('.check_all_class').prop('checked', false).change();
                }
            });
        //check single order list


        //bulk product published (route for all checked product published)
            $(document).on('click', '.publishedAllProduct', function (){
                $('.alert-success').hide();
                $('#published_modal').modal('show');
            });
            //$(document).on('click', '.publishedAllProduct', function (){
            $(document).on('click', '.published-button', function (){
                var ids = [];
                $('input.check_single_class[type=checkbox]').each(function () {
                    if(this.checked){
                        var v = $(this).val();
                        ids.push(v);
                    }
                });
                var url =  "{{ route('admin.unpublished.products.publishing') }}";

                if(ids.length <= 0) return ;
                var page_no         = $('.page_no').val();
                $.ajax({
                    url: url,
                    data: {ids: ids,page_no:page_no},
                    type: "POST",
                    beforeSend:function(){
                        $('#published_modal').modal('hide');
                        $('.loading').fadeIn();
                        $('.loadingText').show();
                    },
                    success: function(response){
                        if(response.status == true)
                        {
                            $('.alert-success').show();
                            $('.text-left').text(response.message);
                            defaultLoading(page_no);
                        }
                    },
                    complete:function(){
                        $('.loading').fadeOut();
                        $('.loadingText').hide();
                    },
                });
            });
        //bulk product published end 



        //bulk product deleting (route for all checked product deleting)
            $(document).on('click', '.deletedAllProduct', function (){
                $('.alert-success').hide();
                $('#delete_modal').modal('show');
            });
            $(document).on('click', '.delete-button', function (){
                var ids = [];
                $('input.check_single_class[type=checkbox]').each(function () {
                    if(this.checked){
                        var v = $(this).val();
                        ids.push(v);
                    }
                });
                var url =  "{{ route('admin.unpublished.products.deleting') }}";

                if(ids.length <= 0) return ;
                var page_no         = $('.page_no').val();
                $.ajax({
                    url: url,
                    data: {ids: ids,page_no:page_no},
                    type: "POST",
                    beforeSend:function(){
                        $('#delete_modal').modal('hide');
                        $('.loading').fadeIn();
                        $('.loadingText').show();
                    },
                    success: function(response){
                        if(response.status == true)
                        {
                            $('.alert-success').show();
                            $('.text-left').text(response.message);
                            defaultLoading(page_no);
                        }
                    },
                    complete:function(){
                        $('.loading').fadeOut(); 
                        $('.loadingText').hide();
                    },
                });
            });
        //bulk product deleting end
    </script>


    <script>
        //show category edit modal
        $(document).on('click','.categoryEdit',function(){
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.product.category.edit') }}",
                data: {id:id},
                success: function(response){
                    if(response.status == true)
                    {
                        $('.containt-data-category-edit').html(response.data);
                        $('#categorySubCategoryEditModal').modal('show'); 
                    }
                },
                error: function (data) { 
                    alert('error happened');
                }
            });
        });
        //show category edit modal

        //subcategory by category
        $(document).on('change','.categoryId',function(){
            var catid = $('#category_id option:selected').val();
            $.ajax({
                url: "{{ route('admin.product.subcat.by.categoryid') }}",
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
        //subcategory by category

        //update category
        $(document).on("submit",'.updateSingleCategory',function(e){
            e.preventDefault();
            var form = $(this);
            var url = form.attr("action");
            var type = form.attr("method");
            var data = form.serialize();
            var page_no = $('.page_no').val();
            $.ajax({
                url: url,
                data: data,
                type: type,
                datatype:"JSON",
                beforeSend:function(){
                    //$('.loading').fadeIn();
                },
                success: function(response){
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
        //end ajax
    </script>






@endsection
