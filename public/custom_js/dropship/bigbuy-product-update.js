
var clear_interval;
/*
|---------------------------------------------------
|   product import from bigbuy
|---------------------------------------------------
*/


    $('.message').html('');
    $('.loading').fadeOut();

    $(document).on('click','.import',function(e){
        e.preventDefault();
        $('.message').html('');
        processingUpdateProduct();
    });

    function processingUpdateProduct()
    {      
        var url = $('.bigbuyBigbuyImportedSingleProduct').val();
        var sku  = $('.sku').val();
        $.ajax({
            type: 'GET',
            url: url,
            data:{sku:sku},
            beforeSend:function(){
                //$('.loading').fadeIn();
            },
            success: function(response){
                //$('.message').html(response);
            },
            complete:function(){
                //$('.loading').fadeOut();
            },
        });
        //end ajax
    }                                                                                                               
    /**product import from bigbuy*/



    $(document).on('click','.import',function(e){
        e.preventDefault();
        $('.currentSkuNo').hide();
        $('.current_sku').text("");
        $('.alert-success').hide();
        $('.message').html('');
        $('#process').show();  
        $('.parcentage').text(0+'%');
        //setTimeout(function () { 
            clear_interval = setInterval(function(){
                bigbuyProductImportProgressBar();
            },5000);
            displayNoneContent();
            displayVisibleContent();
            disabledAttribute();       
        //},5000)
    });
    
    function bigbuyProductImportProgressBar()
    {
        var url = $('.getUpdatedValueAfterUpdatingBigbuyProductForProgressBar').val();
        $.ajax({
            type: 'GET',
            url: url,
            beforeSend:function(){
                //$('.loading').fadeIn();
            },
            success: function(response){
                $('.currentSkuNo').show();
                $('.current_sku').text(response.currentSku);
                if(response.totalRow > 0)
                {
                    //console.log("reamining.. "+ response.remainingInsertRow);
                    //console.log("inserting... Total row : "+response.totalRow + ", Inserted row : " + response.insertedRow);
                    var width = Math.round((response.insertedRow/response.totalRow)*100);
                    if(isNaN(width) && (response.insertedRow) == 0) {
                        var width = 0;
                    }
                    if(width >= 100)
                    {
                        width = 100;
                    }
                    //console.log("second : "+width);
                    $('.parcentage').text(width+'%');
                    $('#process_data').text(response.insertedRow);
                    $('.progress-bar').css('width', width + '%');   
                }

                if((response.totalRow > 0 ) && (response.totalRow <= response.insertedRow ))
                {
                    if(response.remainingInsertRow == 0)
                    {
                        clearInterval(clear_interval);
                        $('.currentSkuNo').hide();
                        $('.current_sku').text("");
                        $('.alert-success').show();
                        $('#process').hide(); 
                        $('.parcentage').text(0+'%');
                        $('.text-left').html(response.message);
                        displayNoneContent();
                        enableAttribute();
                    }
                    afterCompletedProgressBar();
                    
                    //enableAttribute();
                    //console.log(' : yes end');
                }
                //console.log("---------------------------------------------------");
            },
            complete:function(){
                //$('.loading').fadeOut();
            },
        });
        //end ajax
    }
    /**processing file upload */

    
    function afterCompletedProgressBar()
    {
        var url = $('.updatedValueAfterUpdateCompletedProgressBar').val();
        $.ajax({
            type: 'GET',
            url: url,
            //data:{},
            beforeSend:function(){
            },
            success: function(response){
                /* $('#process').hide();    
                $('.progress-bar').css({
                    'background-color' : '#007bff'
                }); */
               /*  $('.alert-success').show();
                $('.text-left').html(response.message);
                displayNoneContent();
                enableAttribute(); */
                //$('.parcentage').text(0+'%');
                //console.log("*****************************************************");
            },
            complete:function(){
                //$('.loading').fadeOut();
            },
        });
        //end ajax
    }




  

    function displayVisibleContent()
    {
        $('.progress-bar').css('width', 0 + '%');
        $('#process').show('100');
        $('.progress').show('100');
    }
    function displayNoneContent()
    {
        $('#process').hide(200);
        $('.progress').hide(200);
    }
    function disabledAttribute()
    {
        $('.emptyFile').val('');
        $('.emptyFile').attr('disabled','disabled');
        $('.disabledAttr').attr('disabled','disabled');
    }
    function enableAttribute()
    {
        $('.disabledAttr').attr('disabled',false);
        $('.disabledAttr').val('Import');
        $('.emptyFile').attr('disabled',false);
    }