    
    
    //default Bigbuy order list displaied by this route
    function defaultLoading(currentStatus = null)
    {
        var createUrl       =  $('.orderListByAjax').val();
        var page_no         = parseInt($('.page_no').val());
        var defaultUrl      =  createUrl+"?page="+page_no;
        var status          = currentStatus;
        var page_no         = $('.page_no').val();
        var pagination      = $('#paginate :selected').val();
        var search          = $('.custom_search').val();
        var start_date      = $('.start_date').val();
        var end_date        = $('.end_date').val();
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
        $('.order_status').attr('class','order_status btn btn-secondary btn-sm');
        var statusFromController = $('.currentStatusFromController').val();
        if(statusFromController == 'none')
        {
            $('#none').removeAttr('class','order_status btn btn-secondary');
            $('#none').attr('class','order_status btn btn-primary btn-sm');
        }else{
            $('#'+statusFromController).removeAttr('class','order_status btn btn-secondary');
            $('#'+statusFromController).attr('class','order_status btn btn-primary btn-sm');
        }
        defaultLoading(statusFromController);
    });
    //default Bigbuy order list displaied by this route




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
        //if((e.type) == "click")
        if($(e.target).prop("name") == "status")
        {
            status      = isChangedStatus(this);
        }else{
            status      = selectedStatus;
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
            $('.order_status').attr('class','order_status btn btn-secondary btn-sm');
            if(orderStatus == "all_orders")
            {
                orderStatus = "";
            }else{
                orderStatus = orderStatus;
            }
            $(thisID).removeAttr('class','order_status btn btn-secondary');
            $(thisID).attr('class','order_status btn btn-primary btn-sm');
        }
        $('.selectedStatus').val(orderStatus);
        return orderStatus;
    }
    /**
     * set Status Label (page label) when change status
     */
    function statusLabel(status)
    {
        var new_status = status.replace(/_/g, ' ');
        if(status == "all_orders")
        {
            $('.status_label').text('All Orders');
            status = "";
        }else{
            $('.status_label').text(new_status.replace(/\b[a-z]/g, function(txtjq) {
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
        var selectedStatus = $('.selectedStatus').val();
        defaultLoading(selectedStatus);
    });

//==============================================================================


    /*
    | Tracking Details 
    */
    $(document).on('click','.trackingDetails',function(e){
        e.preventDefault();
        //var id = $(this).data('id');
        var url = $(this).data('href');
        $.ajax({
            url: url,
            type: "GET",
            datatype:"HTML",
            /* data:{
                id:id
            }, */
            success: function(response){
                if(response.status == true)
                {
                    $(".ajax-response-tracking-details-result").html(response.data);
                    $('#trackingDetails').modal('show');
                }
            },
        });
    });
    /*
    | Tracking Details 
    */


//==============================================================================

    // checked all order list 
    $(document).on('click','.check_all_class',function()
    {
        if (this.checked == false)
        {   
            $('.allOrderPlaceToBigbuy').hide();
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
            $('.allOrderPlaceToBigbuy').show();
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
            $('.allOrderPlaceToBigbuy').hide();
            $('.allOrderSyncStatus').hide();
            $('.check_all_class').prop('checked', false).change();
        }

        var id = $(this).attr('id');
        if (this.checked == false)
        {
            $(this).prop('checked', false).change();
            $(this).val('').change();
        }else{
            $('.allOrderPlaceToBigbuy').show();
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
            $('.allOrderPlaceToBigbuy').hide();
            $('.allOrderSyncStatus').hide();
            $('.check_all_class').prop('checked', false).change();
        }
    });
    //check single order list
    

    // unchek all method
    function uncheckedAllData()
    {
        $('.check_all_class').prop('checked', false).change();
        $('.check_single_class').val('').change();
        $('.check_single_class').prop('checked', false).change();
        $('.allOrderPlaceToBigbuy').hide();
        $('.allOrderSyncStatus').hide();
    }
    // unchek all method

//==============================================================================
