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

                        <div class="table-responsiv">
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        {{-- <th>{{ __('Type') }}</th>
                                        <th>{{ __('Stock') }}</th>
                                        <th>{{ __('Price') }}</th> --}}
                                        <th>{{ __('Just in') }}</th>
                                        <th>{{ __('Weekly Deals') }}</th>
                                        <th>{{ __('Trending Products') }}</th>
                                        <th>{{ __('Top Kids & Baby Products') }}</th>
                                        <th>{{ __('Featured Phones & Accessories') }}</th>
                                        <th>{{ __('The Beauty Editors Pick') }}</th>
                                        {{-- <th>{{ __('Options') }}</th> --}}
                                    </tr>
                                </thead>
                            </table>
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
@endsection



@section('scripts')

    <script type="text/javascript">
        var table = $('#geniustable').DataTable({
            ordering: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.product.promotion.datatables') }}',
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'just_in',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'weekly_deals',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'trending_products',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'top_kids_baby_products',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'featured_phones_accessories',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'the_beauty_editors_pick',
                    searchable: false,
                    orderable: false
                }
                // {
                //     data: 'action',
                //     searchable: false,
                //     orderable: false
                // }

            ],
            language: {
                processing: '<img src="{{ asset('assets/images/xloading.gif') }}">'
            },
            drawCallback: function(settings) {
                $('.select').niceSelect();
            }
        });

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
