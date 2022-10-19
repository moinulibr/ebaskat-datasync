

    disabledUpdateButton();
    displayNoneAllMessage();
    $(document).on('keyup','.sku',function(e){
        displayNoneAllMessage();
        if($(this).val().length < 1) 
        {
            disabledUpdateButton();
        }else{
            enableUpdateButton();
        }
    });


    $(document).on('click','.update',function(e){
        displayNoneAllMessage();
        e.preventDefault();
        processingUpdateProduct();
    });


    function processingUpdateProduct()
    {      
        var url = $('.updatingSingleProductByProductSkuUrl').val();
        var sku  = $('.sku').val();
        if(($('.sku').val()).length < 5) 
        {
            alert('Invalid SKU');
            return;
        }
        disabledUpdateButton();
        disabledSkuField();
        $.ajax({
            type: 'GET',
            url: url,
            data:{sku:sku},
            beforeSend:function(){
                $('.loading').fadeIn();
            },
            success: function(response){
                enableSkuField();
                if(response.status == true)
                {
                    $('.alert-custom-success').show();
                } 
                else if(response.status == false)
                {
                    $('.alert-custom-error').show();
                }
                $('.message').html(response.message);
                enableUpdateButton();
            },
            complete:function(){
                $('.loading').fadeOut();
                enableUpdateButton();
            },
            error:function(err){
                $('.loading').fadeOut();
                enableUpdateButton();
            },
        });
        //end ajax
    }                                                                                                               
    /**product import from bigbuy*/




    

   
    function displayNoneAllMessage()
    {
        $('.alert-custom-success').hide();
        $('.alert-custom-error').hide();
        $('.message').text('');
    }

    function disabledSkuField()
    {
        $('.sku').attr('disabled','disabled');
    }

    function enableSkuField()
    {
        $('.sku').attr('disabled',false);
    } 


    function disabledUpdateButton()
    {
        $('.disabledAttr').attr('disabled','disabled');
    }

    function enableUpdateButton()
    {
        $('.disabledAttr').attr('disabled',false);
    }