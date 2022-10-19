
    
    //default aliexpress order list displaied by this route
    function defaultLoading()
    {
        var defaultUrl      = $('.orderListByAjax').val();
        var page_no         = $('.page_no').val();
        var url             = defaultUrl+"?page="+page_no;

        var pagination      = $('#paginate :selected').val();
        var search          = $('.custom_search').val();
        var start_date      = $('.start_date').val();
        var end_date        = $('.end_date').val();
        $.ajax({
            url: url,
            type: "GET",
            datatype:"HTML",
            data:{
                pagination:pagination,search:search,start_date:start_date,end_date:end_date,status:status
            },
            beforeSend:function(){
                $('.processing_on').fadeIn();
            },
            success: function(response){
                if(response.status == true)
                {
                    $(".ajax-response-result").html(response.data);
                }
            },
            complete:function(){
                $('.processing_on').fadeOut();
            },
        });
    }
    $(document).ready(function(){
        defaultLoading();
    });
    //default aliexpress order list displaied by this route


    // single order place to aliexpress
    $(document).on('click','.adminOrderToAliexpress',function(e){
        $('.singleOrderPlaceRouteWithId').val('');
        e.preventDefault();
        $('.singleOrderPlaceRouteWithId').val($(this).data("href"));
        $('#confirmation_place_single_order_modal').modal('show');
    });
    // single order place to aliexpress
    $(document).on('click','.single-order-confirm-button',function(e){ //adminOrderToAliexpress
        e.preventDefault();
        $('.text-left-ul').html(" ");
        $('.text-left-li').html(" ");

        $('.alertSuccessBulk').hide();
        $('.alertDangerBulk').hide();

        //var url = $(this).data("href");
        var url = $('.singleOrderPlaceRouteWithId').val();
        $('.singleOrderPlaceRouteWithId').val('');
        $.ajax({
            type: 'GET',
            url: url,
            beforeSend:function(){
                $('.loading').fadeIn();
            },
            success: function(response){
                defaultLoading();
                $('#confirmation_place_single_order_modal').modal('hide');
                if(response.status == "success")
                {
                    $('.alertSuccessSingle').show();
                    $('.alert-success').show();
                }else{
                    $('.alertDangerSingle').show();
                    $('.alert-danger').show();
                }
                $('.text-left-single').text(response.message);
            },
            complete:function(){
                $('.loading').fadeOut();
            },
        });
        //end ajax
    });
    $()
    // single order place to aliexpress end


    // checked all order list 
    $(document).on('click','.check_all_class',function()
    {
        if (this.checked == false)
        {   
            $('.allOrderPlaceToAliexpress').hide();
            $('.allOrderSyncStatus').hide();
            $('.check_single_class').prop('checked', false).change();
            $(".check_single_class").each(function ()
            {
                var id = $(this).attr('id');
                $(this).val('').change();
            });
        }
        else
        {
            $('.allOrderPlaceToAliexpress').show();
            $('.allOrderSyncStatus').show();
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
            $('.allOrderPlaceToAliexpress').hide();
            $('.allOrderSyncStatus').hide();
            $('.check_all_class').prop('checked', false).change();
        }

        var id = $(this).attr('id');
        if (this.checked == false)
        {
            $(this).prop('checked', false).change();
            $(this).val('').change();
        }else{
            $('.allOrderPlaceToAliexpress').show();
            $('.allOrderSyncStatus').show();

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
            $('.allOrderPlaceToAliexpress').hide();
            $('.allOrderSyncStatus').hide();
            $('.check_all_class').prop('checked', false).change();
        }
    });
    //check single order list
    


    //bulk order place to aliexpress (route for all checked order place to aliexpress)
    //order place confirmation
    $(document).on('click','.allOrderPlaceToAliexpress',function(){
        $('#confirmation_place_order_modal').modal('show');
    });
    $(document).on('click', '.confirm-button', function (){
        var ids = [];
        $('input.check_single_class[type=checkbox]').each(function () {
            if(this.checked){
                if(! $(this).data('aliex_order_id'))
                {
                    var v = $(this).val();
                    ids.push(v);
                }
            }
        });
        var url = $('.bulkOrderPlaceToAliexpress').val();

        if(ids.length <= 0) return ;
        $('#confirmation_place_order_modal').modal('hide');
        $.ajax({
            url: url,
            data: {ids: ids},
            type: "POST",
            beforeSend:function(){
                $('.loading').fadeIn();
            },
            success: function(response){
                defaultLoading();
                uncheckedAllData();
                $('.text-left-ul').html(" ");
                $('.text-left-li').html(" ");

                $('.alertSuccessSingle').hide();
                $('.alertDangerSingle').hide();
            
                if(response.status == true)
                {
                    orderListByAllSelectedFields();
                    $.each(response.datas, function(i, mess) {
                        if(mess.message_type == 'success')
                        {
                            $('.alertSuccessBulk').show();
                            $('.alert-success').show();
                            $('.text-left-ul').append("<li>"+mess.message+"</li>");
                        }else{
                            $('.alertDangerBulk').show();
                            $('.alert-danger').show();
                            $('.text-left-li').append("<li>"+mess.message+"</li>");
                        }
                    });
                }else{
                    $('.alertDanger').show();
                    $('.alert-danger').show();
                    $('.text-left').text('Order not process to aliexpress');
                }
            },
            complete:function(){
                $('.loading').fadeOut();
            },
        });
    });
    //bulk order place to aliexpress end





    // unchek all method
    function uncheckedAllData()
    {
        $('.check_all_class').prop('checked', false).change();
        $('.check_single_class').val('').change();
        $('.check_single_class').prop('checked', false).change();
        $('.allOrderPlaceToAliexpress').hide();
        $('.allOrderSyncStatus').hide();
    }
    // unchek all method

    //pagination
    $(document).on("click",".pagination li a",function(e){
        e.preventDefault();
        uncheckedAllData();

        var page = $(this).attr('href');
        var pageNumber = page.split('?page=')[1]; 
        return getPagination(pageNumber);
    });//split == delete some things...
    function getPagination(pageNumber){
        var createUrl = $('.orderListByAjax').val();
        var url =  createUrl+"?page="+pageNumber;
        var page_no         = parseInt($('.page_no').val());
        
        var status          = $('.selectedStatus').val();
        var pagination      = $('#paginate :selected').val();
        var search          = $('.custom_search').val();
        var start_date      = $('.start_date').val();
        var end_date        = $('.end_date').val();

        $.ajax({
            url: url,
            type: "GET",
            datatype:"HTML",
            data:{
                pagination:pagination,search:search,start_date:start_date,end_date:end_date,status:status
            },
            beforeSend:function(){
                $('.processing_on').fadeIn();
            },
            success: function(response){
                $(".ajax-response-result").html(response.data);
            },
            complete:function(){
                $('.processing_on').fadeOut();
            },
        });
    }
    //pagination

    //pagination,custom search , start date, end date  event
    var ctrlDown = false,ctrlKey = 17,cmdKey = 91,vKey = 86,cKey = 67; xKey = 88;
    $(document).on('change keyup click','.paginate,.custom_search,.start_date,.end_date,.order_status',function(e){
        var action = 0;
        if($(e.target).prop("name") == "date" && ((e.type)=='change'))
        {
            action = 1;
        }
        else if($(e.target).prop("name") == "paginate" && ((e.type)=='change'))
        {
            action = 1;
        } 
        else if($(e.target).prop("name") == "custom_search" && ((e.type)=='keyup'))
        {
            action = 1;
        } else if($(e.target).prop("name") == "status" && ((e.type)=='click'))
        {
            action = 1;
        }
        else{
            action = 0;
        }
        if(action == 0) return;

        if (e.keyCode == ctrlKey || e.keyCode == cmdKey) ctrlDown = true;
        if (ctrlDown && (e.keyCode == vKey || e.keyCode == cKey || e.keyCode == xKey)) return false;

        var defaultUrl      = $('.orderListByAjax').val();
        var pagination      = $('#paginate :selected').val();
        var search          = $('.custom_search').val();
        var start_date      = $('.start_date').val();
        var end_date        = $('.end_date').val();
        var selectedStatus  = $('.selectedStatus').val();
        var page_no         = $('.page_no').val();
        var status  = "";
        if($(e.target).prop("name") == "status")
        {
            status      = isChangedStatus(this);
        }else{
            status          = selectedStatus;
        }
        
        $.ajax({
            url: defaultUrl,
            type: "GET",
            datatype:"HTML",
            data:{
                pagination:pagination,search:search,start_date:start_date,end_date:end_date,status:status
            },
            beforeSend:function(){
                $('.processing_on').fadeIn();
            },
            success: function(response){
                $(".ajax-response-result").html(response.data);
            },
            complete:function(){
                $('.processing_on').fadeOut();
            },
        });
    });
    //pagination,custom search , start date, end date  event





    /**
     * return status when change status
     */
     function isChangedStatus(thisID) {
        var orderStatus      = $(thisID).data('status');
        if(typeof(orderStatus)  === "undefined")
        {
            orderStatus = "";                
        }
        if(orderStatus)
        {
            statusLabel(orderStatus);
            $('.order_status').attr('id','');
            $('.order_status').attr('class','order_status btn btn-sm btn-secondary');
            if(orderStatus == "all_orders")
            {
                orderStatus = "";
            }else{
                orderStatus = orderStatus;
            }
            $(thisID).removeAttr('class','order_status btn btn-sm btn-secondary');
            $(thisID).attr('class','order_status btn btn-sm btn-primary');
        }
        $('.selectedStatus').val(orderStatus);
        return orderStatus;
    }
      


    /**
     * set Status Label (page label) when change status
     */
    function statusLabel(status)
    {
        if(status == "all_orders")
        {
            $('.status_label').text('All Aliexpress Orders');
            status = "";
        }else{
            $('.status_label').text(status.replace(/\b[a-z]/g, function(txtjq) {
             return txtjq.toUpperCase()}
             ));
        }
    }



    

    /**
     *  show order list after bulk order process
     */
    function orderListByAllSelectedFields()
    {
        var page_no         = $('.page_no').val();
        var createUrl       = $('.orderListByAjax').val();
        var url =  createUrl+"?page="+page_no;

        var pagination      = $('#paginate :selected').val();
        var search          = $('.custom_search').val();
        var start_date      = $('.start_date').val();
        var end_date        = $('.end_date').val();

        var selectedStatus  = $('.selectedStatus').val();
        var status  = "";
        if(selectedStatus)
        {
            if(selectedStatus == "all_orders")
            {
                status = "";
            }else{
                status = selectedStatus;
            }
        }
        
        $.ajax({
            url: url,
            type: "GET",
            datatype:"HTML",
            data:{
                pagination:pagination,search:search,start_date:start_date,end_date:end_date,status:status
            },
            success: function(response){
                $(".ajax-response-result").html(response.data);
            },
        });
    }




    /*
    |----------------------------------------------------------------------
    | Order Package status syncing :  Individually
    | Individually order package syncing 
    |-----------------------------------------------------
    */
        //order status sync confirmation
        $(document).on('click','.orderPackageStatusUpdateBySyncingIndividually',function(e){
            statusAndSyncMessageProcessingHide();
            e.preventDefault();
            $(".email_applicable_when_syncing_for_single_order").prop("checked",false);
            $('.orderPackageStatusUpdateBySyncingSingleOrderUrl').val('');
            $('.orderPackageStatusUpdateBySyncingSingleOrderId').val('');
            $('.orderPackageStatusUpdateBySyncingSingleOrderUrl').val($(this).data('href'));
            $('.orderPackageStatusUpdateBySyncingSingleOrderId').val($(this).data('id'));
            $('#confirmation_single_order_syncing_modal').modal('show');
        });
        $(document).on('click','.confirm_single_order_syning_button',function(){
            statusAndSyncMessageProcessingHide();
            var id  = $('.orderPackageStatusUpdateBySyncingSingleOrderId').val();
            var url = $('.orderPackageStatusUpdateBySyncingSingleOrderUrl').val();
            var email_applicable    = $(".email_applicable_when_syncing_for_single_order").prop("checked");
            email_applicable        = email_applicable == true ? 1 : 0; 
            $.ajax({
                url: url,
                type: "GET",
                datatype:"HTML",
                data:{
                    id:id,email_applicable:email_applicable
                },
                beforeSend:function(){
                    $('.single_syncing_loading').fadeIn();
                },
                success: function(response){
                    defaultLoading();
                    if(response.status == true)
                    {
                        $('.alertSuccess_Individually_processing').show();
                        $('#confirmation_single_order_syncing_modal').modal('hide');
                        $('.alertSuccess_Individually').show();
                        $('.alertSuccessMessage_Individually').text('Order Sync Successfully');
                    }
                },
                complete:function(){
                    $('.single_syncing_loading').fadeOut();
                },
            });
        });
    /*
    |----------------------------------------------------------------------
    | Order Package status syncing :  Individually
    | Individually order package syncing
    |---------------------------------------------------------------------------------
    */


    /*
    |------------------------------------------------------------------
    | Order Package status syncing :  Bulking
    |------------------------------------------------------------------
    */
        //order status sync confirmation
        $(document).on('click','.allOrderSyncStatus',function(){
            $(".email_applicable_when_syncing_for_bulk_order").prop("checked",false);
            statusAndSyncMessageProcessingHide();
            $('#confirmation_bulk_syncing_modal').modal('show');
        });
        //main part
        $(document).on('click', '.confirm-bulk-syning-button', function (){
            var ids = [];
            $('input.check_single_class[type=checkbox]').each(function () {
                if(this.checked){
                    if( $(this).data('aliex_order_id'))
                    {
                        var v = $(this).val();
                        ids.push(v);
                    }
                }
            });
            var url = $('.orderPackageStatusUpdateBySyncingBulking').val();

            var email_applicable    = $(".email_applicable_when_syncing_for_bulk_order").prop("checked");
            email_applicable        = email_applicable == true ? 1 : 0; 

            if(ids.length <= 0) return ;
           
            $.ajax({
                url: url,
                data: {ids: ids,email_applicable:email_applicable},
                type: "POST",
                beforeSend:function(){
                    $('.bulk_syncing_loading').fadeIn();
                },
                success: function(response){
                    if(response.status == true)
                    {
                        defaultLoading();
                        uncheckedAllData();
                        $('#confirmation_bulk_syncing_modal').modal('hide');
                        $('.alertSuccess_Individually_processing').show();
                        $('.alertSuccess_Individually').show();
                        $('.alertSuccessMessage_Individually').text('Order Sync Successfully');
                    }
                    else{
                        alert('Order Not Sync');  
                    }
                },
                complete:function(){
                    $('.bulk_syncing_loading').fadeOut();
                },
            });
        });
    /*
    |------------------------------------------------------------------
    | Order Package status syncing :  Bulking
    |------------------------------------------------------------------
    */


    //----------------------------- x --------------------------------------
    //----------------------------- x --------------------------------------


    /*
    |-----------------------------------------------------------------------
    | Display Delivery Status modal 
    | Order package delivery modal display here
    |----------------------------------------------------------------------
    |----------------------------------------------------------------------
    */
        $(document).on('click','.adminOrderDeliveryStatus',function(){
            statusAndSyncMessageProcessingHide();
            var id = $(this).data('id');//order package id
            var url = $(this).data('href');
            $.ajax({
                url: url,
                type: "GET",
                datatype:"HTML",
                data:{
                    id:id
                },
                beforeSend:function(){
                    $('.processing_on').fadeIn();
                },
                success: function(response){
                    if(response.status == true)
                    {
                        $('#deliveryStatus').modal('show');
                        $(".ajax-response-delivery-status-result").html(response.html);
                    }
                },
                complete:function(){
                    $('.processing_on').fadeOut();
                },
            });
        });
    /*
    |-----------------------------------------------------------------------
    | Display Delivery Status modal 
    | Order package delivery modal display here
    |----------------------------------------------------------------------
    */

    /*
    |------------------------------------------------------------------------
    | Order Package status  
    | Update order package delivery status
    |-----------------------------------------------------
    | Order package Delivery Status update by this method
    |
    */
        $(document).on('click','#order_package_status_update',function(){
            statusAndSyncMessageProcessingHide();
            var order_package_status    = $('#main_order_package_status option:selected').val();
            var order_package_id        = $('.order_package_id').val(); //order package id
            var order_id                = $('.order_id').val(); //order id
            var url = $('.orderPackageStatusUpdate').val();
            var email_applicable    = $(".email_applicable_for_package").prop("checked");
            email_applicable        = email_applicable == true ? 1 : 0; 
            $.ajax({
                url: url,
                type: "GET",
                datatype:"HTML",
                data:{
                    order_package_status:order_package_status,order_package_id:order_package_id,order_id:order_id,email_applicable:email_applicable
                },
                beforeSend:function(){
                    $('.on_processing_main_status').fadeIn();
                },
                success: function(response){
                    if(response.status == true)
                    {
                        $('#deliveryStatus').modal('show');
                        $(".ajax-response-delivery-status-result").html(response.html);
                        $('.alertSuccessMainStatus').show();
                        $('.alert-success').show();
                        $('.text-left-ul').text('Delivery Status Update Successfully');
                    }
                },
                    complete:function(){
                    $('.on_processing_main_status').fadeOut();
                },
            });
        });
    /*
    |------------------------------------------------------------------------
    | Order Package status  
    | Update order package delivery status
    |-----------------------------------------------------
    | Order package Delivery Status update by this method
    |
    */

        

    /*
    |-------------------------------------------------------------
    | Status add/update
    | Order Product table Delivery status add/update
    |-------------------------------------------------------------
    |
    | single product update/add delivery status in the order products table
    */
        $(document).on('click','.add_status',function(e){
            e.preventDefault();
            statusAndSyncMessageProcessingHide();
            var id = $(this).data('id'); //order_products table : id
            //var status      = $('.order_product_status_id_'+id).val();
            var status      = $('#order_product_status_id_'+id+' option:selected').val();
            
            var title       = $('.title_id_'+id).val();
            var text        = $('.text_id_'+id).val();
            var order_id    = $('.order_id').val();//order id
            $('.title_error_message_'+id).text('');
            $('.details_error_message_'+id).text('');
            $('.status_error_message_'+id).text('');
            if(!status){
                $('.status_error_message_'+id).text('Status field is required');
                return 0;
            } 
            if(!title){
                $('.title_error_message_'+id).text('Title field is required');
                return 0;
            }
            if(!text){
                $('.details_error_message_'+id).text('Details field is required');
                return 0;
            }
            var email_applicable    = $(".email_applicable_for_package_order_"+id).prop("checked");
            email_applicable        = email_applicable == true ? 1 : 0; 

            var order_product_id = id;
            var order_package_id = $('.order_package_id').val(); //order_packages table : id
            var url              = $('.orderProductStatusUpdate').val();
            $.ajax({
                url: url,
                type: "GET",
                datatype:"HTML",
                data:{
                    status:status,title:title,text:text,order_id:order_id,order_product_id:id,order_package_id:order_package_id,email_applicable:email_applicable
                },
                beforeSend:function(){
                    $('.on_processing_'+id).fadeIn();
                },
                success: function(response){
                    if(response.status == true)
                    {
                        $('#deliveryStatus').modal('show');
                        $(".ajax-response-delivery-status-result").html(response.html);
                        $('.alertSuccessProductStatus_'+id).show();
                        $('.alertSuccessSingleProductStatus_'+id).show();
                        $('.successMessage_'+id).text('Delivery Status Updated Successfully');
                    }
                },
                complete:function(){
                    $('.on_processing_'+id).fadeOut();
                },
            });
        }); 

    /*
    |-------------------------------------------------------------
    | Status add/update
    | Order Product table Delivery status add/update
    |-------------------------------------------------------------
    | single product update/add delivery status in the order products table
    */
    
    

    /*
    |-----------------------------------------------------------------
    | Display Tracking Details modal 
    |----------------------------------------------------------------
    */
        $(document).on('click','.adminOrderTrackingDetails',function(){
            statusAndSyncMessageProcessingHide();
            var id = $(this).data('id');
            var url = $(this).data('href');
            $.ajax({
                url: url,
                type: "GET",
                datatype:"HTML",
                data:{
                    id:id
                },
                beforeSend:function(){
                    $('.processing_on').fadeIn();
                },
                success: function(response){
                    if(response.status == true)
                    {
                        $('#trackingDetails').modal('show');
                        $(".ajax-response-tracking-details-result").html(response.html);
                    }
                },
                complete:function(){
                    $('.processing_on').fadeOut();
                },
            });
        });
    /*
    |-----------------------------------------------------------------
    | Display Tracking Details modal 
    |----------------------------------------------------------------
    */


    //close alert all message
    $(document).on('click','.alert-close',function(){
        statusAndSyncMessageProcessingHide();
    });
    function statusAndSyncMessageProcessingHide()
    {
        $('.alertSuccessMainStatus').hide();
        $('.alert-success').hide();
        $('.text-left-ul').text('');
        $('.alertSuccessProductStatusSingle').hide();

        $('.alertSuccess_Individually_processing').hide();
        $('.alertSuccess_Individually').hide();
        $('.alertSuccessMessage_Individually').text('');
    }
    //close alert all message

    $(document).on('click',".closeDeliveryModel",function(){
        defaultLoading();
    });



































    //==============================================================================
        //bulk order status sync (route for all checked order status sync)
        //order status sync confirmation
           /*  
                $(document).on('click','.allOrderPlaceToAliexpress',function(){
                    $('#confirmation_place_order_modal').modal('show');
                });
                $(document).on('click', '.confirm-button', function (){
                    var ids = [];
                    $('input.check_single_class[type=checkbox]').each(function () {
                        if(this.checked){
                            var v = $(this).val();
                            ids.push(v);
                        }
                    });
                    var url = $('.bulkOrderPlaceToAliexpress').val();

                    if(ids.length <= 0) return ;
                    $('#confirmation_place_order_modal').modal('hide');
                    $.ajax({
                        url: url,
                        data: {ids: ids},
                        type: "POST",
                        beforeSend:function(){
                            $('.loading').fadeIn();
                        },
                        success: function(response){
                            uncheckedAllData();
                            $('.text-left-ul').html(" ");
                            $('.text-left-li').html(" ");

                            $('.alertSuccessSingle').hide();
                            $('.alertDangerSingle').hide();
                        
                            if(response.status == true)
                            {
                                orderListByAllSelectedFields();
                                $.each(response.datas, function(i, mess) {
                                    if(mess.message_type == 'success')
                                    {
                                        $('.alertSuccessBulk').show();
                                        $('.alert-success').show();
                                        $('.text-left-ul').append("<li>"+mess.message+"</li>");
                                    }else{
                                        $('.alertDangerBulk').show();
                                        $('.alert-danger').show();
                                        $('.text-left-li').append("<li>"+mess.message+"</li>");
                                    }
                                });
                            }else{
                                $('.alertDanger').show();
                                $('.alert-danger').show();
                                $('.text-left').text('Order not process to aliexpress');
                            }
                        },
                        complete:function(){
                            $('.loading').fadeOut();
                        },
                    });
                }); 
            */
        //bulk order status sync (route for all checked order status sync)
    //==============================================================================