
    
    //default aliexpress order list displaied by this route
    $(document).ready(function(){
        var defaultUrl =  $('.orderListByAjax').val();

        var pagination      = $('.pagination').val();
        var search          = $('.custom_search').val();
        var start_date      = $('.start_date').val();
        var end_date        = $('.end_date').val();
        $.ajax({
            url: defaultUrl,
            type: "GET",
            datatype:"HTML",
            data:{
                pagination:pagination,search:search,start_date:start_date,end_date:end_date
            },
            success: function(response){
                if(response.status == true)
                {
                    $(".ajax-response-result").html(response.data);
                }
            },
        });
    });
    //default aliexpress order list displaied by this route


    // single order place to aliexpress
    $(document).on('click','.adminOrderToAliexpress',function(e){
        e.preventDefault();
        
        var url = $(this).data("href");
        $.ajax({
            type: 'GET',
            url: url,
            beforeSend:function(){
                $('.loading').fadeIn();
            },
            success: function(response){
                if(response.status == "success")
                {
                    $('.alert-success').show();
                }else{
                    $('.alert-danger').show();
                }
                $('.text-left').text(response.message);
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
            $('.check_all_class').prop('checked', false).change();
        }

        var id = $(this).attr('id');
        if (this.checked == false)
        {
            $(this).prop('checked', false).change();
            $(this).val('').change();
        }else{
            $('.allOrderPlaceToAliexpress').show();

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
            $('.check_all_class').prop('checked', false).change();
        }
    });
    //check single order list
    


    //bulk order place to aliexpress (route for all checked order place to aliexpress)
    $(document).on('click', '.allOrderPlaceToAliexpress', function (){
        var ids = [];
        $('input.check_single_class[type=checkbox]').each(function () {
            if(this.checked){
                var v = $(this).val();
                ids.push(v);
            }
        });
        var url = $('.bulkOrderPlaceToAliexpress').val();

        if(ids.length <= 0) return ;
       
        $.ajax({
            url: url,
            data: {ids: ids},
            type: "POST",
            beforeSend:function(){
                $('.loading').fadeIn();
            },
            success: function(response){
                uncheckedAllData();
                if(response.status == "success")
                {
                    $('.alert-success').show();
                }else{
                    $('.alert-danger').show();
                }
                $('.text-left').text(response.message);
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

            var pagination      = $('.pagination').val();
            var search          = $('.custom_search').val();
            var start_date      = $('.start_date').val();
            var end_date        = $('.end_date').val();
            $.ajax({
                url: url,
                type: "GET",
                datatype:"HTML",
                data:{
                    pagination:pagination,search:search,start_date:start_date,end_date:end_date
                },
                success: function(response){
                    $(".ajax-response-result").html(response.data);
                },
            });
        }
    //pagination

    //pagination,custom search , start date, end date  event
    $(document).on('change keyup','.pagination,.custom_search,.start_date,.end_date',function(e){
        var defaultUrl =  $('.orderListByAjax').val();

        //if((e.type) == "keyup")
        var pagination      = $('.pagination').val();
        var search          = $('.custom_search').val();
        var start_date      = $('.start_date').val();
        var end_date        = $('.end_date').val();

        $.ajax({
            url: defaultUrl,
            type: "GET",
            datatype:"HTML",
            data:{
                pagination:pagination,search:search,start_date:start_date,end_date:end_date
            },
            success: function(response){
                $(".ajax-response-result").html(response.data);
            },
        });
        
    });
    //pagination,custom search , start date, end date  event