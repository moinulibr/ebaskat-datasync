<?php



    /*
    |-------------------------------------------------------------------------------------------------------
    | Start from here
    |-------------------------------------------------------------------------------------------------------
    */



        /*
        |-------------------------------------------------------
        |       woocommerce api customer key and secret
        |-------------------------------------------------------
        */
            function restapiCustomerKeyForWoocommerce()
            {
                return "ck_2865b286fb34a92b54a8bb1c79a7afdc513a9927";
            }
            function restapiCustomerSecretForWoocommerce()
            {
                return "cs_e91b3cbd56ad3578830c15d7d6248410c52ee206";
            }
        /*
        |-------------------------------------------------------
        |       woocommerce api customer key and secret
        |-------------------------------------------------------
        */



        /*
        |-------------------------------------------------------
        |       bigbuy  api  key and secret for production
        |-------------------------------------------------------
        */ 
            function bigbuyApiUrl_hd()
            {
                return "https://api.bigbuy.eu";//production
                return "https://api.sandbox.bigbuy.eu";//sandbox
                if(strtolower(config('app.env'))!='production')
                {
                    return "https://api.sandbox.bigbuy.eu";//sandbox
                }else{
                    return "https://api.bigbuy.eu";//production
                }
            } 
            function bigbuyApiKey_hd()
            {  
                //production key 
                return 'ZWQwYzFhZDczZGY0ZmNlYjk0ZGQwZTMwNmUwNGRkOGUyOTUyZDNlMGYzOTE1NDRlZjNiYzFiZmJiNjczMWNkYw';
                //sandbox key
                return 'ZGRiZWQwNTExY2Q4ZGUxYzUzMDkwZWQ2NGMyN2YxNGJhODJhMjA5N2E4MjRkNmRkYzA0MGRiN2U5MWEyMDU0Zg';
                if(strtolower(config('app.env'))!='production')
                {
                    //sandbox key
                    return 'ZGRiZWQwNTExY2Q4ZGUxYzUzMDkwZWQ2NGMyN2YxNGJhODJhMjA5N2E4MjRkNmRkYzA0MGRiN2U5MWEyMDU0Zg';
                }else{
                    //production key 
                    return 'ZWQwYzFhZDczZGY0ZmNlYjk0ZGQwZTMwNmUwNGRkOGUyOTUyZDNlMGYzOTE1NDRlZjNiYzFiZmJiNjczMWNkYw';
                }
            }
        /*
        |-------------------------------------------------------
        |       bigbuy  api  key and secret for production
        |-------------------------------------------------------
        */

        /*
        |--------------------------------------------------------------
        |     ebaskat queue project dependency: base url and app key
        |-------------------------------------------------------
        */
            //base urls : et base url in the queueBaseUrl_hd() function. 
		    //#[base url is :- ebaskat-queue hosted base url]
            function queueBaseUrl_hd()
            {
                //return "http://localhost:8000";
                return "https://mail-queue.ebaskat.com";
            }

            //app key , set app key in the queueHeaderXAppKey_hd() function
		    //#[app key is :- ebaskat-queue app_key (its from .env files)]
            function queueHeaderXAppKey_hd()
            {
                //return "3jaww6dXHjNq8Frc9AveaWI87PJUeBfs";//for my local
                return "base64:J2dirHj3pYq4+d9A63XS3Pva18Uo9bwIN5VB1dsFHVM=";
            }
        /*
        |-------------------------------------------------------
        |     ebaskat queue project dependency: base url and app key
        |---------------------------------------------------------------
        */

        
          
        /*
        |------------------------------------------------------------
        | bigbuy order note
        |------------------------------------------------------------
        */
        function bigbuyOrderNote_hd()
        {
            return "Ebaskat";
            //return "We are dropshipper, so please don't provide bigbuy invoice to the customer. 
            //If possible, please make a invoice by our company name 'Ebaskat'";
        }  
        /*
        |------------------------------------------------------------
        | bigbuy order note
        |------------------------------------------------------------
        */


        
        /*
        |------------------------------------------------------------
        | add extra ebaskat charge when product import from bigbuy
        |------------------------------------------------------------
        */
        function addExtraChargeForEbaskat_hd($price)
        {
            if($price <= 50 )
            {
                return 5;
            }
            else if($price > 50 && $price <= 100)
            {
                return 7;
            }
            else if($price > 100)
            {
                return 9;
            }
        }  
        /*
        |------------------------------------------------------------
        | add extra ebaskat charge when product import from bigbuy
        |------------------------------------------------------------
        */

        /*
        |------------------------------------------------------------
        | default shipping short country for bigbuy
        |------------------------------------------------------------
        */
            function defaultShippingCountryForBigbuy_hd()
            {
                return "IE"; // Ireland
            } 
            function defaultShippingPostCodeForBigbuy_hd()
            {
                return "D01K196"; // Ireland
            } 

        /*
        |------------------------------------------------------------
        | default shipping short country for bigbuy
        |------------------------------------------------------------
        */



        /*
        |-------------------------------------------------------
        |       product import from aliexpress
        |-------------------------------------------------------
        */
            /*
             * all default id
             */

            /*
            * default ebaskat prime id : aliexpress
            */
            function defaultEbaskatPrimeId_hd(){
                return 1;
            }

            /*
            * default ebaskat prime bigbuy id
            */
            function defaultEbaskatPrimeBbId_hd(){
                return 2; 
            }


            function defaultCategoryId_hd(){
                return 32;
            }

            function defaultSubCategoryId_hd(){
                return 396;
            }

            function defaultChildCategoryId_hd(){
                return 1991;
            }
            
            function defaultBrandId_hd(){
                return 106;
            }
            
            function defaultCurrency_hd(){
                return 1;
            }

            /*
             * product image storage destination , Width and Height
             */
            function dropshipAliexpressProductImageStorageDestination_hd()
            {
                return "public/products";
            }
            function dropshipAliexpressProductImageWidth_hd()
            {
                return 800;
            }
            function dropshipAliexpressProductImageHeight_hd()
            {
                return 800;
            }

            /*
             * product thumbnails  image storage destination , Width and Height
             */
            function dropshipAliexpressProductThumbnailStorageDestination_hd()
            {
                return "public/thumbnails";
            }
            function dropshipAliexpressProductThumbnailWidth_hd()
            {
                return 800;
            }
            function dropshipAliexpressProductThumbnailHeight_hd()
            {
                return 800;
            }

            /*
             * product galleries image storage destination , Width and Height
             */
            function dropshipAliexpressProductGalleryStorageDestination_hd()
            {
                return "public/galleries";
            }
            function dropshipAliexpressProductGalleryWidth_hd()
            {
                return 800;
            }
            function dropshipAliexpressProductGalleryHeight_hd()
            {
                return 800;
            }
        /*
        |-------------------------------------------------------
        |       product import from aliexpress End
        |-------------------------------------------------------
        */







        /*
        |------------------------------------------------------------
        |       bulk product import 
        |------------------------------------------------------------
        */
            /*
             * bulk product image storage destination , Width and Height
             */
            function bulkProductUploadUpdateImageStorageDestination_hd()
            {
                return "public/products";
            }
            function bulkProductUploadUpdateImageWidth_hd()
            {
                return 800;
            }
            function bulkProductUploadUpdateImageHeight_hd()
            {
                return 800;
            }


            function bulkProductUploadUpdateThumbnailStorageDestination_hd()
            {
                return "public/thumbnails";
            }
            function bulkProductUploadUpdateThumbnailWidth_hd()
            {
                return 285;
            }
            function bulkProductUploadUpdateThumbnailHeight_hd()
            {
                return 285;
            }

        /*
        |------------------------------------------------------------
        |       bulk product import  End
        |------------------------------------------------------------
        */



 

        /*
        |-------------------------------------------------------
        |       product create and update
        |-------------------------------------------------------
        */
            /*
             * product image storage destination , Width and Height
             */
            function productImageStorageDestination_hd()
            {
                return "public/products";
            }
            function productImageWidth_hd()
            {
                return 400;
            }
            function productImageHeight_hd()
            {
                return NULL;
            }

            /*
             * product thumbnails  image storage destination , Width and Height
             */
            function productThumbnailStorageDestination_hd()
            {
                return "public/thumbnails";
            }
            function productThumbnailWidth_hd()
            {
                return 285;
            }
            function productThumbnailHeight_hd()
            {
                return 285;
            }

            /*
             * product galleries image storage destination , Width and Height
             */
            function productGalleryStorageDestination_hd()
            {
                return "public/galleries";
            }
            function productGalleryWidth_hd()
            {
                return 300;
            }
            function productGalleryHeight_hd()
            {
                return 300;
            }
        /*
        |-------------------------------------------------------
        |       product create and update End
        |-------------------------------------------------------
        */






        /*
        |-------------------------------------------------------
        |      Category Picture
        |-------------------------------------------------------
        */
            /*
             * Category image storage destination , Width and Height
             */
            function categoryImageStorageDestination_hd()
            {
                return "public/categories";
            }
            function categoryImageWidth_hd()
            {
                return 300;
            }
            function categoryImageHeight_hd()
            {
                return 150;
            }
        
        /*
        |-------------------------------------------------------
        |      Category Picture End
        |-------------------------------------------------------
        */

  

        /*
        |-------------------------------------------------------
        |      Brand Picture
        |-------------------------------------------------------
        */
            /*
             * Brand image storage destination , Width and Height
             */
            function brandImageStorageDestination_hd()
            {
                return "public/brand";
            }
            function brandImageWidth_hd()
            {
                return 300;
            }
            function brandImageHeight_hd()
            {
                return NULL;
            }
        /*
        |-------------------------------------------------------
        |      Brand Picture End
        |-------------------------------------------------------
        */





        /*
        |-------------------------------------------------------
        |      Banner Picture
        |-------------------------------------------------------
        */
            /*
             * Banner image storage destination , Width and Height
             */
            function bannerImageStorageDestination_hd()
            {
                return "public/banner";
            }
            function bannerImageWidth_hd()
            {
                return 300;
            }
            function bannerImageHeight_hd()
            {
                return NULL;
            }
        
            /*
        |-------------------------------------------------------
        |      Banner Picture End
        |-------------------------------------------------------
        */

        

        /*
        |-------------------------------------------------------
        |      Blog Picture
        |-------------------------------------------------------
        */
            /*
             * blog image storage destination , Width and Height
             */
            function blogImageStorageDestination_hd()
            {
                return "public/blog";
            }
            function blogImageWidth_hd()
            {
                return 300;
            }
            function blogImageHeight_hd()
            {
                return NULL;
            }
        /*
        |-------------------------------------------------------
        |      Blog Picture End
        |-------------------------------------------------------
        */



      

        /*
        |-------------------------------------------------------
        |      Profile Picture (admin and staff)
        |-------------------------------------------------------
        */
            /*
             * Admin profile picuture storage destination , Width and Height
             */
            function adminProfilePictureStorageDestination_hd()
            {
                return "public/admins";
            }
            function adminProfilePictureWidth_hd()
            {
                return 300;
            }
            function adminProfilePictureHeight_hd()
            {
                return NULL;
            }
        /*
        |-------------------------------------------------------
        |      Profile Picture End (admin and staff)
        |-------------------------------------------------------
        */


        /*
        |-------------------------------------------------------
        |    User  Profile Picture (User only)
        |-------------------------------------------------------
        */
            /*
             * User profile picuture storage destination , Width and Height
             */
            function userProfilePictureStorageDestination_hd()
            {
                return "public/users";
            }
            function userProfilePictureWidth_hd()
            {
                return 300;
            }
            function userProfilePictureHeight_hd()
            {
                return NULL;
            }
        /*
        |-------------------------------------------------------
        |    User  Profile Picture End (User only)
        |-------------------------------------------------------
        */


        /*
        |-------------------------------------------------------
        |      Review Images
        |-------------------------------------------------------
        */
            /*
             * Admin profile picuture storage destination , Width and Height
             */
            function reviewPictureStorageDestination_hd()
            {
                return "public/reviews";
            }
            function reviewPictureWidth_hd()
            {
                return 300;
            }
            function reviewPictureHeight_hd()
            {
                return NULL;
            }
        /*
        |-------------------------------------------------------
        |      Review Picture End
        |-------------------------------------------------------
        */


        /*
        |-------------------------------------------------------
        |      Service Images
        |-------------------------------------------------------
        */
            /*
             * Admin profile picuture storage destination , Width and Height
             */
            function servicePictureStorageDestination_hd()
            {
                return "public/services";
            }
            function servicePictureWidth_hd()
            {
                return 300;
            }
            function servicePictureHeight_hd()
            {
                return NULL;
            }
        /*
        |-------------------------------------------------------
        |      Service Picture End
        |-------------------------------------------------------
        */

        /*
        |-------------------------------------------------------
        |      Slider Images
        |-------------------------------------------------------
        */
            /*
             * Admin profile picuture storage destination , Width and Height
             */
            function sliderImageStorageDestination_hd()
            {
                return "public/sliders";
            }
            function sliderImageWidth_hd()
            {
                return 800;
            }
            function sliderImageHeight_hd()
            {
                return NULL;
            }
        /*
        |-------------------------------------------------------
        |      Slider Picture End
        |-------------------------------------------------------
        */
