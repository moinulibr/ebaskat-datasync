@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __('PRODUCT') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Products') }}</h4>
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
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">
                        @include('includes.form-success')
                        
                        <div>
                            <table style="width: 100%">
                                <tr>
                                    <td style="width: 30%"></td>
                                    <td style="width: 40%">
                                        <input type="text" class="search form-control" autofocus>
                                    </td>
                                    <td style="width: 30%;text-align: right;">
                                        <a class="add-btn" href="{{route('admin.product.physical.create')}}"><i class="fas fa-plus"></i> <span class="remove-mobile">Add New Product<span></span></span></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="productListAjaxResult">
                            <div class="table-responsiv">
                                <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
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

    {{-- DELETE MODAL --}}
    @include('includes.delete-modal', ['type' => 'Products'])
    {{-- Resote Modal --}}
    @include('includes.restore_modal', ['type' => 'Products'])

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

    {{-- DATA TABLE --}}
    <script type="text/javascript">

        $(document).ready(function(){
            var url = '{{ route('admin.prod.datatables') }}'
            url = url+'/'+'';
            $("#geniustable").dataTable().fnDestroy();
            datatable(url);
        });

        $(document).on('keyup keypress','.search',function(){
            $("#geniustable").dataTable().fnDestroy();
            var search = $(this).val();
            var url = '{{ route('admin.prod.datatables') }}'
            url = (url+'/'+search);
            datatable(url);
        });

        function datatable(url)
        {
            $.fn.dataTableExt.pager.numbers_length = 50;
            var table = $('#geniustable').DataTable({
            ordering: true,
            processing: true,
            serverSide: true,
            pageLength: 50,
            "searching": false,
            ajax: url,
            columns: [
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'category',
                    name: 'category'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'stock',
                    name: 'stock'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'status',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'action',
                    searchable: false,
                    orderable: false
                }

            ],
                language: {
                    processing: '<img src="{{ asset('assets/images/xloading.gif') }}">'
                },
                drawCallback: function (settings) {
                    $('.select').niceSelect();
                },
            });
        }

        



        if (!+$('#can_edit').val()) {
            // status -> 4
            table.column(4).visible(0);
        }

		/* if($('#can_add').val())
        $(function() {
            $(".btn-area").append('<div class="col-sm-4 table-contents">' +
                '<a class="add-btn" href="{{ route('admin.product.physical.create') }}">' +
                '<i class="fas fa-plus"></i> <span class="remove-mobile">{{ __('Add New Product') }}<span>' +
                '</a>' +
                '</div>');
        }); */
    </script>
       {{--  '<a class="add-btn" href="{{ route('admin.product.physical.create') }}">' +
        '<i class="fas fa-plus"></i> <span class="remove-mobile">{{ __('Add New Product') }}<span>' +
        '</a>' --}}

    <script type="text/javascript">
        // Gallery Section Update

        $(document).on("click", ".set-gallery", function() {
            var pid = $(this).find('input[type=hidden]').val();
            $('#pid').val(pid);
            $('.selected-image .row').html('');
            $.ajax({
                type: "GET",
                url: "{{ route('admin-gallery-show') }}",
                data: {
                    id: pid
                },
                success: function(data) {
                    if (data[0] == 0) {
                        $('.selected-image .row').addClass('justify-content-center');
                        $('.selected-image .row').html('<h3>{{ __('No Images Found.') }}</h3>');
                    } else {
                        $('.selected-image .row').removeClass('justify-content-center');
                        $('.selected-image .row h3').remove();
                        var arr = $.map(data[1], function(el) {
                            return el
                        });

                        for (var k in arr) {
                            $('.selected-image .row').append('<div class="col-sm-6">' +
                                '<div class="img gallery-img">' +
                                '<span class="remove-img"><i class="fas fa-times"></i>' +
                                '<input type="hidden" value="' + arr[k]['id'] + '">' +
                                '</span>' +
                                '<a href="' + '{{ asset('storage/galleries') . '/' }}' + arr[k]
                                ['photo'] + '" target="_blank">' +
                                '<img src="' + '{{ asset('storage/galleries') . '/' }}' + arr[
                                    k]['photo'] + '" alt="gallery image">' +
                                '</a>' +
                                '</div>' +
                                '</div>');
                        }
                    }

                }
            });
        });


        $(document).on('click', '.remove-img', function() {
            var id = $(this).find('input[type=hidden]').val();
            $(this).parent().parent().remove();
            $.ajax({
                type: "GET",
                url: "{{ route('admin-gallery-delete') }}",
                data: {
                    id: id
                }
            });
        });

        $(document).on('click', '#prod_gallery', function() {
            $('#uploadgallery').click();
        });


        $("#uploadgallery").change(function() {
            $("#form-gallery").submit();
        });

        $(document).on('submit', '#form-gallery', function() {
            $.ajax({
                url: "{{ route('admin-gallery-store') }}",
                method: "POST",
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data != 0) {
                        $('.selected-image .row').removeClass('justify-content-center');
                        $('.selected-image .row h3').remove();
                        var arr = $.map(data, function(el) {
                            return el
                        });
                        for (var k in arr) {
                            $('.selected-image .row').append('<div class="col-sm-6">' +
                                '<div class="img gallery-img">' +
                                '<span class="remove-img"><i class="fas fa-times"></i>' +
                                '<input type="hidden" value="' + arr[k]['id'] + '">' +
                                '</span>' +
                                '<a href="' + '{{ asset('storage/galleries') . '/' }}' + arr[k]
                                ['photo'] + '" target="_blank">' +
                                '<img src="' + '{{ asset('storage/galleries') . '/' }}' + arr[
                                    k]['photo'] + '" alt="gallery image">' +
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
