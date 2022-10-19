
    $(document).ready(function(){
        productListLoading();
    });

    function productListLoading()
    {
        var createUrl       = $('.productListByAjaxResponseUrl').val();
        var status          = "";
        var page_no         = parseInt($('.page_no').val());
        var pagination      = $('#paginate :selected').val();
        var category_id      = $('#category_filter_id :selected').val();
        var search          = $('.search').val();
        var url             =  createUrl+"?page="+page_no;
        $.ajax({
            url: url,
            data:{
                pagination:pagination,search:search,status:status,page_no:page_no,category_id:category_id
            },
            success: function(response){
                if(response.status == true)
                {
                    $('.productListAjaxResult').html(response.data);
                }
            },
            error: function (data) { 
                alert('error happened');
            }
        });
    }


    //pagination
    $(document).on("click",".pagination li a",function(e){
        e.preventDefault();
        var page = $(this).attr('href');
        var pageNumber = page.split('?page=')[1]; 
        return getPagination(pageNumber);
    });
    function getPagination(pageNumber){
        var createUrl = $('.productListByAjaxResponseUrl').val();
        var url =  createUrl+"?page="+pageNumber;
        var page_no         = parseInt($('.page_no').val());
        
        // var status          = $('.selectedStatus').val();
        var pagination      = $('#paginate :selected').val();
        var status  = $('#status').val();
        var sell_from = $('#sell_price_from').val();
        var sell_to = $('#sell_price_to').val();
        var from_date= $('#from').val();
        var to_date = $('#to').val();
        var merchant_id = $('#merchant_id').val();
        var category_id = $('#category_id').val();
        var subcategory_id = $('#subcategory_id').val();
        // var category_id      = $('#category_filter_id :selected').val();
        var search          = $('.search').val();
        $.ajax({
            url: url,
            type: "GET",
            datatype:"HTML",
            data:{
                pagination:pagination,search:search,status:status,page_no:page_no,sell_from:sell_from,sell_to:sell_to,from_date:from_date,to_date:to_date,merchant_id:merchant_id,category_id:category_id,subcategory_id:subcategory_id
            },
            success: function(response){
                if(response.status == true)
                {
                    $('.productListAjaxResult').html(response.data);
                }
            },
        });
    }
//pagination


    var ctrlDown = false,
        ctrlKey = 17,
        cmdKey = 91,
        vKey = 86,
        cKey = 67;
        xKey = 88;

//pagination,custom search , event
$(document).on('change keyup','.paginate,.search',function(e){
    // if (e.keyCode == ctrlKey || e.keyCode == cmdKey) ctrlDown = true;
    // if (ctrlDown && (e.keyCode == vKey || e.keyCode == cKey || e.keyCode == xKey)) return false;
    
    var defaultUrl =  $('.productListByAjaxResponseUrl').val();
    //console.log(e.type);
    //if((e.type) == "keyup")
    var pagination      = $('#paginate :selected').val();
    // var category_id      = $('#category_filter_id :selected').val();
    var status  = $('#status').val();
    var sell_from = $('#sell_price_from').val();
    var sell_to = $('#sell_price_to').val();
    var from_date= $('#from').val();
    var to_date = $('#to').val();
    var merchant_id = $('#merchant_id').val();
    var category_id = $('#category_id').val();
    var subcategory_id = $('#subcategory_id').val();
    var search          = $('.search').val();

    // var selectedStatus  = $('.selectedStatus').val();
    var page_no         = $('.page_no').val();
    // var status  = "";
    //if((e.type) == "click")
    /* if($(e.target).prop("name") == "name")
    {
        status      = isChangedStatus(this);
    }else{
        status          = selectedStatus;
    } setTimeout(function() 
    {//do something special
    }, 5000);*/
    // status          = selectedStatus;
    $.ajax({
        url: defaultUrl,
        type: "GET",
        datatype:"HTML",
        data:{
            pagination:pagination,search:search,status:status,page_no:page_no,sell_from:sell_from,sell_to:sell_to,from_date:from_date,to_date:to_date,merchant_id:merchant_id,category_id:category_id,subcategory_id:subcategory_id
        },
        success: function(response){
            if(response.status == true)
            {
                $('.productListAjaxResult').html(response.data);
            }
        },
    });
});
//pagination,custom search , event



//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------

 
    //subcategory by category when change category from details product
    $(document).on('change','.categoryId',function(){
        var catid = $('.categoryId option:selected').val();
        console.log(catid);
        var url = $('.subCategoryByCategoryUrl').val();
        $.ajax({
            url: url,
            data: {catid:catid},
            success: function(response){
                if(response.status == true)
                {
                    $('.subcategoryId').html(response.html);
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
        var url = $('.editCategoryUrlForOpeningModal').val();
        $.ajax({
            url: url,
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
        var url = $('.subCategoryByCategoryUrl').val();
        $.ajax({
            url: url,
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
                productListLoading();
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



    //-----------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    //pagination,custom search , event
    $(document).on('click','#filter_btn',function(e){
        e.preventDefault();

        var defaultUrl =  $('.productListByAjaxResponseUrl').val();

        var pagination      = $('#paginate :selected').val();
        var search          = $('.search').val();

        // var selectedStatus  = $('.selectedStatus').val();
        var page_no         = $('.page_no').val();
        var status  = $('#status').val();
        var sell_from = $('#sell_price_from').val();
        var sell_to = $('#sell_price_to').val();
        var from_date= $('#from').val();
        var to_date = $('#to').val();
        var merchant_id = $('#merchant_id').val();
        var category_id = $('#category_id').val();
        var subcategory_id = $('#subcategory_id').val();
        $.ajax({
            url: defaultUrl,
            type: "GET",
            datatype:"HTML",
            data:{
                pagination:pagination,search:search,status:status,page_no:page_no,sell_from:sell_from,sell_to:sell_to,from_date:from_date,to_date:to_date,merchant_id:merchant_id,category_id:category_id,subcategory_id:subcategory_id
            },
            success: function(response){
                if(response.status == true)
                {
                    $('.productListAjaxResult').html(response.data);
                }
            },
        });
    });
    //pagination,custom search , event

     //pagination,custom search , event
     $(document).on('click','#filter_btn_clear',function(e){
        e.preventDefault();

        var defaultUrl =  $('.productListByAjaxResponseUrl').val();

        var pagination      = $('#paginate :selected').val();
        $('.search').val('');

        $('.datetime').daterangepicker({
            // timePicker: true,
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            locale: {
                format: 'YYYY-MM-DD'
            },
            autoApply:true
        });

        $('#from').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            startDate: moment().subtract(365, 'days'),
            minYear: 1901,
            locale: {
                format: 'YYYY-MM-DD'
            },
            autoApply:true
        });

        var page_no         = $('.page_no').val();
         $('#status').val('');
        $('#sell_price_from').val('');
        $('#sell_price_to').val('');
        // $('#from').val('');
        // $('#to').val('');
        $('#merchant_id').val('');
        $('#category_id').val('');
        $('#subcategory_id').val('');
        $.ajax({
            url: defaultUrl,
            type: "GET",
            datatype:"HTML",
            data:{
                pagination:pagination,page_no:page_no
            },
            success: function(response){
                if(response.status == true)
                {
                    $('.productListAjaxResult').html(response.data);
                }
            },
        });
    });
    //pagination,custom search , event


    

