        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

    /**show  Brand Modal */
    $(document).on('click','.addNewBrandModal',function(){
        $('.message').text('');
        $('.img-upload').val('');
        $('#addNewBrandModal').modal('show');
    });
    /**show  Brand Modal */


    // make slug
    function convertToSlug( str )
    {
        //replace all special characters | symbols with a space
        str = str.replace(/[`~!@#$%^&*()_\-+=\[\]{};:'"\\|\/,.<>?\s]/g, ' ').toLowerCase();

        // trim spaces at start and end of string
        str = str.replace(/^\s+|\s+$/gm,'');

        // replace space with dash/hyphen
        str = str.replace(/\s+/g, '-');
        document.getElementById("slug-text").innerHTML= str;
        //return str;
    }



    /**create Brand */
    $(document).on("submit",'.createBrand',function(e){
        e.preventDefault();
        $('.color-red').text('');
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            enctype: 'multipart/form-data',
            data: new FormData(this),
            processData: false,
            contentType: false,
            beforeSend:function(){
                $('.loading').fadeIn();
            },
            success: function(response){
                console.log(response);
                if(response.status == 'errors')
                {
                    printErrorMsg(response.error);
                }
                else if(response.status == true)
                {
                    $('.mess').text('Data Inserted Successfully');
                    $('.mess').css({
                        'color':'green',
                        'background-color':'#f8f9fa',
                        'padding' :'2%',
                        'font-size' : '15px',
                        'font-weight':'700'
                    });
                    $('.message').text(response.message);
                    document.getElementById("formResetId").reset();
                    setTimeout(function()
                    {
                        $('#addNewBrandModal').modal("hide");
                        getIndexData($('.getIndexData').val());
                    }, 200);
                }
                else if(response.status == false)
                {

                }
            },
            complete:function(){
                $('.loading').fadeOut();
            },
        });
        //end ajax

        function printErrorMsg(msg) {
            $('.color-red').css({'color':'red'});
            $.each(msg, function(key, value ) {
                $('.'+key+'_err').text(value);
            });
        }
    });
    /**create Brand */


    /** index / view Brand list */
    $(document).ready(function(){
        var url = $('.getIndexData').val();
        getIndexData(url);
    });

    /** index / view Brand list by pagination*/
    $(document).on("click",".pagination li a",function(e){
        e.preventDefault();
        var page = $(this).attr('href');
        var pageNumber = page.split('?page=')[1];
        return getPagination(pageNumber);
	});
        function getPagination(pageNumber){
            var createUrl = $('.getIndexData').val();
            var url =  createUrl+"?page="+pageNumber;
            getIndexData(url);
        }
    /** index / view Brand list by pagination*/

    /** index / view Brand list by search or show per page*/
    $(document).on('change keyup','.search ,.pagination',function(){
        var url = $('.getIndexData').val();
        getIndexData(url)
    });
    /** index / view Brand list by search or show per page*/

    function getIndexData(url)
    {
        var search      = $('.search').val();
        var pagination = $('.pagination option:selected').val();
        $.ajax({
            url: url,
            type: "GET",
            data:{pagination:pagination,search:search},
            datatype:"HTML",
            success: function(response){
                $('.showResult').html(response.html);
            },
        });
    }
/** index / view Brand list by search or show per page*/



/** Show single Data*/
    $(document).on('click','.showSingle',function(e){
        e.preventDefault();
        var id      = $(this).data('id');
        var url     = $('.showModalUrl').val();
        $.ajax({
            url: url,
            type: "GET",
            data:{id:id},
            datatype:"HTML",
            success: function(response){
                $('#editBrandModal').html(response.html).modal('show');
            },
        });
    });

/** Show single Data*/


/** Delete Single Brand*/
    $(document).on('click','.singleDelete',function(e){
        e.preventDefault();
        $('.delete_id').val();
        $('#confirm-delete').modal('show');
        var id      = $(this).data('id');
        $('.delete_id').val(id);
    });
    $(document).on('click','.deleteConfirm',function(e){
        e.preventDefault();
        var id      = $('.delete_id').val();
        var create  = $('.deleteModalUrl').val();
        var url     = create+ '?id='+id;
        $.ajax({
            url: url,
            type: "GET",
            data:{id:id},
            datatype:"HTML",
            success: function(response){
                $('.delete_id').val();
                $('#confirm-delete').modal('hide');
                if(response.status == true)
                {
                    $('.mess').text('Data Deleted Successfully');
                    $('.mess').css({
                        'color':'green',
                        'background-color':'#f8f9fa',
                        'padding' :'2%',
                        'font-size' : '15px',
                        'font-weight':'700'
                    });
                    getIndexData($('.getIndexData').val());
                }
            },
        });
    });
/** Delete Single Brand*/
