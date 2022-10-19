var clear_interval;
/*
|---------------------------------------------------
|   product import from aliexpress
|---------------------------------------------------
*/
    $('.message').html('');
    $('.loading').fadeOut();

    $(document).on('click','.import',function(e){
        e.preventDefault();
        $('.message').html('');
        processingFileUploadProgressBar();
    });

    function processingFileUploadProgressBar()
    {      
        var url = $('.admin_import_product_from_aliexpress').val();
        var start_page  = $('.startPage').val();
        var end_page    = $('.endPage').val();
        $.ajax({
            type: 'GET',
            url: url,
            data:{start_page:start_page,end_page:end_page},
            beforeSend:function(){
                //$('.loading').fadeIn();
                //setTimeout(function () { 
                    //clear_interval = setInterval(function(){
                        //aliexpressProductImportProgressBar();
                    //},2000);
                    //displayNoneContent();
                    //displayVisibleContent();
                    //disabledAttribute();       
                //},2000)
            },
            success: function(response){
                //$('.message').html(response);
                //$('.message').css({
                    //"background-color":"#rgb(236 243 251)",
                    //"color" : '#10bb10'
                //});
            },
            complete:function(){
                //$('.loading').fadeOut();
            },
        });
        //end ajax
    }                                                                                                               
    /**product import from aliexpress*/



    $(document).on('click','.import',function(e){
        e.preventDefault();
        $('.currentPageNo').hide();
        $('.current_page').text("");
        $('.alert-success').hide();
        $('.message').html('');
        $('#process').show();  
        $('.parcentage').text(0+'%');
        //setTimeout(function () { 
            clear_interval = setInterval(function(){
                aliexpressProductImportProgressBar();
            },5000);
            displayNoneContent();
            displayVisibleContent();
            disabledAttribute();       
        //},5000)
    });
    
    function aliexpressProductImportProgressBar()
    {
        var url = $('.aliexpressProductImportProgressingBar').val();
        $.ajax({
            type: 'GET',
            url: url,
            beforeSend:function(){
                //$('.loading').fadeIn();
            },
            success: function(response){
                $('.currentPageNo').show();
                $('.current_page').text(response.currentPage);
                if(response.totalRow > 0)
                {
                    //console.log("reamining.. "+ response.remainingInsertRow);
                    // console.log("inserting... Total row : "+response.totalRow + ", Inserted row : " + response.insertedRow);
                    var width = Math.round((response.insertedRow/response.totalRow)*100);
                    //console.log("first: "+width);
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
                    if(response.nextPage == 0)
                    {
                        clearInterval(clear_interval);
                        $('.currentPageNo').hide();
                        $('.current_page').text("");
                        $('.alert-success').show();
                        $('#process').hide(); 
                        $('.parcentage').text(0+'%');
                        $('.text-left').html(response.message);
                        displayNoneContent();
                        enableAttribute();
                    }
                    afterCompletedProgressBar();
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
        var url = $('.updateInsertedValueAfterCompletedProgressBar').val();
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
                });
                $('.alert-success').show();
                $('.text-left').html(response.message);
                displayNoneContent();
                enableAttribute();
                $('.parcentage').text(0+'%'); */
                //console.log("*****************************************************");
            },
            complete:function(){
                //$('.loading').fadeOut();
            },
        });
        //end ajax
    }




    // if start page is empty
    $(document).on('keyup','.startPage,.endPage',function(){
        var startPage   = $('.startPage').val();
        var endPage     = $('.endPage').val();
        
        var submitable      = 0;
        var startPageAction = 0;
        var pressStartPage  = 0;
        var endPageAction   = 0;
        var pressEndPage    = 0;
        if(($('.startPage').val()).length > 0)
        {
            pressStartPage  = 1;
            if(Math.sign(startPage) != 1)
            {
                startPageAction   = 0;
                $('.error_mess_startPage').text('Invalid number format');
            }else{
                startPageAction   = 1;
                $('.error_mess_startPage').text('');
            } 
        }else{
            pressStartPage      = 0;
            startPageAction     = 0;
            $('.error_mess_startPage').text('');
        }
        if(($('.endPage').val()).length > 0)
        {
            pressEndPage    = 1;
            if(Math.sign(endPage) != 1)
            {
                endPageAction     = 0;
                $('.error_mess_endPage').text('Invalid number format');
            }else{
                if(parseInt($('.startPage').val()) <= parseInt($('.endPage').val()))
                {
                    endPageAction     = 1;
                    $('.error_mess_endPage').text('');
                }else{
                    endPageAction     = 0;
                    $('.error_mess_endPage').text('Invalid number format : End page must be greater than Start Page');
                }
            }
        }else{
            pressEndPage        = 0;
            endPageAction       = 0;
            $('.error_mess_endPage').text('');
        }
        
        if((startPageAction == 1 && pressStartPage == 1) && pressEndPage == 0)
        {
            submitable = 1;
        }
        else if((startPageAction == 1 && pressStartPage == 1) && (pressEndPage == 1 && endPageAction == 0))
        {
            submitable = 0;
        }
        else if((startPageAction == 1 && pressStartPage == 1) && (pressEndPage == 1 && endPageAction == 1))
        {
            submitable = 1;
        }
        else if((startPageAction == 0 && pressStartPage == 1) && (pressEndPage == 1 && endPageAction == 1))
        {
            submitable = 0;
        }else if((startPageAction == 0 && pressStartPage == 1) && (pressEndPage == 1 && endPageAction == 0))
        {
            submitable = 0;
        }

        if(submitable == 1)
        {
            $('.disabledAttr').removeAttr('disabled');
        }else{
            $('.disabledAttr').attr('disabled','disabled');
        }
    });


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