
    //---------------------------------------------
        /*
        | display single single order place modal
        */
            function displayAllProductBasedOnSingleOrderPackageId(package_id,order_id)
            {
                statusAndSyncMessageProcessingHide();
                var url = $('.displayAllProductsUrlIdForSingle').val();
                $.ajax({
                    url: url,
                    type: "GET",
                    datatype:"HTML",
                    data:{
                        package_id:package_id,order_id:order_id
                    },
                    beforeSend:function(){
                        $('.individually_processing').fadeIn();
                    },
                    success: function(response){
                        if(response.status == true)
                        {
                            $(".order_products_details_data").html(response.productData);
                        }
                    },
                    complete:function(){
                        $('.individually_processing').fadeOut();
                    },
                });
            }

            $(document).on('click','.displayAllProductForSingleOrderPlace',function(e){
                e.preventDefault();
                statusAndSyncMessageProcessingHide();
                var package_id  = $(this).data('package_id');
                var order_id    = $(this).data('order_id');
                var url         = $(this).data('href');
                $.ajax({
                    url: url,
                    type: "GET",
                    datatype:"HTML",
                    data:{
                        package_id:package_id,order_id:order_id
                    },
                    beforeSend:function(){
                        $('.individually_processing').fadeIn();
                    },
                    success: function(response){
                        if(response.status == true)
                        {
                            $("#displayAllProductForSingleOrderPlaceInThisModal").html(response.modalData).modal('show');
                            $(".order_products_details_data").html(response.productData);
                        }
                    },
                    complete:function(){
                        $('.individually_processing').fadeOut();
                    },
                });
            });
        /*
        | display single single order place modal
        */


    //==============================================================================
        //single order place confirmation
            $(document).on('click','.single_order_placing_to_bigbuy',function(e){
                $('.singleSingleOrderId').val('');
                $('.singleSingleOrderId').val($(this).data('id'));
                e.preventDefault();
                $('#confirmationModalWhenSingleProductWiseOrderPlaceToBigby').modal('show');
            });
        /*
        | single order place to bigbuy
        */
        $(document).on('click','.singleSingleOrderConfirmationButton',function(e){
            e.preventDefault();
            statusAndSyncMessageProcessingHide();
            sinngleOrdrRelatedAllStatusMessageHide();
            var id = $('.singleSingleOrderId').val();
            var order_package_id    = $('.place_'+id).data('order_package_id_'+id);//order package id
            var product_id          = $('.place_'+id).data('product_id_'+id);//product id
            var order_product_id    = $('.place_'+id).data('order_product_id_'+id);//order_product_id id
            var order_id            = $('.place_'+id).data('order_id_'+id);//order_id id
            var url = $('.place_'+id).data('href_'+id);
            $.ajax({
                url: url,
                type: "GET",
                datatype:"HTML",
                data:{
                    order_id:order_id,order_package_id:order_package_id,product_id:product_id,order_product_id:order_product_id
                },
                beforeSend:function(){
                    $('.single_processing_on').fadeIn();
                },
                success: function(response){
                    $('#confirmationModalWhenSingleProductWiseOrderPlaceToBigby').modal('hide');
                    if(response.status == "success")
                    {
                        $(".order_products_details_data").html(response.html);
                        defaultLoading();
                        $('.alertSuccessSingleOrder').show();
                        $('.alert-success-single-order').show();
                    }else{
                        $('.alertDangerSingleOrder').show();
                        $('.alert-danger-single-order').show();
                    }
                    $('.text-left-single-order').text(response.message);
                },
                complete:function(){
                    $('.single_processing_on').fadeOut();
                },
                error: function (data) { 
                    defaultLoading();
                    $('#confirmationModalWhenSingleProductWiseOrderPlaceToBigby').modal('hide');
                },
            });
        });
    /*
    | single order place to bigbuy
    */
   function sinngleOrdrRelatedAllStatusMessageHide()
   {
        $('.alertSuccessSingleOrder').hide();
        $('.alert-success-single-order').hide();
        $('.alertDangerSingleOrder').hide();
        $('.alert-danger-single-order').hide();
        $('.text-left-single-order').text('');
   }
   $(document).on('click','.alert-close',function(){
       sinngleOrdrRelatedAllStatusMessageHide();
       defaultLoading();
   });
//==============================================================================



//==============================================================================

    /*
    | bigbuy order number : open modal
    */
        $(document).on('click','.bigbuyOrderNumberUpdateForSingleOrder',function(){
            $('.bigbuyOrderIdErrorMessageForSingleOrder').text('');
            sinngleOrdrRelatedAllStatusMessageHide();
            $('.displayOrderProductNameForSingleOrder').text('');
            $('.orderProductTableIdForSingleOrder').val('');
            $('.bigbuyOrderNoForSingleOrder').val('');
            var id  = $(this).data('id');
            var order_product_name = $(this).data('order_product_name');

            var ds_order_no = $(this).data('ds_order_no');

            $('#bigbuyOrderNumberUpdateForSingleOrder_modal').modal('show');
            $('.displayOrderProductNameForSingleOrder').text(order_product_name);
            $('.orderProductTableIdForSingleOrder').val(id);
            $('.bigbuyOrderNoForSingleOrder').val(ds_order_no);
        });
    /*
    | bigbuy order number : open modal
    */
    /*
    | bigbuy order number : udpate
    */
        $(document).on('keyup','.bigbuyOrderNoForSingleOrder',function(){
            $('.bigbuyOrderIdErrorMessageForSingleOrder').text('');
        });
        $(document).on('click','.updateBigbuyOrderNumberButtonForSingleOrder',function(){
            var orderProductId  = $('.orderProductTableIdForSingleOrder').val();
            var bigbuyOrderNo   = $('.bigbuyOrderNoForSingleOrder').val();
            
            if(!bigbuyOrderNo)
            {
                $('.bigbuyOrderIdErrorMessageForSingleOrder').text('Bigbuy Order No/Number field is empty');
                return ;
            }
            var package_id  = $('.orderPackageIdForSingle').val();
            var order_id    = $('.orderIdForSingle').val();

            var url = $('.bigbuyOrderNoUpdateUrlForSingleOrder').val();
           
            $.ajax({
                url: url,
                type: "GET",
                datatype:"HTML",
                data:{
                    orderProductId:orderProductId,bigbuyOrderNo:bigbuyOrderNo
                },
                beforeSend:function(){
                    $('.loading').show()
                },
                success: function(response){
                    displayAllProductBasedOnSingleOrderPackageId(package_id,order_id);
                    if(response.status == true)
                    {
                        $('.bigbuyOrderIdErrorMessageForSingleOrder').text('');
                        $('.alertSuccessSingleOrder').show();
                        $('.alert-success-single-order').show();
                        $('.text-left-single-order').text(response.message);
                        $('#bigbuyOrderNumberUpdateForSingleOrder_modal').modal('hide');
                        defaultLoading();
                    }
                },
                complete:function(){
                    $('.loading').hide()
                },
            });
        });
    /*
    | bigbuy order number : udpate
    */

//==============================================================================
