
    $(document).ready(function(){
        orderListLoading();
    });

    function orderListLoading()
    {
        var createUrl       = $('.orderListByAjaxResponseUrl').val();
        var status          = "";
        var page_no         = parseInt($('.page_no').val());
        var pagination      = $('#paginate :selected').val();
        var search          = $('.search').val();
        var url             =  createUrl+"?page="+page_no;
        $.ajax({
            url: url,
            data:{
                pagination:pagination,search:search,status:status,page_no:page_no
            },
            success: function(response){
                if(response.status == true)
                {
                    $('.orderListAjaxResult').html(response.data);
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
        var createUrl = $('.orderListByAjaxResponseUrl').val();
        var url =  createUrl+"?page="+pageNumber;
        var page_no         = parseInt($('.page_no').val());
        
        // var status          = $('.selectedStatus').val();
        var pagination      = $('#paginate :selected').val();
        var status  = $('#status').val();
        var from_date= $('#from').val();
        var to_date = $('#to').val();
        var merchant_id = $('#merchant_list').val();
        var customer_id = $('#user_id').val();
        var search          = $('.search').val();
        $.ajax({
            url: url,
            type: "GET",
            datatype:"HTML",
            data:{
                pagination:pagination,search:search,status:status,page_no:page_no,from_date:from_date,to_date:to_date,merchant_id:merchant_id,customer_id:customer_id
            },
            success: function(response){
                if(response.status == true)
                {
                    $('.orderListAjaxResult').html(response.data);
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

    var defaultUrl =  $('.orderListByAjaxResponseUrl').val();

    //if((e.type) == "keyup")
    var pagination      = $('#paginate :selected').val();
    var search          = $('.search').val();

    var selectedStatus  = $('.selectedStatus').val();
    var page_no         = $('.page_no').val();
    var status  = "";
    //if((e.type) == "click")
    /* if($(e.target).prop("name") == "name")
    {
        status      = isChangedStatus(this);
    }else{
        status          = selectedStatus;
    } */
    status          = selectedStatus;
    $.ajax({
        url: defaultUrl,
        type: "GET",
        datatype:"HTML",
        data:{
            pagination:pagination,search:search,status:status,page_no:page_no
        },
        success: function(response){
            if(response.status == true)
            {
                $('.orderListAjaxResult').html(response.data);
            }
        },
    });
});
//pagination,custom search , event



    

