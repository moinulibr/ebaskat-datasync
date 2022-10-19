
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
        
        var status          = $('.selectedStatus').val();
        var pagination      = $('#paginate :selected').val();
        var category_id      = $('#category_filter_id :selected').val();
        var search          = $('.search').val();
        $.ajax({
            url: url,
            type: "GET",
            datatype:"HTML",
            data:{
                pagination:pagination,search:search,status:status,page_no:page_no,category_id:category_id
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


//pagination,custom search , event
var ctrlDown = false,ctrlKey = 17,cmdKey = 91,vKey = 86,cKey = 67;xKey = 88;
$(document).on('change keyup','.paginate,.category_filter_id,.search',function(e){
    var action = 0;
        if($(e.target).prop("name") == "paginate" && ((e.type)=='change'))
        {
            action = 1;
        }
        else if($(e.target).prop("name") == "category" && ((e.type)=='change'))
        {
            action = 1;
        } 
        else if($(e.target).prop("name") == "search" && ((e.type)=='keyup'))
        {
            action = 1;
        }
        else{
            action = 0;
        }
        if(action == 0) return;

    if (e.keyCode == ctrlKey || e.keyCode == cmdKey) ctrlDown = true;
    if (ctrlDown && (e.keyCode == vKey || e.keyCode == cKey || e.keyCode == xKey)) return false;

    var defaultUrl      = $('.productListByAjaxResponseUrl').val();
    var pagination      = $('#paginate :selected').val();
    var category_id     = $('#category_filter_id :selected').val();
    var search          = $('.search').val();
    var selectedStatus  = $('.selectedStatus').val();
    var page_no         = $('.page_no').val();
    var status          = selectedStatus;
    $.ajax({
        url: defaultUrl,
        type: "GET",
        datatype:"HTML",
        data:{
            pagination:pagination,search:search,status:status,page_no:page_no,category_id:category_id
        },
        beforeSend:function(){
            $('.on_processing').fadeIn();
        },
        success: function(response){
            if(response.status == true)
            {
                $('.productListAjaxResult').html(response.data);
            }
        },
        complete:function(){
            $('.on_processing').fadeOut();
        },
    });
});
//pagination,custom search , event



//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------

 
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



    
    /* $(document).on('click change keyup keypress keydown','.process',function(e){
        e.preventDefault();
        setTimeout(function(){
            //productListLoading();
        },1000);
    }); */




    //Product Unpublished
    //-----------------------------------------------------------------------------------
    $('#unpublished_modal').on('show.bs.modal', function (e) {
        $(this).find('.btn-submit').attr('href', $(e.relatedTarget).data('href'));
    });

    $('#unpublished_modal').on('click', '.btn-submit', function(e) {
        e.preventDefault();
        $.notify("Unpublishing Product", "info");
        $.ajax({
            type: "GET",
            url: $(this).attr('href'),
            success: function(data) {
                productListLoading();
                $('#unpublished_modal').modal('hide');
                //table.ajax.reload();
                if (typeof data === 'string' || data instanceof String)
                {
                    $.notify("Product Unpublished successfully!", "success");    
                }
                else
                {
                    if(data.status == 'error')
                    {
                        $.notify(data.mgs, "error");
                    }
                }
            },
            error: function (e) {
                $.notify('Error Occur', "error");
                $('#unpublished_modal').modal('hide');
            }
        });
    });
    //-----------------------------------------------------------------------------------
    //Product Unpublished


    // product Published 
    //-----------------------------------------------------------------------------------
    $('#publishing_modal').on('show.bs.modal', function (e) {
        $(this).find('.btn-submit').attr('href', $(e.relatedTarget).data('href'));
    });

    $('#publishing_modal').on('click', '.btn-submit', function(e) {
        e.preventDefault();
        $.notify("Product Publisheding....", "info");
        $.ajax({
            type: "GET",
            url: $(this).attr('href'),
            success: function(data) {
                productListLoading();
                $('#publishing_modal').modal('hide');
                //table.ajax.reload();
                if (typeof data === 'string' || data instanceof String)
                {
                    $.notify("Product Published Successfully", "success");    
                }
                else
                {
                    if(data.status == 'error')
                    {
                        $.notify(data.mgs, "error");
                    }
                }
            },
            error: function (e) {
                $.notify('Error Occur', "error");
                $('#publishing_modal').modal('hide');
            }
        });
    });
    //-----------------------------------------------------------------------------------
    // product Published 
    
    
    // product delete 
    //-----------------------------------------------------------------------------------
        $('#delete_modal').on('show.bs.modal', function (e) {
            $(this).find('.btn-submit').attr('href', $(e.relatedTarget).data('href'));
        });

        $('#delete_modal').on('click', '.btn-submit', function(e) {
            e.preventDefault();
            $.notify("Deleting  Product...", "info");
            $.ajax({
                type: "GET",
                url: $(this).attr('href'),
                success: function(data) {
                    productListLoading();
                    $('#delete_modal').modal('hide');
                    //table.ajax.reload();

                    if (typeof data === 'string' || data instanceof String)
                    {
                        $.notify(data, "success");    
                    }
                    else
                    {
                        if(data.status == 'error')
                        {
                            $.notify(data.mgs, "error");
                        }
                    }
                },
                error: function (e) {
                    $.notify('Error Occur', "error");
                    $('#delete_modal').modal('hide');
                }
            });
        });
    //-----------------------------------------------------------------------------------
    // product delete 


    // product restore 
    //-----------------------------------------------------------------------------------
        $('#restore_modal').on('show.bs.modal', function (e) {
            $(this).find('.btn-submit').attr('href', $(e.relatedTarget).data('href'));
        });

        $('#restore_modal').on('click', '.btn-submit', function(e) {
            e.preventDefault();
            $.notify("Restoring  Product...", "info");
            $.ajax({
                type: "GET",
                url: $(this).attr('href'),
                success: function(data) {
                    productListLoading();
                    $('#restore_modal').modal('hide');
                    //table.ajax.reload();

                    if (typeof data === 'string' || data instanceof String)
                    {
                        $.notify(data, "success");    
                    }
                    else
                    {
                        if(data.status == 'error')
                        {
                            $.notify(data.mgs, "error");
                        }
                    }
                },
                error: function (e) {
                    $.notify('Error Occur', "error");
                    $('#restore_modal').modal('hide');
                }
            });
        });
    //-----------------------------------------------------------------------------------
    // product restore 


    

