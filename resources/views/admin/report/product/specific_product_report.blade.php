@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')

    <input type="hidden" id="headerdata" value="{{ __('Specific Product Report') }}">

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-6">
                    <h4 class="heading">{{ __('Specific Product Report') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.all.report') }}">{{ __('Reports') }}</a>
                        </li>
                        <li>
                            {{ __('Specific Product Report') }}
                        </li>
                    </ul>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="btn-group float-right" role="group">
                        <a href="{{ route('admin.report.product.list') }}" class="btn btn-secondary">Product list</a>
                        <a href="{{ route('admin.report.product.specific') }}" class="btn btn-info">Specific</a>
                        {{-- <a href="{{ route('admin.report.product.merchant-wise') }}" class="btn btn-secondary">Merchant wise</a>
                        <a href="{{ route('admin.report.product.stock-wise') }}" class="btn btn-secondary">Stock wise</a> --}}
                        <a href="{{ route('admin.report.product.best-sell') }}" class="btn btn-secondary">Best sell</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="form-row">
                    <div class="form-group col-md-4">
                      <label class="font-weight-bold">Product SKU or Name</label>
                      <select id="product_list" class="form-control">
                        <option value="">Type Product</option>
                    </select>
                    </div>
                    <div class="form-group col-md-3">
                      <label class="font-weight-bold">Report</label> <br>
                      <button id="filter_btn" class="btn btn-sm btn-info">Filter Now</button>
                    </div>
                </div>
            </div>

            <div class="alert alert-success validation" style="display: none;">
                <button type="button" class="close alert-close"><span>×</span></button>
                <p class="text-left mb-0"></p>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mr-table allproduct">
                            <div class="row pb-3">
                                <div class="col-2">Name</div>
                                <div class="col-9">: <span id="prd_name"></span> </div>
                            </div> 
                            <div class="row pb-3">
                                <div class="col-2">SKU</div>
                                <div class="col-9">: <span id="prd_sku"></span> </div>
                            </div>
                            <div class="row pb-3">
                                <div class="col-2">Details</div>
                                <div class="col-9">: <span id="prd_des"></span> </div>
                            </div>
                            <div class="row pb-3">
                                <div class="col-2">Total Profit</div>
                                <div class="col-9">: <span id="profit"></span> </div>
                            </div>
                            <div class="row pb-3">
                                <div class="col-2">Total Sell Amount</div>
                                <div class="col-9">: <span id="sell"></span> </div>
                            </div>
                            <div class="row pb-3">
                                <div class="col-2">Action</div>
                                <div class="col-9">: 
                                    <span id="action"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




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
                    <p class="text-center">{{ __('You are about to delete this ') }}.</p>
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

    <input type="hidden" class="subCategoryByCategoryUrl" value="{{ route('admin.product.subcat.by.categoryid') }}">
@endsection

@push('scripts')
    <script type="text/javascript">
        $('#product_list').select2({
            'minimumInputLength': 3,
            ajax: {
                url: "{{ route('admin.report.product.specific') }}",
                data: function(params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            }
        });

        $('#product_list').on('select2:select', function (e) {
            $.ajax({
                url: "{{ route('admin.report.product.specific') }}",
                data: {find_id: e.params.data.id},
                success: function(data){
                    $('#prd_name').text(data.name);
                    $('#prd_sku').text(data.sku);
                    $('#prd_des').text(data.meta_description);
                    $('#profit').text(0);
                    $('#sell').text(0);
                    if(data.id)
                    {
                        $('#action').append('<span class="btn btn-sm btn-danger delete" id="actionId" style="margin-right:5px;">Delete</span>');
                        $('#actionId').attr('data-id',data.id);
                        $('#action').append('<span class="btn btn-sm btn-info productShortDetail" id="actionViewId" style="margin-right:5px;">View</span>');
                        $('#actionViewId').attr('data-id',data.id); 
                        $('#action').append('<span class="btn btn-sm btn-primary categoryEdit " id="actionEditCatId" style="margin-right:5px;">Edit Category</span>');
                        $('#actionEditCatId').attr('data-id',data.id);
                    }
                     //console.log(data); admin.product.delete 
                },
                error: function (data) { 
                    alert('error happened');
                }
            });
        });


        //product delete
        $(document).on('click','.delete',function(){
            $('#delete_modal').modal('show');
        });
        $(document).on('click','.delete-button',function(){
            var id = $(".delete").data('id');
            $('#delete_modal').modal('hide');
            $.ajax({
                url: "{{ route('admin.destroy.product') }}",
                data: {id:id},
                success: function(data){
                    if(data.status == true)
                    {
                        $('.alert-success').show();
                        $('.text-left').text(data.msg);
                        location.reload(); 
                    }
                },
                error: function (data) { 
                    alert('error happened');
                }
            });
        });
        //product delete
    </script>

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

        //subcategory by category when change category from category edit
        $(document).on('change','.categoryId_from_edit',function(){
            var catid = $('#category_id_from_edit option:selected').val();
            $.ajax({
                url: "{{ route('admin.product.subcat.by.categoryid') }}",
                data: {catid:catid},
                success: function(response){
                    if(response.status == true)
                    {
                        $('#subcategory_id_from_edit').html(response.html);
                    }
                },
                error: function (data) { 
                    alert('error happened');
                }
            });
        });
        //subcategory by category when change category from category edit


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


@endpush
