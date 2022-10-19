    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
  
    $(document).on('click','.edit',function(e){
        e.preventDefault();
        var id      = $(this).data('id');
        var url     = $('.editModalUrl').val();
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

    // make slug
    function convertToSlugForEdit( str )
    {
        //replace all special characters | symbols with a space
        str = str.replace(/[`~!@#$%^&*()_\-+=\[\]{};:'"\\|\/,.<>?\s]/g, ' ').toLowerCase();

        // trim spaces at start and end of string
        str = str.replace(/^\s+|\s+$/gm,'');

        // replace space with dash/hyphen
        str = str.replace(/\s+/g, '-');
        document.getElementById("slug-text-edit").innerHTML= str;
        //return str;
    }

    /**Edit Brand */
    $(document).on("submit",'.editBrand',function(e){
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
                if(response.status == 'errors')
                {   
                    printErrorMsg(response.error);
                }
                else if(response.status == true)
                {   
                    $('.mess').text('Data Updated Successfully');
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
                        $('#editBrandModal').html('').modal("hide");
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