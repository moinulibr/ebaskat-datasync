

    disabledImportButton();
    displayNoneAllMessage();
    $(document).on('keyup','.id',function(e){
        displayNoneAllMessage();
        if($(this).val().length < 1) 
        {
            disabledImportButton();
        }else{
            enableImportButton();
        }
    });


    $(document).on('click','.import',function(e){
        displayNoneAllMessage();
        e.preventDefault();
        processingImportProduct();
    });


    function processingImportProduct()
    {      
        var url = $('.importingSingleProductByProductIdUrl').val();
        var id  = $('.id').val();id
        if(($('.id').val()).length < 1) 
        {
            alert('Invalid ID');
            return;
        }
        disabledImportButton();
        disabledIdField();
        $.ajax({
            type: 'GET',
            url: url,
            data:{id:id},
            beforeSend:function(){
                $('.loading').fadeIn();
            },
            success: function(response){
                enableIdField();
                if(response.status == true)
                {
                    $('.alert-custom-success').show();
                } 
                else if(response.status == false)
                {
                    $('.alert-custom-error').show();
                }
                $('.message').html(response.message);
                enableImportButton();
            },
            complete:function(){
                $('.loading').fadeOut();
                enableImportButton();
            },
            error:function(err){
                $('.loading').fadeOut();
                enableImportButton();
            },
        });
        //end ajax
    }                                                                                                               
    /**product import from aliexpress*/




    

   
    function displayNoneAllMessage()
    {
        $('.alert-custom-success').hide();
        $('.alert-custom-error').hide();
        $('.message').text('');
    }

    function disabledIdField()
    {
        $('.id').attr('disabled','disabled');
    }

    function enableIdField()
    {
        $('.id').attr('disabled',false);
    } 


    function disabledImportButton()
    {
        $('.disabledAttr').attr('disabled','disabled');
    }

    function enableImportButton()
    {
        $('.disabledAttr').attr('disabled',false);
    }