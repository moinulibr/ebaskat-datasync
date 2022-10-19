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
                        {{-- <a href="{{ route('admin.report.product.merchant-wise') }}" class="btn btn-secondary">Merchant wise</a> --}}
                        {{-- <a href="{{ route('admin.report.product.stock-wise') }}" class="btn btn-secondary">Stock wise</a> --}}
                        <a href="{{ route('admin.report.product.best-sell') }}" class="btn btn-secondary">Best sell</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">

                <div>
                    <form id="form_csv" action="{{ route('admin.report.product.list') }}">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                          <label class="font-weight-bold">Sell Price Range (from)</label>
                          <input type="number" class="form-control" name="sell_from" id="sell_price_from" placeholder="Write Price">
                        </div>
                        <div class="form-group col-md-3">
                          <label class="font-weight-bold">Sell Price Range (to)</label>
                          <input type="number" class="form-control" name="sell_to" id="sell_price_to" placeholder="Write Price">
                        </div>
                        <div class="form-group col-md-3">
                          <label class="font-weight-bold">Date (from)</label>
                          <input type="text" class="form-control datetime" name="from_date" id="from">
                        </div>
                        <div class="form-group col-md-3">
                          <label class="font-weight-bold">Date (to)</label>
                          <input type="text" class="form-control datetime" name="to_date" id="to">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-3">
                          <label class="font-weight-bold">Merchant</label>
                          <select id="merchant_id" name="merchant_id">
                            <option value="">Select One</option>
                            @foreach ($merchant_list as $item)
                                <option value="{{$item->user_id}}">{{$item->name}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="form-group col-3">
                          <label class="font-weight-bold">Category</label>
                          <select id="category_id" name="category_id">
                            <option value="">Select One</option>
                            @foreach ($category as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                        </div>

                        <div class="form-group col-3">
                          <label class="font-weight-bold">Sub Category</label>
                          <select id="subcategory_id" name="subcategory_id">
                                <option value="">Select One</option>
                                @foreach ($subcategory as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                          </select>
                        </div>

                        <div class="form-group col-3">
                          <label class="font-weight-bold">Status</label>
                          <select id="status" name="status">
                                <option value="">Select One</option>
                                <option value="1">Active</option>
                                <option value="0">In Active</option>
                          </select>
                        </div>
                        <div class="form-group col-3">
                            <button id="filter_btn" class="btn btn-sm btn-info">Filter Now</button>
                            <button id="filter_btn_clear" class="btn btn-sm btn-danger">Clear Filter</button>
                          </div>

                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mr-table allproduct">
                            <div>
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="width: 30%">
                                            <select class="form-control paginate" id="paginate" name="paginate" style="font-size: 12px;width:30%;">
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
                                        
                                        <td style="width: 40%">
                                            <input type="text" class="search form-control" name="search" autofocus>
                                        </td>
                                        <td class="w-25 text-right">
                                            
                                            <input type="text" id="csv" name="csv" hidden>
                                            <button class="btn btn-secondary btn-sm" id="csv_button"> <i class="fa fa-download"></i> CSV</button>
                                            
                                        </td>
                                        {{-- <td style="width: 30%;text-align: right;">
                                            <a class="add-btn" href="{{route('admin.product.physical.create')}}"><i class="fas fa-plus"></i> <span class="remove-mobile">Add New Product<span></span></span></a>
                                        </td> --}}
                                    </tr>
                                </table>
                            </div>
                        </form>
                            <div class="productListAjaxResult" style="font-family: sans-serif">
                                <div class="table-responsive">
                                    <table id="report_table" class="table table-hover dt-responsive" cellspacing="0"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                {{-- <th>{{ __('Sl') }}</th> --}}
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Sku') }}</th>
                                                <th>{{ __('Buy Pirce') }}</th>
                                                <th>{{ __('Sell Price') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Stock') }}</th>
                                                <th class="not-export">{{ __('Status') }}</th>
                                                {{-- <th>{{ __('Status') }}</th> --}}
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
    {{-- permissions --}}
    <input type="hidden" id="can_add" value="{{ \Auth::guard('admin')->user()->role->permissionCheck('products|add') }}">
    <input type="hidden" id="can_edit" value="{{ \Auth::guard('admin')->user()->role->permissionCheck('products|edit') }}">
    
    <input type="hidden" class="productListByAjaxResponseUrl" value="{{ route('admin.report.product.list.ajaxresponse') }}">
    <input type="hidden" class="editCategoryUrlForOpeningModal" value="{{ route('admin.product.category.edit') }}">
    <input type="hidden" class="subCategoryByCategoryUrl" value="{{ route('admin.product.subcat.by.categoryid') }}">

@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/buttons.html5.min.js') }}"></script>

    {{---product list by ajax---}}
    <script src="{{asset('custom_js/product/report.js')}}"></script>
    {{---product list by ajax---}}
    
    <script type="text/javascript">
       

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
        $( document ).ready(function() {
            $('#csv').val(0);
        });
        
        
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

        // csv file download
        $(document).on('click','#csv_button',function(e){
            e.preventDefault();
            $('#csv').val(1);
            // var csv = $('#csv')
            $('#form_csv').submit();
        });
</script>
    {{---product short description---}}


   


@endpush
