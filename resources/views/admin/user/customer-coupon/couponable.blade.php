
@extends('layouts.admin')

@section('content')
    <input type="hidden" id="headerdata" value="{{ __('PRODUCT') }}">
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Customers Coupons') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Customer') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.user.index') }}">{{ __('All Customer') }}</a>
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
                                <input type="text" class="pname form-control" autofocus placeholder="Search by  name / email / phone">
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                        
                            <div style="text-align: center">
                                <span class="loadingText">Please wait...</span>
                            </div>
                            
                        
                        <div class="result">
                            <div class="row" style="margin-top:.5% ">
                                <div class="col-md-6">
                                    <button class="activateAllCustomer btn btn-sm btn-primary" style="display: none;">Published All Product</button>
                                    <button class="inactivateAllCustomer btn btn-sm btn-danger" style="display: none;">Delete All Product</button>        
                                </div>
                                <div class="col-md-6"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



    


        {{-- Activate MODAL --}}
        <div class="modal fade" id="activate_modal" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
        
                    <div class="modal-header d-block text-center">
                        <h4 class="modal-title d-inline-block">{{ __('Confirm Activate') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
        
                    <!-- Modal body -->
                    <div class="modal-body">
                        <p class="text-center">{{ __('You are about to activate all.') }}</p>
                        <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                    </div>
        
                    <!-- Modal footer -->
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <a class="btn btn-primary btn-submit activate-button">{{ __('Activate') }}</a>
                    </div>
        
                </div>
            </div>
        </div>
        {{-- published MODAL --}}

        {{-- Inactivate MODAL --}}
        <div class="modal fade" id="inactive_modal" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
        
                    <div class="modal-header d-block text-center">
                        <h4 class="modal-title d-inline-block">{{ __('Confirm Deactivate') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
        
                    <!-- Modal body -->
                    <div class="modal-body">
                        <p class="text-center">{{ __('You are about to deactivate all.') }}</p>
                        <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                    </div>
        
                    <!-- Modal footer -->
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <a class="btn btn-danger btn-submit inactivate-button">{{ __('Deactivate') }}</a>
                    </div>
        
                </div>
            </div>
        </div>
        {{-- delete MODAL --}}
@endsection



@section('scripts')
    

    <script>
        //customer list with pagination
            function defaultLoading()
            {
                $.ajax({
                    url: "{{ route('admin.user.couponable.list.ajax.response') }}",
                    success: function(response){
                        if(response.status == true)
                        {
                            $('.result').html(response.data);
                            $('.loadingText').hide();
                        }
                    },
                    error: function (data) { 
                        alert('error happened');
                    },
                });
            }
            $(document).ready(function(){
                defaultLoading();
            });


            $(document).on("click",".pagination li a",function(e){
                e.preventDefault();
                var page = $(this).attr('href');
                var pageNumber = page.split('?page=')[1];
                return getPagination(pageNumber);
            });

                function getPagination(pageNumber){
                    var createUrl = "{{ route('admin.user.couponable.list.ajax.response') }}";
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
                    url: "{{ route('admin.user.couponable.list.ajax.response') }}",
                    data: {pname:pname},
                    success: function(response){
                        if(response.status == true)
                        {
                            $('.result').html(response.data);
                        }
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
                    $('.activateAllCustomer').hide();
                    $('.inactivateAllCustomer').hide();
                    $('.check_single_class').prop('checked', false).change();
                    $(".check_single_class").each(function ()
                    {
                        var id = $(this).attr('id');
                        $(this).val('').change();
                    });
                }
                else
                {
                    $('.activateAllCustomer').show();
                    $('.inactivateAllCustomer').show();
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
                    $('.activateAllCustomer').hide();
                    $('.inactivateAllCustomer').hide();
                    $('.check_all_class').prop('checked', false).change();
                }

                var id = $(this).attr('id');
                if (this.checked == false)
                {
                    $(this).prop('checked', false).change();
                    $(this).val('').change();
                }else{
                    $('.activateAllCustomer').show();
                    $('.inactivateAllCustomer').show();

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
                    $('.activateAllCustomer').hide();
                    $('.inactivateAllCustomer').hide();
                    $('.check_all_class').prop('checked', false).change();
                }
            });
        //check single order list


        //bulk product published (route for all checked product published)
            $(document).on('click', '.activateAllCustomer', function (){
                $('.alert-success').hide();
                $('#activate_modal').modal('show');
            });
            //$(document).on('click', '.activateAllCustomer', function (){
            $(document).on('click', '.activate-button', function (){
                var ids = [];
                $('input.check_single_class[type=checkbox]').each(function () {
                    if(this.checked){
                        var v = $(this).val();
                        ids.push(v);
                    }
                });
                var url =  "{{ route('admin.user.couponable.bulk.activate') }}";

                if(ids.length <= 0) return ;
            
                $.ajax({
                    url: url,
                    data: {ids: ids},
                    type: "POST",
                    beforeSend:function(){
                        $('#activate_modal').modal('hide');
                        $('.loading').fadeIn();
                        $('.loadingText').show();
                    },
                    success: function(response){
                        if(response.status == true)
                        {
                            $('.alert-success').show();
                            $('.text-left').text(response.message);
                            defaultLoading();
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
            $(document).on('click', '.inactivateAllCustomer', function (){
                $('.alert-success').hide();
                $('#inactive_modal').modal('show');
            });
            $(document).on('click', '.inactivate-button', function (){
                var ids = [];
                $('input.check_single_class[type=checkbox]').each(function () {
                    if(this.checked){
                        var v = $(this).val();
                        ids.push(v);
                    }
                });
                var url =  "{{ route('admin.user.couponable.bulk.inactivate') }}";

                if(ids.length <= 0) return ;
            
                $.ajax({
                    url: url,
                    data: {ids: ids},
                    type: "POST",
                    beforeSend:function(){
                        $('#inactive_modal').modal('hide');
                        $('.loading').fadeIn();
                        $('.loadingText').show();
                    },
                    success: function(response){
                        if(response.status == true)
                        {
                            $('.alert-success').show();
                            $('.text-left').text(response.message);
                            defaultLoading();
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


@endsection
