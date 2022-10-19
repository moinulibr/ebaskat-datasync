@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')

    <input type="hidden" id="headerdata" value="{{ __('Product List Report') }}">

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-5">
                    <h4 class="heading">{{ __('Product List Report') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.all.report') }}">{{ __('Reports') }}</a>
                        </li>
                        <li>
                            {{ __('Product List Report') }}
                        </li>
                    </ul>
                </div>
                <div class="col-md-7 mt-3">
                    <div class="btn-group float-right" role="group">
                        <a href="{{ route('admin.report.product.list') }}" class="btn btn-info">Product list</a>
                        <a href="{{ route('admin.report.product.specific') }}" class="btn btn-secondary">Specific Product</a>
                        <a href="{{ route('admin.report.product.merchant-wise') }}" class="btn btn-secondary">Merchant wise</a>
                        <a href="{{ route('admin.report.product.stock-wise') }}" class="btn btn-secondary">Stock wise</a>
                        <a href="{{ route('admin.report.product.best-sell') }}" class="btn btn-secondary">Best sell</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">

                <div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                          <label class="font-weight-bold">Sell Price Range (from)</label>
                          <input type="number" class="form-control" id="sell_price_from" placeholder="400">
                        </div>
                        <div class="form-group col-md-3">
                          <label class="font-weight-bold">Sell Price Range (to)</label>
                          <input type="number" class="form-control" id="sell_price_to" placeholder="1200">
                        </div>
                        <div class="form-group col-md-3">
                          <label class="font-weight-bold">Date (from)</label>
                          <input type="text" class="form-control datetime" id="from">
                        </div>
                        <div class="form-group col-md-3">
                          <label class="font-weight-bold">Date (to)</label>
                          <input type="text" class="form-control datetime" id="to">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-3">
                          <label class="font-weight-bold">Merchant</label>
                          <select id="merchant_id">
                            <option value="">Select One</option>
                            @foreach ($merchant_list as $item)
                                <option value="{{$item->user_id}}">{{$item->name}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="form-group col-3">
                          <label class="font-weight-bold">Category</label>
                          <select id="category_id">
                            <option value="">Select One</option>
                            @foreach ($category as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                        </div>

                        <div class="form-group col-3">
                          <label class="font-weight-bold">Sub Category</label>
                          <select id="subcategory_id">
                                <option value="">Select One</option>
                                @foreach ($subcategory as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                          </select>
                        </div>

                        <div class="form-group col-3">
                          <label class="font-weight-bold">Status</label>
                          <select id="status">
                                <option value="">Select One</option>
                                <option value="1">Active</option>
                                <option value="0">In Active</option>
                          </select>
                        </div>
                        <div class="form-group col-3">
                            <button id="filter_btn" class="btn btn-sm btn-info">Filter Now</button>
                          </div>

                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mr-table allproduct">
                            <div class="table-responsive">
                                <table id="report_table" class="table table-hover dt-responsive" cellspacing="0"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Sl') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Sku') }}</th>
                                            <th>{{ __('Buy Pirce') }}</th>
                                            <th>{{ __('Sell Price') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Stock') }}</th>
                                            <th class="not-export">{{ __('Status') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th class="not-export">{{ __('Action') }}</th>
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

@push('scripts')
    <script src="{{ asset('assets/admin/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/buttons.html5.min.js') }}"></script>
    
    <script type="text/javascript">
        var table = $('#report_table').DataTable({
            dom: 'lBfrtip',
            ordering: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.report.product.list') }}",
                data: function(data){
                    data.sell_from = $('#sell_price_from').val();
                    data.sell_to = $('#sell_price_to').val();
                    data.from_date = $('#from').val();
                    data.to_date = $('#to').val();
                    data.merchant_id = $('#merchant_id :selected').val();
                    data.category_id = $('#category_id :selected').val();
                    data.subcategory_id = $('#subcategory_id :selected').val();
                    data.status = $('#status :selected').val();
                    return data;
                }
            },
            columns: [
                {
                    data: null,
                    render: function(data, type, row) {
                        return row.DT_RowIndex;
                    }
                },
                {
                    data: 'name'
                },
                {
                    data: 'sku'
                },
                
                {
                    data: 'price'
                },
                {
                    data: 'commission',
                    render: function(data, temp, row) {
                        return row.price + row.commission || 0;
                    }
                },
                {
                    data: 'date'
                },
                {
                    data: 'stock'
                },
               
                {
                    data: 'status_change',
                },
                {
                    data: 'status',
                    render: function(data, temp, row){
                        return data ? "Active":"Deactive";
                    }
                },
                {
                    data: 'action',
                }
            ],
            buttons: [{
                extend: 'csvHtml5',
                text: '<i class="icofont-upload-alt"></i> CSV',
                titleAttr: 'CSV',
                title: 'Product List Report',
                exportOptions: {
                    columns: ':not(.not-export)',
                }
            }],
            "columnDefs": [
                {
                    "targets": [8],
                    "visible": false,
                }
            ],
            language: {
                processing: `<img src="{{ asset('assets/images/xloading.gif') }}">`
            },
            lengthMenu: [
                [10, 25, 100, -1],
                [10, 25, 100, "All"]
            ]
        });

        // filter button
        $('#filter_btn').click(function() {
            table.draw();
        });

        $('#category_id').change(function (e) { 
            let url = "{{route('admin-get-cat-subcat')}}/" + $('#category_id :selected').val();

            $.ajax({
                url: url,
                success: function (res) {

                    let opt = `<option value="">Select One</option>`;
                    res.forEach(el => {
                        opt += `<option value="`+el.id+`">`+ el.name +`</option>`; 
                    });
                    $('#subcategory_id').html(opt);
                }
            });
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
