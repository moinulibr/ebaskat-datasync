
        /*
        |-----------------------------------------
        | show delivery status
        |-----------------------------------------
        */  
            $(document).on('click','.delivery_status',function(){
                var id = $(this).data('id');
                var url = $(this).data('href');
                $.ajax({
                    url: url,
                    type: "GET",
                    datatype:"HTML",
                    data:{
                        id:id
                    },
                    success: function(response){
                        if(response.status == true)
                        {
                            $('#deliveryStatus').modal('show');
                            $(".ajax-response-delivery-status-result").html(response.html);
                        }
                    },
                });
            });
        /*
        |-----------------------------------------
        | show delivery status
        |-----------------------------------------
        */  


        /*
        |-----------------------------------------
        | main order status update
        |-----------------------------------------
        */
            $(document).on('click','#order_status_update',function(){
                statusAndSyncMessageProcessingHide();
                var main_order_status   = $('#main_order_status option:selected').val();
                var order_id            = $('.order_id').val(); //order id
                var url                 = $('.mainOrderStatusUpdate').val();
                var email_applicable    = $(".email_applicable_for_main_order").prop("checked");
                email_applicable        = email_applicable == true ? 1 : 0; 
                $.ajax({
                    url: url,
                    type: "GET",
                    datatype:"HTML",
                    data:{
                        main_order_status:main_order_status,order_id:order_id,email_applicable:email_applicable
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
                            $('.text-left-ul').text('Order Status Update Successfully');
                            defaultLoading();
                        }
                    },
                    complete:function(){
                        $('.on_processing_main_status').fadeOut();
                    },
                });
            });
        /*
        |-----------------------------------------
        | main order status update
        |-----------------------------------------
        */
        


    /*
    |------------------------------------------------------------------------
    | Order Package status  
    | Update order package delivery status
    |-----------------------------------------------------
    | Order package Delivery Status update by this method
    |
    */
        $(document).on('click','.updateOrderPackageDeliveryStatus',function(){
            statusAndSyncMessageProcessingHide();
            var id          = $(this).data('id'); //order_products table : id
            var status      = $('#order_package_delivery_status_'+id+' option:selected').val();
            var order_id    = $('.order_id').val();//order id
            var order_package_id = id; //order_packages table : id
            var url = $('.orderPackageDeliveryStatusUpdateFromMianOrder').val();
            var selectedOrderPackage = $('.selectedOrderPackageId').val();
            var email_applicable    = $(".email_applicable_for_package_order_"+id).prop("checked");
            email_applicable        = email_applicable == true ? 1 : 0; 
            $.ajax({
                url: url,
                type: "GET",
                datatype:"HTML",
                data:{
                    status:status,order_id:order_id,order_package_id:order_package_id,email_applicable:email_applicable
                },
               beforeSend:function(){
                    $('.on_processing_order_package_deliver_status_'+id).fadeIn();
                },
                success: function(response){
                    if(response.status == true)
                    {
                        $('#deliveryStatus').modal('show');
                        $(".ajax-response-delivery-status-result").html(response.html);
                        changePackageStatusFunction(id);//order_packages table : id
                        $('.alertSuccessOrderPackageDeliveryStatus_'+id).show();
                        $('.orderPackageDeliveryStatus_'+id).show();
                        $('.orderPackageDeliveryStatusText_'+id).text('Delivery Status Updated Successfully');
                        defaultLoading();
                    }
                },
                complete:function(){
                    $('.on_processing_order_package_deliver_status__'+id).fadeOut();
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
        $(document).on('click','.add_order_product_status',function(){
            statusAndSyncMessageProcessingHide();
            var id          = $(this).data('id'); //order_products table : id
            var order_package_id = $(this).data('package-id');//order_packages table : id
            var status      = $('#order_product_delivery_status_'+id+' option:selected').val();
            var title       = $('.title_id_'+id).val();
            var text        = $('.text_id_'+id).val();
            var order_id    = $('.order_id').val();//order id
            $('.title_error_message_'+id).text('');
            $('.details_error_message_'+id).text('');
            $('.status_error_message_'+id).text('');
            var order_product_id = id; 
            var email_applicable    = $(".email_applicable_for_package_order_product_"+id).prop("checked");
            email_applicable        = email_applicable == true ? 1 : 0; 
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
        
            var url = $('.orderProductDeliveryStatusUpdateFromMianOrder').val();
            $.ajax({
                url: url,
                type: "GET",
                datatype:"HTML",
                data:{
                    status:status,title:title,text:text,order_id:order_id,order_product_id:order_product_id,order_package_id:order_package_id,email_applicable:email_applicable
                },
            beforeSend:function(){
                    $('.on_processing_order_product_delivery_status_'+id).fadeIn();
                },
                success: function(response){
                    if(response.status == true)
                    {
                        $('#deliveryStatus').modal('show');
                        $(".ajax-response-delivery-status-result").html(response.html);
                        changePackageStatusFunction(order_package_id);//order_packages table : id
                        $('.alertSuccessOrderProductDeliveryStatus_'+id).show();
                        $('.alertSuccessSingleOrderProductDeliveryStatus_'+id).show();
                        $('.successMessageSingleOrderProductDeliveryStatus_'+id).text('Delivery Status Updated Successfully');
                        defaultLoading();
                    }
                },
                complete:function(){
                    $('.on_processing_order_product_delivery_status_'+id).fadeOut();
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
    | change package status
    |----------------------------------------------------------------
    */
        function changePackageStatusFunction(id)
        {
            $('.allFullPackage').hide();
            $('.allInactivePackagesWhenChangesStatus').css({
                'color':'#143250',
                'background-color':'#fff',
                'padding':'0px'
            });
            $('.currentChangingStatusText').text('Change Status');
            $('.selectedOrderPackageId').val('');
            //var id = $(this).data('id');
            $('.activeClass_'+id).css({
                'color':'#FFF',
                'background-color':'green',
                'padding':'1%',
                'font-weight':'bold'
            });

            $('.currentChangingStatusText_'+id).text('Changing Status...');

            $('.selectedOrderPackageId').val(id);
            $('.fullPackage_'+id).show();
        }
        $(document).on('click','.changePackageStatus',function(){
            var id = $(this).data('id');
            changePackageStatusFunction(id);
        });
    /*
    |-----------------------------------------------------------------
    | change package status
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
            //defaultLoading();//already used
        });





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
                success: function(response){
                    if(response.status == true)
                    {
                        $('#trackingDetails').modal('show');
                        $(".ajax-response-tracking-details-result").html(response.html);
                    }
                },
            });
        });
    /*
    |-----------------------------------------------------------------
    | Display Tracking Details modal 
    |----------------------------------------------------------------
    */
