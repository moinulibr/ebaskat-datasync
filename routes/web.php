<?php

use App\Console\Commands\SyncTable;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Psr\Http\Message\ResponseInterface;

Route::get('/',function (){
   return redirect('/admin');
});

    Route::get('clear',function(){
        Artisan::call('cache:clear');
        Artisan::call('storage:link');
        Artisan::call('view:clear');
        return redirect()->route('admin.dashboard')->with('success','Cache, Storage Link, View  Clear');
    });

    Route::get('/bigbuy/importable/products/from/primary/db', 'Admin\DropshipBibguyController@bigbuyProductImportFromPrimaryDB')->name('bigbuy.product.import.from.db');

    Route::get('/bigbuy/product/update/by/queue/{jobNo?}', 'Admin\DropshipBibguyController@updateImportedProductByQueue')->name('admin.dropship.bigbuy.update.imported.product.by.queue');

    Route::get('/aliexpress/product/update/by/queue/{lastpage?}', 'Admin\DropshipAliexpressController@updateImportedProductByQueue')->name('admin.dropship.aliexpress.update.imported.product.by.queue');


Route::prefix('admin')->group(function () {
    // LOGIN
    Route::get('/login', 'Admin\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Admin\LoginController@login')->name('admin.login.submit');
    Route::get('/forgot', 'Admin\LoginController@showForgotForm')->name('admin.forgot');
    Route::post('/forgot', 'Admin\LoginController@forgot')->name('admin.forgot.submit');
    Route::get('/change-password/{token}', 'Admin\LoginController@showChangePassForm')->name('admin.change.token');
    Route::post('/change-password', 'Admin\LoginController@changePassword')->name('admin.change.password');
    Route::get('/logout', 'Admin\LoginController@logout')->name('admin.logout');



    
    Route::group(['middleware' => 'permissions:products'], function () {

        Route::get('/products/datatables/{search?}', 'Admin\ProductController@datatables')->name('admin.prod.datatables'); //JSON REQUEST
        Route::get('/products', 'Admin\ProductController@index')->name('admin.product.index');
        Route::get('/products/list', 'Admin\ProductController@productList')->name('admin.product.list.ajaxresponse');
        Route::get('/product/detail', 'Admin\ProductController@productDetail')->name('admin.product.detail');
        Route::get('/product/promotion-level', 'Admin\ProductController@promotionLevel')->name('admin.product.promotion-level');
        Route::get('/product/change-promotion', 'Admin\ProductController@promotionStatus')->name('admin.product.promotion.change');

        Route::post('/products/upload/update/{id}', 'Admin\ProductController@uploadUpdate')->name('admin.product.upload.update')->middleware('permissions:products|edit');

        Route::get('/products/deactive/datatables', 'Admin\ProductController@deactivedatatables')->name('admin.product.deactive.datatables')->middleware('permissions:products|add'); //JSON REQUEST
        Route::get('/products/deactive', 'Admin\ProductController@deactive')->name('admin.product.deactive')->middleware('permissions:products|add');

        Route::get('/products/promotion/datatables', 'Admin\ProductController@promotiondatatables')->name('admin.product.promotion.datatables'); //JSON REQUEST
        Route::get('/products/promotion-ajax', 'Admin\ProductController@promotionList')->name('admin.product.promotion.ajax');
        Route::get('/products/promotion', 'Admin\ProductController@promotion')->name('admin.product.promotion');


        Route::get('/products/catalogs/datatables', 'Admin\ProductController@catalogdatatables')->name('admin.product.catalog.datatables')->middleware('permissions:products|edit_catalog'); //JSON REQUEST
        Route::get('/products/catalogs/', 'Admin\ProductController@catalogs')->name('admin.product.catalog.index')->middleware('permissions:products|edit_catalog');

        // CREATE SECTION
        Route::get('/products/types', 'Admin\ProductController@types')->name('admin.product.types')->middleware('permissions:products|add');
        Route::get('/products/physical/create', 'Admin\ProductController@createPhysical')->name('admin.product.physical.create')->middleware('permissions:products|add');

        	/**ckEditor */
        Route::post('/products/create/ckeditor/upload/image', 'Admin\ProductController@productCreateCkEditorUploadImage')->name('product.create.Ckeditor.upload.image');


        Route::get('/products/digital/create', 'Admin\ProductController@createDigital')->name('admin.prod.digital.create');
        Route::get('/products/license/create', 'Admin\ProductController@createLicense')->name('admin.prod.license.create');
        Route::post('/products/store', 'Admin\ProductController@store')->name('admin.product.store')->middleware('permissions:products|add');
        Route::get('/getattributes', 'Admin\ProductController@getAttributes')->name('admin.product.getattributes');
        // CREATE SECTION

        // EDIT SECTION
        Route::get('/products/edit/{id}', 'Admin\ProductController@edit')->name('admin.product.edit')->middleware('permissions:products|edit');
        Route::post('/products/edit/{id}', 'Admin\ProductController@update')->name('admin.product.update')->middleware('permissions:products|edit');

        Route::get('/products/category/edit', 'Admin\ProductController@categoryEdit')->name('admin.product.category.edit')->middleware('permissions:products|edit');
        Route::post('/products/single/category/update', 'Admin\ProductController@categoryUpdate')->name('admin.product.category.update')->middleware('permissions:products|edit');
        Route::get('/products/subcategory/by/categoryid', 'Admin\ProductController@subCategoryByCatId')->name('admin.product.subcat.by.categoryid')->middleware('permissions:products|edit');

        Route::get('/products/brands/by/merchant', 'Admin\ProductController@getBrandsByMerchant')->name('admin.get.brands.merchant')->middleware('permissions:products|edit');
        // EDIT SECTION ENDS

        // Route::get('/products/variation/remove', 'Admin\ProductController@variationRemove')->name('product.variation.remove')->middleware('permissions:products|edit');
        Route::get('/products/variation/atribute/edit', 'Admin\ProductController@variationAtrEdit')->name('product.variation_atribute.edit')->middleware('permissions:products|edit');
        Route::get('/products/variation/dimension/edit', 'Admin\ProductController@variationDimeEdit')->name('product.variation_dimension.edit')->middleware('permissions:products|edit');

        Route::get('/products/delete/{id}', 'Admin\ProductController@destroy')->name('admin.product.delete')->middleware('permissions:products|delete');
        Route::get('/products/destroy', 'Admin\ProductController@destroyProduct')->name('admin.destroy.product')->middleware('permissions:products|delete');
        Route::get('/products/restore/{id}', 'Admin\ProductController@restore')->name('admin.prod.restore')->middleware('permissions:recover_delete');

        Route::get('/products/catalog/{id1}/{id2}', 'Admin\ProductController@catalog')->name('admin.prod.catalog')->middleware('permissions:products|edit_catalog');

        Route::get('/products/status/{id1}/{id2}', 'Admin\ProductController@status')->name('admin.product.status')->middleware('permissions:products|edit');
        Route::get('/products/promotion/status/{id1}/{id2}/{id3}', 'Admin\ProductController@promotionchange')->name('admin.product.promotion_status')->middleware('permissions:products|edit');
        Route::get('/products/feature/{id}', 'Admin\ProductController@feature')->name('admin.product.feature')->middleware('permissions:products|edit');
        Route::post('/products/feature/{id}', 'Admin\ProductController@featuresubmit')->name('admin.product.feature')->middleware('permissions:products|edit');

        Route::get('/products/tags', 'Admin\ProductController@getTags')->name('admin.products.tags.get');

    });

    //Dropshipping - aliexpress : product
    Route::group(['middleware' => 'permissions:products'], function () {
        Route::get('/dropship/aliexpress', 'Admin\DropshipAliexpressController@index')->name('admin_dropship_aliexpress_index')->middleware('permissions:products|aliexpress_add');
        Route::get('/aliexpress/product/import/by/queue', 'Admin\DropshipAliexpressController@importProductFromAliexpressByQueue')->name('admin.dropship.aliexpress.product.import.by.queue');
        Route::get('/import/product/from/aliexpress/', 'Admin\DropshipAliexpressController@productImportFromAliexpress')->name('admin_import_product_from_aliexpress')->middleware('permissions:products|aliexpress_add');
        Route::get('/import/aliexpress/product/progress/bar/', 'Admin\DropshipAliexpressController@aliexpressProductImportProgressBar')->name('admin.dropship.aliexpress.product.import.progress.bar');
        Route::get('/import/aliexpress/product/after/completed/progress/bar', 'Admin\DropshipAliexpressController@updateInsertedValueAfterCompletedProgressBar')->name('admin.dropship.aliexpress.update.value.after.completed.progress.bar');
        
        Route::get('/update/imported/aliexpress/product', 'Admin\DropshipAliexpressController@updateImportedProductByPage')->name('admin.dropship.aliexpress.update.imported.product.by.page')->middleware('permissions:products|aliexpress_update');
        Route::get('/update/imported/aliexpress/product/by/page', 'Admin\DropshipAliexpressController@updateImportedProduct')->name('admin.dropship.aliexpress.update.imported.product')->middleware('permissions:products|aliexpress_update');

        Route::get('/get/updating/result/by/progress/bar/aliexpress/product/update', 'Admin\DropshipAliexpressController@getUpdatingRowForProductUpdateProgressBar')->name('admin.dropship.aliexpress.updated.value.for.product.update.progress.bar');
        Route::get('update/imported/aliexpress/product/after/completed/progress/bar', 'Admin\DropshipAliexpressController@updatedValueAfterCompletingProgressBarForProductUpdateProgressBar')->name('admin.dropship.aliexpress.update.value.after.completed.progress.bar.for.product.update');
        
        Route::get('aliexpress/single/product/update/by/sku/', 'Admin\DropshipAliexpressController@displaySingleProductUpdateByProductSku')->name('admin.aliexpress.display.single.product.update.by.sku');
        Route::get('aliexpress/single/product/updating/by/sku/', 'Admin\DropshipAliexpressController@updatingSingleProductByProductSku')->name('admin.aliexpress.single.product.updating.by.sku');

        Route::get('aliexpress/single/product/import/by/aliexpress/product/id', 'Admin\DropshipAliexpressController@displaySingleProductImportByProductId')->name('admin.aliexpress.display.single.product.import.by.id');
        Route::get('aliexpress/single/product/importing/by/aliexpress/product/id', 'Admin\DropshipAliexpressController@importingSingleProductByProductId')->name('admin.aliexpress.single.product.importing.by.id');

    });


    //Dropshipping - bigbuy : product
    Route::group(['middleware' => 'permissions:products'], function () {
        Route::get('/dropshipping/bigbuy', 'Admin\DropshipBibguyController@index')->name('adminDropshippingBigbuyIndex')->middleware('permissions:products|bigbuy_add');
        Route::get('/import/product/from/bigbuy/', 'Admin\DropshipBibguyController@productImportFromBigbuy')->name('adminImportProductFromBigbuy')->middleware('permissions:products|bigbuy_add');
        Route::get('/import/bigbuy/product/progress/bar/', 'Admin\DropshipBibguyController@bigbuyProductImportProgressBar')->name('admin.dropship.bigbuy.product.import.progress.bar');
        Route::get('/import/bigbuy/product/after/completed/progress/bar', 'Admin\DropshipBibguyController@updateInsertedValueAfterCompletedProgressBar')->name('admin.dropship.bigbuy.update.value.after.completed.progress.bar');

        Route::get('/bigbuy/product/import/by/queue', 'Admin\DropshipBibguyController@autoImportingProductByQueue')->name('admin.dropship.bigbuy.auto.import.product.by.queue');

        Route::get('/update/imported/bigbuy/single/product', 'Admin\DropshipBibguyController@updateImportedSingleProduct')->name('admin.dropship.bigbuy.update.imported.single.product');
        Route::get('/update/imported/bigbuy/single/product/by/sku', 'Admin\DropshipBibguyController@updateImportedSingleProductBySku')->name('admin.dropship.bigbuy.update.imported.single.product.by.sku');
        Route::get('/get/updating/row/when/updating/bigbuy/single/product/by/sku/for/progress/bar', 'Admin\DropshipBibguyController@getUpdatingRowWhenUpdatingSingleProductBySkuForProgressBar')->name('admin.dropship.bigbuy.get.updated.row.when.updating.single.product.by.sku.for.progress.bar');
        Route::get('/update/value/when/updating/bigbuy/single/product/by/sku/for/progress/bar', 'Admin\DropshipBibguyController@updatedValueAfterCompletingProgressBarWhenSingleProductUpdatedBySkuWithProgressBar')->name('admin.dropship.bigbuy.update.value.after.completed.progress.bar.when.single.product.update.by.sku');

        Route::get('/update/imported/bigbuy/product', 'Admin\DropshipBibguyController@updateImportedProductByPage')->name('admin.dropship.bigbuy.update.imported.product.by.page')->middleware('permissions:products|bigbuy_update');
        Route::get('/update/imported/bigbuy/product/by/page', 'Admin\DropshipBibguyController@updateImportedProduct')->name('admin.dropship.bigbuy.update.imported.product')->middleware('permissions:products|bigbuy_update');
        Route::get('/get/updating/result/by/progress/bar/bigbuy/product/update', 'Admin\DropshipBibguyController@getUpdatingRowForProductUpdateProgressBar')->name('admin.dropship.bigbuy.updated.value.for.product.update.progress.bar');
        Route::get('update/imported/bigbuy/product/after/completed/progress/bar', 'Admin\DropshipBibguyController@updatedValueAfterCompletingProgressBarForProductUpdateProgressBar')->name('admin.dropship.bigbuy.update.value.after.completed.progress.bar.for.product.update');

                
        Route::get('bigbuy/single/product/import/by/sku/', 'Admin\DropshipBibguyController@displaySingleProductImportByProductSku')->name('admin.bigbuy.display.single.product.import.by.sku');
        Route::get('bigbuy/single/product/importing/by/sku/', 'Admin\DropshipBibguyController@importingSingleProductByProductSku')->name('admin.bigbuy.single.product.importing.by.sku');
    });

    //Dropshipping - aliexpress : product review import by queue
    Route::group(['middleware' => 'permissions:products'], function () {
        Route::get('product/review/import/from/aliexpress', 'Admin\ProductReviewController@bulkProductReviewsImportFromAliexpress')->name('admin.bulk.product.reviews.import.from.aliexpress');
    });
    //product published / unpublished : product
    Route::group(['middleware' => 'permissions:products'], function () {
        Route::get('/products/unpublished/list', 'Admin\ProductPublishingStatusController@unpublishedProductList')->name('admin.products.unpublished.list');
        Route::get('/products/unpublished/list/ajax/response', 'Admin\ProductPublishingStatusController@unpublishedProductListAjaxResponse')->name('admin.products.unpublished.list.ajax.response');
        Route::post('unpublished/products/publishing', 'Admin\ProductPublishingStatusController@unpublishedProductPublishing')->name('admin.unpublished.products.publishing');
        Route::post('unpublished/products/deleting', 'Admin\ProductPublishingStatusController@unpublishedProductDeleting')->name('admin.unpublished.products.deleting');
        //effect single products
        Route::get('published/product/up-publishing/{id}', 'Admin\ProductPublishingStatusController@publishedProductUnpublishing')->name('admin.published.product.unpublishing');
        Route::get('unpublished/product/publishing/{id}', 'Admin\ProductPublishingStatusController@unpublishedProductRepublishing')->name('admin.unpublished.product.unpublishing');
    });
    //product published / unpublished : product





    //no need
    //------------ ADMIN AFFILIATE PRODUCT SECTION ------------
    Route::group(['middleware' => 'permissions:affilate_products'], function () {

        Route::get('/products/import/create', 'Admin\ImportController@createImport')->name('admin.import.create')->middleware('permissions:affilate_products|add');
        Route::get('/products/import/edit/{id}', 'Admin\ImportController@edit')->name('admin.import.edit')->middleware('permissions:affilate_products|edit');


        Route::get('/products/import/datatables', 'Admin\ImportController@datatables')->name('admin.import.datatables'); //JSON REQUEST
        Route::get('/products/import/index', 'Admin\ImportController@index')->name('admin.import.index');

        Route::post('/products/import/store', 'Admin\ImportController@store')->name('admin.import.store')->middleware('permissions:affilate_products|add');
        Route::post('/products/import/update/{id}', 'Admin\ImportController@update')->name('admin.import.update')->middleware('permissions:affilate_products|edit');

        Route::get('/affiliate/products/delete/{id}', 'Admin\ProductController@destroy')->name('admin.affiliate.product.delete')->middleware('permissions:affilate_products|delete');
        Route::get('/affiliate/products/restore/{id}', 'Admin\ProductController@restore')->name('admin.affiliate.prod.restore')->middleware('permissions:recover_delete');

    });

    //------------ ADMIN AFFILIATE PRODUCT SECTION ENDS ------------

    



    Route::get('/category/datatables', 'Admin\CategoryController@datatables')->name('admin.category.datatables'); //JSON REQUEST
    Route::get('/category', 'Admin\CategoryController@index')->name('admin.category.index');
    Route::get('/category/create', 'Admin\CategoryController@create')->name('admin.category.create')->middleware('permissions:categories|add');
    Route::post('/category/create', 'Admin\CategoryController@store')->name('admin.category.store')->middleware('permissions:categories|add');
    Route::get('/category/edit/{id}', 'Admin\CategoryController@edit')->name('admin.category.edit')->middleware('permissions:categories|edit');
    Route::post('/category/edit/{id}', 'Admin\CategoryController@update')->name('admin.category.update')->middleware('permissions:categories|edit');
    Route::get('/category/delete/{id}', 'Admin\CategoryController@destroy')->name('admin.category.delete')->middleware('permissions:categories|delete');
    Route::get('/category/restore/{id}', 'Admin\CategoryController@restore')->name('admin.cat.restore')->middleware('permissions:recover_delete');
    Route::get('/category/status/{id1}/{id2}', 'Admin\CategoryController@status')->name('admin.category.status')->middleware('permissions:categories|edit');

    //category wise products price update
    Route::get('/category/wise/product/price/update', 'Admin\CategoryWisePriceUpdateAndRestoreController@index')->name('admin.category.wise.product.price.change.index');
    Route::get('/category/wise/product/price/updating', 'Admin\CategoryWisePriceUpdateAndRestoreController@store')->name('admin.category.wise.product.price.change.store');
    //category wise products price update
    //category wise products price restore
    Route::get('/category/wise/product/price/restore', 'Admin\CategoryWisePriceUpdateAndRestoreController@restore')->name('admin.category.wise.product.price.restore.index');
    Route::get('/category/wise/product/price/restoring', 'Admin\CategoryWisePriceUpdateAndRestoreController@restoreProcess')->name('admin.category.wise.product.price.restore.process');
    //category wise products price restore

    /**Brand */
    Route::group(['middleware' => 'permissions:brands_manage'], function () {
        Route::get('/brands', 'Admin\BrandController@index')->name('admin.brand.index');
        Route::get('/brands/create', 'Admin\BrandController@create')->name('admin.brand.create')->middleware('permissions:brands_manage|add');
        Route::post('/brands/store', 'Admin\BrandController@store')->name('admin.brand.store')->middleware('permissions:brands_manage|add');
        Route::get('/brands/show', 'Admin\BrandController@show')->name('admin.brand.show');
        Route::get('/brands/edit', 'Admin\BrandController@edit')->name('admin.brand.edit')->middleware('permissions:brands_manage|edit');
        Route::post('/brands/update', 'Admin\BrandController@update')->name('admin.brand.update')->middleware('permissions:brands_manage|edit');
        Route::get('/brands/delete/{id?}', 'Admin\BrandController@destroy')->name('admin.brand.delete')->middleware('permissions:brands_manage|delete');
        Route::get('/brands/restore/{id}', 'Admin\BrandController@restore')->name('admin.brand.restore')->middleware('permissions:recover_delete');
    });



    // DASHBOARD & PROFILE
    Route::get('/', 'Admin\DashboardController@index')->name('admin.dashboard');
    Route::get('/dashboard/chart/load/by/ajax', 'Admin\DashboardController@dashboardChartLoadByAjax')->name('admin.dashboard.chart.load.by.ajax');
    Route::get('/profile', 'Admin\DashboardController@profile')->name('admin.profile');
    Route::post('/profile/update', 'Admin\DashboardController@profileUpdate')->name('admin.profile.update');
    Route::get('/password', 'Admin\DashboardController@passwordReset')->name('admin.password');
    Route::post('/password/update', 'Admin\DashboardController@changePassword')->name('admin.password.update');


    // ORDER
    //MAIN ORDER ROUTES
    Route::get('/main/orders/list', 'Admin\OrderController@mainOrderList')->name('admin.main.order.list');
    
    Route::group(['middleware' => 'permissions:orders'], function () {
        Route::get('/orders/datatables/{slug}', 'Admin\OrderController@datatables')->name('admin.order.datatables'); //JSON REQUEST
        Route::get('/orders/{status?}', 'Admin\OrderController@index')->name('admin.order.index');
        Route::get('/main/orders/{status?}', 'Admin\OrderController@mainOrder')->name('admin.main.order.index');

        //not using this.. now managing all kinds of status from index page
            Route::get('/orders/pending', 'Admin\OrderController@pending')->name('admin.order.pending');
            Route::get('/orders/processing', 'Admin\OrderController@processing')->name('admin.order.processing');
            Route::get('/orders/completed', 'Admin\OrderController@completed')->name('admin.order.completed');
            Route::get('/orders/declined', 'Admin\OrderController@declined')->name('admin.order.declined');
            Route::get('/orders/on-delivery', 'Admin\OrderController@onDelivery')->name('admin.order.on_delivery');
            Route::get('/orders/partial-delivered', 'Admin\OrderController@partialDelivered')->name('admin.order.partial_delivered');
        //not using this.. now managing all kinds of status from index page

        Route::get('/orders/search', 'Admin\OrderSearchController@search')->name('admin.search.order');
        Route::get('/order/edit/{id}', 'Admin\OrderController@edit')->name('admin.order.edit')->middleware('permissions:orders|edit');
        Route::post('/order/update/{id}', 'Admin\OrderController@update')->name('admin.order.update');
        Route::get('/order/{id}/show', 'Admin\OrderController@show')->name('admin.order.show');
        Route::get('/order/{id}/invoice', 'Admin\OrderController@invoice')->name('admin.order.invoice');
        Route::get('/order/{id}/print', 'Admin\OrderController@printpage')->name('admin.order.print');
        Route::get('/order/{id1}/status/{status}', 'Admin\OrderController@status')->name('admin.order.status');
        Route::post('/order/email/', 'Admin\OrderController@emailsub')->name('admin.order.emailsub')->middleware('permissions:orders|send_email');
        Route::post('/order/{id}/license', 'Admin\OrderController@license')->name('admin.order.license');

        Route::get('/order/{id}/track', 'Admin\OrderTrackController@index')->name('admin.order.track')->middleware('permissions:orders|track');
        Route::get('/order/{id}/trackload', 'Admin\OrderTrackController@load')->name('admin.order.track.load');
        Route::post('/order/track/store', 'Admin\OrderTrackController@store')->name('admin.order.track.store');
        Route::get('/order/track/add', 'Admin\OrderTrackController@add')->name('admin.order.track.add');
        Route::get('/order/track/edit/{id}', 'Admin\OrderTrackController@edit')->name('admin.order.track.edit');
        Route::post('/order/track/update/{id}', 'Admin\OrderTrackController@update')->name('admin.order.track.update')->middleware('permissions:orders|edit');
        Route::get('/order/track/delete/{id}', 'Admin\OrderTrackController@delete')->name('admin.order.track.delete');
    });

    // main Order status and delivery status
    Route::group(['middleware' => 'permissions:orders'], function () {
        Route::get('/admin/main/order/show/delivery/status/details', 'Admin\MianOrderStatusController@mainOrderShowDeliveryStatusDetails')->name('admin.main.order.show.delivery.status.details')->middleware('permissions:orders|edit');
        Route::get('/admin/main/order/status/update', 'Admin\MianOrderStatusController@mainOrderStatusUpdate')->name('admin.main.order.status.update')->middleware('permissions:orders|edit');
        Route::get('/admin/order/package/delivery/status/update/from/main/order', 'Admin\MianOrderStatusController@orderPackageDeliveryStatusUpdateFromMianOrder')->name('admin.order.package.delivery.status.update.from.main.order')->middleware('permissions:orders|edit');
        Route::get('/admin/order/product/delivery/status/update/from/main/order', 'Admin\MianOrderStatusController@orderProductDeliveryStatusUpdateFromMianOrder')->name('admin.order.product.delivery.status.update.from.main.order')->middleware('permissions:orders|edit');
    });

    // ebaskat orders
    Route::group(['prefix' => 'ebaskat','as'=>'ebaskat.','middleware' => 'permissions:orders'], function () {
        Route::get('/orders', 'Admin\EbaskatOrderController@index')->name('admin.order.index');
        Route::get('/orders/list/by/ajax', 'Admin\EbaskatOrderController@orderListByAjax')->name('admin.order.list.by.ajax');
        Route::get('/order/edit/{id}', 'Admin\EbaskatOrderController@edit')->name('admin.order.edit')->middleware('permissions:orders|edit');
        Route::post('/order/update/{id}', 'Admin\EbaskatOrderController@update')->name('admin.order.update');
        /* Route::get('/orders/pending', 'Admin\EbaskatOrderController@pending')->name('admin.order.pending'); */
        /* Route::get('/orders/processing', 'Admin\EbaskatOrderController@processing')->name('admin.order.processing'); */
        /* Route::get('/orders/completed', 'Admin\EbaskatOrderController@completed')->name('admin.order.completed'); */
        /* Route::get('/orders/declined', 'Admin\EbaskatOrderController@declined')->name('admin.order.declined'); */
        Route::get('/order/{id}/show', 'Admin\EbaskatOrderController@show')->name('admin.order.show');
        Route::get('/order/{id}/invoice', 'Admin\EbaskatOrderController@invoice')->name('admin.order.invoice');
        Route::get('/order/{id}/print', 'Admin\EbaskatOrderController@printpage')->name('admin.order.print');
        Route::get('/order/{id1}/status/{status}', 'Admin\EbaskatOrderController@status')->name('admin.order.status');
        Route::post('/order/email/', 'Admin\EbaskatOrderController@emailsub')->name('admin.order.emailsub')->middleware('permissions:orders|send_email');
        Route::post('/order/{id}/license', 'Admin\EbaskatOrderController@license')->name('admin.order.license');
        Route::get('/orders/search', 'Admin\OrderSearchController@search')->name('admin.search.order');

        Route::get('/order/{id}/track', 'Admin\EbaskatOrderTrackController@index')->name('admin.order.track')->middleware('permissions:orders|track');
        Route::get('/order/{id}/trackload', 'Admin\EbaskatOrderTrackController@load')->name('admin.order.track.load');
        Route::post('/order/track/store', 'Admin\EbaskatOrderTrackController@store')->name('admin.order.track.store');
        Route::get('/order/track/add', 'Admin\EbaskatOrderTrackController@add')->name('admin.order.track.add');
        Route::get('/order/track/edit/{id}', 'Admin\EbaskatOrderTrackController@edit')->name('admin.order.track.edit');
        Route::post('/order/track/update/{id}', 'Admin\EbaskatOrderTrackController@update')->name('admin.order.track.update')->middleware('permissions:orders|edit');
        Route::get('/order/track/delete/{id}', 'Admin\EbaskatOrderTrackController@delete')->name('admin.order.track.delete');
    });


    // Aliexpress Order
    Route::group(['prefix' => 'aliexpress','as'=>'aliexpress.','middleware' => 'permissions:orders'], function () {
        Route::get('/orders', 'Admin\AliexpressOrderController@index')->name('admin.order.index');
        Route::get('/orders/list/by/ajax', 'Admin\AliexpressOrderController@orderListByAjax')->name('admin.order.list.by.ajax');
        Route::get('/order/{id}/show', 'Admin\AliexpressOrderController@show')->name('admin.order.show');
        //order to aliexpress
        Route::get('/order/{id}/to/aliexpress', 'Admin\AliexpressOrderController@orderToAliexpress')->name('adminOrderToAliexpress');
        Route::post('/bulk/order/place', 'Admin\AliexpressOrderController@bulkOrderToAliexpress')->name('admin.bulk.order.to.aliexpress');
        //update order status from obaskat
        Route::get('/order/status/update/from/aliexpress', 'Admin\AliexpressOrderStatusController@updateOrderStatusFromAliexpress')->name('admin.update.order.status.from.aliexpress');
    });

    // Aliexpress Order status and delivery status
    Route::group(['prefix' => 'aliexpress','as'=>'aliexpress.','middleware' => 'permissions:orders'], function () {
        Route::get('/order/delivery/status', 'Admin\AliexpressOrderStatusController@orderDeliveryStatus')->name('admin.delivery.status');
        Route::get('/order/tracking/details', 'Admin\AliexpressOrderStatusController@orderTrackingDetails')->name('admin.tracking.details');
        Route::get('/order/package/status/update', 'Admin\AliexpressOrderStatusController@orderPackageStatusUpdate')->name('admin.order.package.status.update');
        Route::get('/order/product/status/update', 'Admin\AliexpressOrderStatusController@orderProductStatusUpdate')->name('admin.order.product.status.update');
        Route::get('/order/package/status/sync/individually', 'Admin\AliexpressOrderStatusController@orderPackageStatusUpdateBySyncingIndividually')->name('admin.order.package.status.update.by.syncing.individually');
        Route::post('/order/package/status/sync/bulking', 'Admin\AliexpressOrderStatusController@orderPackageStatusUpdateBySyncingBulking')->name('admin.order.package.status.update.by.syncing.bulking');
    });


    // bigbuy Order
    Route::group(['prefix' => 'bigbuy','as'=>'bigbuy.','middleware' => 'permissions:orders'], function () {
        Route::get('/orders', 'Admin\BigbuyOrderController@index')->name('admin.order.index');
        Route::get('/orders/list/by/ajax', 'Admin\BigbuyOrderController@orderListByAjax')->name('admin.order.list.by.ajax');
        Route::get('/order/{id}/show', 'Admin\BigbuyOrderController@show')->name('admin.order.show');
        //order to bigbuy
        Route::get('/order/{id}/to/bigbuy', 'Admin\BigbuyOrderController@orderToBigbuy')->name('adminOrderToBigbuy');

        Route::get('/display/all/products/for/single/order/{id}/place/to/bigbuy', 'Admin\BigbuyOrderController@displayAllProductForSingleOrderPlace')->name('admin.display.all.products.for.single.order.place.to.bigbuy');
        Route::get('/single/order/place/to/bigbuy/', 'Admin\BigbuyOrderController@singleOrderPlacing')->name('admin.single.order.placing.to.bigbuy');

        Route::post('/bulk/order/place', 'Admin\BigbuyOrderController@bulkOrderToBigbuy')->name('admin.bulk.order.to.bigbuy');
        //update order status from obaskat
        Route::get('/order/status/update/from/bigbuy', 'Admin\BigbuyOrderStatusController@updateOrderStatusFromBigbuy')->name('admin.update.order.status.from.bigbuy');


        Route::get('/update/order/id', 'Admin\BigbuyOrderController@bigbuyOrderIdUpdate')->name('order.id.update');
        Route::get('/update/order/no/for/single/order', 'Admin\BigbuyOrderController@bigbuyOrderNoUpdateForSingleOrder')->name('order.no.update.for.single.order');
    });


    // Bigbuy Order status and delivery status
    Route::group(['prefix' => 'bigbuy','as'=>'bigbuy.','middleware' => 'permissions:orders'], function () {
        Route::get('/order/delivery/status', 'Admin\BigbuyOrderStatusController@orderDeliveryStatus')->name('admin.delivery.status');
        Route::get('/order/tracking/details', 'Admin\BigbuyOrderStatusController@orderTrackingDetails')->name('admin.tracking.details');
        Route::get('/order/package/status/update', 'Admin\BigbuyOrderStatusController@orderPackageStatusUpdate')->name('admin.order.package.status.update');
        Route::get('/order/product/status/update', 'Admin\BigbuyOrderStatusController@orderProductStatusUpdate')->name('admin.order.product.status.update');
        Route::get('/order/package/status/sync/individually', 'Admin\BigbuyOrderStatusController@orderPackageStatusUpdateBySyncingIndividually')->name('admin.order.package.status.update.by.syncing.individually');
        Route::post('/order/package/status/sync/bulking', 'Admin\BigbuyOrderStatusController@orderPackageStatusUpdateBySyncingBulking')->name('admin.order.package.status.update.by.syncing.bulking');
    });












    //------------ ADMIN USER SECTION ------------

    Route::group(['middleware' => 'permissions:customers'], function () {

        Route::get('/users/datatables', 'Admin\UserController@datatables')->name('admin.user.datatables'); //JSON REQUEST
        Route::get('/users', 'Admin\UserController@index')->name('admin.user.index');
        Route::get('/users/edit/{id}', 'Admin\UserController@edit')->name('admin.user.edit')->middleware('permissions:customers|edit');
        Route::post('/users/edit/{id}', 'Admin\UserController@update')->name('admin.user.update')->middleware('permissions:customers|edit');
        Route::get('/users/delete/{id}', 'Admin\UserController@destroy')->name('admin.user.delete')->middleware('permissions:customers|delete');
        Route::get('/users/restore/{id}', 'Admin\UserController@restore')->name('admin.user.restore')->middleware('permissions:recover_delete');
        Route::get('/user/{id}/show', 'Admin\UserController@show')->name('admin.user.show')->middleware('permissions:customers|detail');
        Route::get('/users/ban/{id1}/{id2}', 'Admin\UserController@ban')->name('admin.user.ban')->middleware('permissions:customers|edit');

        // reset password
        Route::post('/users/reset-password/{id}', 'Admin\UserController@resetPassword')->name('admin.user.reset-password')->middleware('permissions:reset_password');

    });

    //------------ ADMIN USER SECTION ENDS ------------

    //------------ ADMIN VENDOR SECTION ------------

    Route::group(['middleware' => 'permissions:merchants'], function () {

        Route::get('/vendors/datatables', 'Admin\VendorController@datatables')->name('admin.merchant.datatables');
        Route::get('/vendors', 'Admin\VendorController@index')->name('admin.merchant.index');

        Route::get('/vendors/{id}/show', 'Admin\VendorController@show')->name('admin.merchant.show');
        Route::get('/vendors/secret/login/{id}', 'Admin\VendorController@secret')->name('admin.merchant.secret');
        Route::get('/vendor/edit/{id}', 'Admin\VendorController@edit')->name('admin.merchant.edit')->middleware('permissions:merchants|edit');
        Route::post('/vendor/edit/{id}', 'Admin\VendorController@update')->name('admin.merchant.update')->middleware('permissions:merchants|edit');

        Route::get('/vendor/verify/{id}', 'Admin\VendorController@verify')->name('admin.merchant.verify');
        Route::post('/vendor/verify/{id}', 'Admin\VendorController@verifySubmit')->name('admin.merchant.verify.submit');

        Route::get('/vendor/color', 'Admin\VendorController@color')->name('admin.merchant.color');
        Route::get('/vendors/status/{id1}/{id2}', 'Admin\VendorController@status')->name('admin.merchant.status');
        Route::get('/vendors/delete/{id}', 'Admin\VendorController@destroy')->name('admin.merchant.delete')->middleware('permissions:merchants|delete');
        // reset password
        Route::post('/vendors/reset-password/{id}', 'Admin\VendorController@resetPassword')->name('admin.merchant.reset-password')->middleware('permissions:reset_password');

        Route::get('/vendors/withdraws/datatables', 'Admin\VendorController@withdrawdatatables')->name('admin.merchant.withdraw.datatables'); //JSON REQUEST
        Route::get('/vendors/withdraws', 'Admin\VendorController@withdraws')->name('admin.merchant.withdraw.index');
        Route::get('/vendors/withdraw/{id}/show', 'Admin\VendorController@withdrawdetails')->name('admin.merchant.withdraw.show');
        Route::get('/vendors/withdraws/accept/{id}', 'Admin\VendorController@accept')->name('admin.merchant.withdraw.accept');
        Route::get('/vendors/withdraws/reject/{id}', 'Admin\VendorController@reject')->name('admin.merchant.withdraw.reject');

        //  Vendor Registration Section

        Route::get('/general-settings/vendor-registration/{status}', 'Admin\GeneralSettingController@regvendor')->name('admin-gs-regvendor');

        //  Vendor Registration Section Ends


        // Verification Section

        Route::get('/verificatons/datatables/{status}', 'Admin\VerificationController@datatables')->name('admin.verification.datatables');
        Route::get('/verificatons', 'Admin\VerificationController@index')->name('admin.verification.index');
        Route::get('/verificatons/pendings', 'Admin\VerificationController@pending')->name('admin.verification.pending');

        Route::get('/verificatons/show', 'Admin\VerificationController@show')->name('admin.verification.show');
        Route::get('/verificatons/edit/{id}', 'Admin\VerificationController@edit')->name('admin.verification.edit');
        Route::post('/verificatons/edit/{id}', 'Admin\VerificationController@update')->name('admin.verification.update');
        Route::get('/verificatons/status/{id1}/{id2}', 'Admin\VerificationController@status')->name('admin.verification.status');
        Route::get('/verificatons/delete/{id}', 'Admin\VerificationController@destroy')->name('admin.verification.delete');


        // Verification Section Ends


    });

    /* ========================= Report ========================= */

    Route::group(['middleware' => 'permissions:report'], function () {


        Route::get('/all-report', 'Admin\Report\GeneralReportController@allReport')->name('admin.all.report');

        /* ----------------- general report ----------------- */

        Route::get('/report/general/customer', 'Admin\Report\GeneralReportController@customerReport')->name('admin.report.generel.customer');
        Route::get('/report/general/customer-ajax', 'Admin\Report\GeneralReportController@customerList')->name('admin.report.generel.customer.ajax');
        Route::get('/report/general/product', 'Admin\Report\GeneralReportController@productReport')->name('admin.report.generel.product');
        Route::get('/report/general/merchant', 'Admin\Report\GeneralReportController@merchantReport')->name('admin.report.generel.merchant');
        Route::get('/report/general/subscription', 'Admin\Report\GeneralReportController@subscriptionReport')->name('admin.report.generel.subscription');
        Route::get('/report/general/coupon', 'Admin\Report\GeneralReportController@couponReport')->name('admin.report.generel.coupon');

        /* ----------------------------- customer report ---------------------------- */
        Route::get('/report/customer/full-report', 'Admin\Report\CustomerReportController@customerFullReport')->name('admin.report.customer.full_report');
        Route::get('/report/customer/full-report-ajax', 'Admin\Report\CustomerReportController@customerFullList')->name('admin.report.customer.full_report.ajaxresponse');

        /* --------------------------- product report ------------------------------- */
        Route::get('/report/product/list','Admin\Report\ProductReportController@productListReport')->name('admin.report.product.list');
        Route::get('/report/product/list-ajax','Admin\Report\ProductReportController@productList')->name('admin.report.product.list.ajaxresponse');
        Route::get('/report/product/specific','Admin\Report\ProductReportController@specifiProductReport')->name('admin.report.product.specific');
        Route::get('/report/product/merchant-wise','Admin\Report\ProductReportController@merchantWiseReport')->name('admin.report.product.merchant-wise');
        Route::get('/report/product/stock-wise','Admin\Report\ProductReportController@stocktWiseReport')->name('admin.report.product.stock-wise');
        Route::get('/report/product/best-sell','Admin\Report\ProductReportController@bestSellProduct')->name('admin.report.product.best-sell');

        /* ====================== ordet report ====================== */
        Route::get('/report/order/customize','Admin\Report\OrderReportController@customizedOrderRport')->name('admin.report.order.customized');
        Route::get('/report/order/order-ajax','Admin\Report\OrderReportController@orderListRport')->name('admin.report.order.ajaxresponse');
        Route::get('/report/order/merchant','Admin\Report\OrderReportController@merchantWiseReport')->name('admin.report.order.merchant');
        Route::get('/report/order/customer','Admin\Report\OrderReportController@customerWiseReport')->name('admin.report.order.customer');
        Route::get('/report/order/with-chart','Admin\Report\OrderReportController@orderReportCharts')->name('admin.report.order.with-chart');

        /* ======================== merchant ======================== */
        Route::get('/report/merchant/details','Admin\Report\MerchantReportController@merchantDetails')->name('admin.report.merchant.details');
        Route::get('/report/merchant/subscription-history','Admin\Report\MerchantReportController@subscriptionHistory')->name('admin.report.merchant.subscription_history');
        Route::get('/report/merchant/product-history','Admin\Report\MerchantReportController@productHistory')->name('admin.report.merchant.product_history');
        Route::get('/report/merchant/order-report','Admin\Report\MerchantReportController@orderReport')->name('admin.report.merchant.order_report');
    });

    Route::get('/general-settings/affilate', 'Admin\GeneralSettingController@affilate')->name('admin-gs-affilate');


 

    //------------ ADMIN CATEGORY SECTION ------------

    Route::group(['middleware' => 'permissions:categories'], function () {

        Route::get('/category/datatables', 'Admin\CategoryController@datatables')->name('admin.category.datatables'); //JSON REQUEST
        Route::get('/category', 'Admin\CategoryController@index')->name('admin.category.index');
        Route::get('/category/create', 'Admin\CategoryController@create')->name('admin.category.create')->middleware('permissions:categories|add');
        Route::post('/category/create', 'Admin\CategoryController@store')->name('admin.category.store')->middleware('permissions:categories|add');
        Route::get('/category/edit/{id}', 'Admin\CategoryController@edit')->name('admin.category.edit')->middleware('permissions:categories|edit');
        Route::post('/category/edit/{id}', 'Admin\CategoryController@update')->name('admin.category.update')->middleware('permissions:categories|edit');
        Route::get('/category/delete/{id}', 'Admin\CategoryController@destroy')->name('admin.category.delete')->middleware('permissions:categories|delete');
        Route::get('/category/status/{id1}/{id2}', 'Admin\CategoryController@status')->name('admin.category.status')->middleware('permissions:categories|edit');


        Route::get('/attribute/datatables', 'Admin\AttributeController@datatables')->name('admin.attr.datatables'); //JSON REQUEST
        Route::get('/attribute', 'Admin\AttributeController@index')->name('admin.attr.index');
        Route::get('/attribute/{catid}/attrCreateForCategory', 'Admin\AttributeController@attrCreateForCategory')->name('admin.attr.create.category');
        Route::get('/attribute/{subcatid}/attrCreateForSubcategory', 'Admin\AttributeController@attrCreateForSubcategory')->name('admin.attr.create.sub.category');
        Route::get('/attribute/{childcatid}/attrCreateForChildcategory', 'Admin\AttributeController@attrCreateForChildcategory')->name('admin.attr.create.child.category');
        Route::post('/attribute/store', 'Admin\AttributeController@store')->name('admin.attr.store');
        Route::get('/attribute/{id}/manage', 'Admin\AttributeController@manage')->name('admin.attr.manage');
        Route::get('/attribute/{attrid}/edit', 'Admin\AttributeController@edit')->name('admin.attr.edit');
        Route::post('/attribute/edit/{id}', 'Admin\AttributeController@update')->name('admin.attr.update');
        Route::get('/attribute/{id}/options', 'Admin\AttributeController@options')->name('admin.attr.options');
        Route::get('/attribute/delete/{id}', 'Admin\AttributeController@destroy')->name('admin.attr.delete');

        Route::get('/general-settings/multiple/shipping/{status}', 'Admin\GeneralSettingController@mship')->name('admin-gs-mship');
        Route::get('/general-settings/multiple/packaging/{status}', 'Admin\GeneralSettingController@mpackage')->name('admin-gs-mpackage');
        Route::get('/general-settings/maintain/{status}', 'Admin\GeneralSettingController@ismaintain')->name('admin-gs-maintain');

        //  Affilte Section
        Route::get('/general-settings/affilate/{status}', 'Admin\GeneralSettingController@isaffilate')->name('admin-gs-isaffilate');


        // SUBCATEGORY SECTION ------------

        Route::get('/subcategory/datatables', 'Admin\SubCategoryController@datatables')->name('admin-subcat-datatables'); //JSON REQUEST
        Route::get('/subcategory', 'Admin\SubCategoryController@index')->name('admin-subcat-index');
        Route::get('/subcategory/create', 'Admin\SubCategoryController@create')->name('admin-subcat-create')->middleware('permissions:categories|add');
        Route::post('/subcategory/create', 'Admin\SubCategoryController@store')->name('admin-subcat-store')->middleware('permissions:categories|add');
        Route::get('/subcategory/edit/{id}', 'Admin\SubCategoryController@edit')->name('admin-subcat-edit')->middleware('permissions:categories|edit');
        Route::post('/subcategory/edit/{id}', 'Admin\SubCategoryController@update')->name('admin-subcat-update')->middleware('permissions:categories|edit');
        Route::get('/subcategory/delete/{id}', 'Admin\SubCategoryController@destroy')->name('admin-subcat-delete')->middleware('permissions:categories|delete');
        Route::get('/subcategory/restore/{id}', 'Admin\SubCategoryController@restore')->name('admin.subcat.restore')->middleware('permissions:recover_delete');
        Route::get('/subcategory/status/{id1}/{id2}', 'Admin\SubCategoryController@status')->name('admin-subcat-status')->middleware('permissions:categories|edit');
        Route::get('/load/subcategories/{id}/', 'Admin\SubCategoryController@load')->name('admin-subcat-load'); //JSON REQUEST
        Route::get('/subcategories/get-cat-subcat/{id?}', 'Admin\SubCategoryController@getSubCatList')->name('admin-get-cat-subcat'); //JSON REQUEST

        // SUBCATEGORY SECTION ENDS------------

        // CHILDCATEGORY SECTION ------------

        Route::get('/childcategory/datatables', 'Admin\ChildCategoryController@datatables')->name('admin-childcat-datatables'); //JSON REQUEST
        Route::get('/childcategory', 'Admin\ChildCategoryController@index')->name('admin-childcat-index');
        Route::get('/childcategory/create', 'Admin\ChildCategoryController@create')->name('admin-childcat-create')->middleware('permissions:categories|add');
        Route::post('/childcategory/create', 'Admin\ChildCategoryController@store')->name('admin-childcat-store')->middleware('permissions:categories|add');
        Route::get('/childcategory/edit/{id}', 'Admin\ChildCategoryController@edit')->name('admin-childcat-edit')->middleware('permissions:categories|edit');
        Route::post('/childcategory/edit/{id}', 'Admin\ChildCategoryController@update')->name('admin-childcat-update')->middleware('permissions:categories|edit');
        Route::get('/childcategory/delete/{id}', 'Admin\ChildCategoryController@destroy')->name('admin-childcat-delete')->middleware('permissions:categories|delete');
        Route::get('/childcategory/restore/{id}', 'Admin\ChildCategoryController@restore')->name('admin.childcat.restore')->middleware('permissions:recover_delete');
        Route::get('/childcategory/status/{id1}/{id2}', 'Admin\ChildCategoryController@status')->name('admin-childcat-status')->middleware('permissions:categories|edit');
        Route::get('/load/childcategories/{id}/', 'Admin\ChildCategoryController@load')->name('admin-childcat-load'); //JSON REQUEST

        // CHILDCATEGORY SECTION ENDS------------

        // slug
        Route::get('category/slug', 'Admin\CategoryController@slug')->name('category.slug');

    });

    //------------ ADMIN CATEGORY SECTION ENDS------------




  
    //------------ ADMIN GENERAL SETTINGS SECTION -------------

    Route::group(['middleware' => 'permissions:general_settings'], function () {

       
       
       

        //------------ ADMIN PACKAGE ------------
        Route::get('/package/datatables', 'Admin\PackageController@datatables')->name('admin-package-datatables');
        Route::get('/package', 'Admin\PackageController@index')->name('admin-package-index');
        Route::get('/package/create', 'Admin\PackageController@create')->name('admin-package-create')->middleware('permissions:general_settings|add');
        Route::post('/package/create', 'Admin\PackageController@store')->name('admin-package-store')->middleware('permissions:general_settings|add');
        Route::get('/package/edit/{id}', 'Admin\PackageController@edit')->name('admin-package-edit')->middleware('permissions:general_settings|edit');
        Route::post('/package/edit/{id}', 'Admin\PackageController@update')->name('admin-package-update')->middleware('permissions:general_settings|edit');
        Route::get('/package/delete/{id}', 'Admin\PackageController@destroy')->name('admin-package-delete')->middleware('permissions:general_settings|delete');
        Route::get('/package/restore/{id}', 'Admin\PackageController@restore')->name('admin-package-restore')->middleware('permissions:recover_delete');

        //------------ ADMIN PACKAGE ENDS------------


        //------------ ADMIN GENERAL SETTINGS JSON SECTION ------------

        // General Setting Section
        Route::get('/general-settings/popup/{status}', 'Admin\GeneralSettingController@ispopup')->name('admin-gs-ispopup');

        Route::get('/general-settings/multiple/shipping/{status}', 'Admin\GeneralSettingController@mship')->name('admin-gs-mship');
        Route::get('/general-settings/multiple/packaging/{status}', 'Admin\GeneralSettingController@mpackage')->name('admin-gs-mpackage');
        Route::get('/general-settings/maintain/{status}', 'Admin\GeneralSettingController@ismaintain')->name('admin-gs-maintain');
        //  Affilte Section

        Route::get('/general-settings/affilate/{status}', 'Admin\GeneralSettingController@isaffilate')->name('admin-gs-isaffilate');


        //------------ ADMIN GENERAL SETTINGS JSON SECTION ENDS------------

        //------------ ADMIN GENERAL SETTINGS JSON SECTION ----------------
        Route::post('/general-settings/update/all', 'Admin\GeneralSettingController@generalupdate')->name('admin-gs-update')->middleware('permissions:general_settings|edit');
        Route::post('/general-settings/update/payment', 'Admin\GeneralSettingController@generalupdatepayment')->name('admin-gs-update-payment')->middleware('permissions:general_settings|edit');
        //------------ ADMIN GENERAL SETTINGS JSON SECTION ENDS------------



        // REPORT SECTION ENDS ------------

        // COMMENT CHECK
        Route::get('/general-settings/comment/{status}', 'Admin\GeneralSettingController@comment')->name('admin-gs-iscomment');
        // COMMENT CHECK ENDS

    });

    //------------ ADMIN GENERAL SETTINGS SECTION ENDS ------------


    // global vat and tax
    Route::group(['middleware' => 'permissions:global_vat_tax'], function () {
        Route::get('/globla-vat-tax/datatables/', 'Admin\GlobalVatTaxController@datatables')->name('admin.globla.vat.tax.datatable'); //JSON REQUEST
        Route::get('/global-vat-tax', 'Admin\GlobalVatTaxController@index')->name('admin.global.vat.tax.index');
        Route::get('/global-vat-tax/create', 'Admin\GlobalVatTaxController@create')->name('admin.global.vat.tax.create');
        Route::get('/global-vat-tax/edit/{id}', 'Admin\GlobalVatTaxController@edit')->name('admin.global.vat.tax.edit');
        Route::post('/global-vat-tax/edit/{id}', 'Admin\GlobalVatTaxController@update')->name('admin.global.vat.tax.update');
        Route::post('/global-vat-tax/store', 'Admin\GlobalVatTaxController@store')->name('admin.global.vat.tax.store');
    });
    // global vat and tax

  


    //------------ ADMIN STAFF SECTION ------------

    Route::group(['middleware' => 'permissions:manage_staffs'], function () {

        Route::get('/staff/datatables', 'Admin\StaffController@datatables')->name('admin-staff-datatables');
        Route::get('/staff', 'Admin\StaffController@index')->name('admin-staff-index');
        Route::get('/staff/create', 'Admin\StaffController@create')->name('admin-staff-create')->middleware('permissions:manage_staffs|add');
        Route::post('/staff/create', 'Admin\StaffController@store')->name('admin-staff-store')->middleware('permissions:manage_staffs|add');
        Route::get('/staff/edit/{id}', 'Admin\StaffController@edit')->name('admin-staff-edit')->middleware('permissions:manage_staffs|edit');
        Route::post('/staff/update/{id}', 'Admin\StaffController@update')->name('admin-staff-update')->middleware('permissions:manage_staffs|edit');
        Route::get('/staff/show/{id}', 'Admin\StaffController@show')->name('admin-staff-show');
        Route::get('/staff/delete/{id}', 'Admin\StaffController@destroy')->name('admin-staff-delete')->middleware('permissions:manage_staffs|delete');
        Route::get('/staff/restore/{id}', 'Admin\StaffController@restore')->name('admin.staff.restore')->middleware('permissions:recover_delete');
        // reset password
        Route::post('/staff/reset-password/{id}', 'Admin\StaffController@resetPassword')->name('admin.staff.reset-password')->middleware('permissions:reset_password');
    });

    //------------ ADMIN STAFF SECTION ENDS------------

    // ------------ GLOBAL ----------------------

    // STATUS SECTION

    // FEATURE SECTION ENDS

    // GALLERY SECTION ------------

    Route::get('/gallery/show', 'Admin\GalleryController@show')->name('admin-gallery-show');
    Route::post('/gallery/store', 'Admin\GalleryController@store')->name('admin-gallery-store')->middleware('permissions:subscribers|add');
    Route::get('/gallery/delete', 'Admin\GalleryController@destroy')->name('admin-gallery-delete')->middleware('permissions:subscribers|delete');

    // GALLERY SECTION ENDS------------

    // ------------ GLOBAL ENDS ----------------------

    Route::group(['middleware' => 'permissions:super'], function () {

        Route::get('/cache/clear', function () {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            return redirect()->route('admin.dashboard')->with('cache', 'System Cache Has Been Removed.');
        })->name('admin-cache-clear');

        // Permission SECTION

        Route::get('/permission/datatables', 'Admin\PermissionController@datatables')->name('admin-permission-datatable');
        Route::get('/permission', 'Admin\PermissionController@index')->name('admin-permission-index');
        Route::post('/permission/create', 'Admin\PermissionController@store')->name('admin-permission-store');
        Route::get('/permission/edit/{id}', 'Admin\PermissionController@edit')->name('admin-permission-edit');
        Route::post('/permission/edit/{id}', 'Admin\PermissionController@update')->name('admin-permission-update');
        Route::get('/permission/delete/{id}', 'Admin\PermissionController@destroy')->name('admin-permission-delete');

        // ROLE SECTION
        Route::get('/role/datatables', 'Admin\RoleController@datatables')->name('admin-role-datatables');
        Route::get('/role', 'Admin\RoleController@index')->name('admin-role-index');
        Route::get('/role/create', 'Admin\RoleController@create')->name('admin-role-create');
        Route::post('/role/create', 'Admin\RoleController@store')->name('admin-role-store');
        Route::get('/role/edit/{id}', 'Admin\RoleController@edit')->name('admin-role-edit');
        Route::post('/role/edit/{id}', 'Admin\RoleController@update')->name('admin-role-update');
        Route::get('/role/delete/{id}', 'Admin\RoleController@destroy')->name('admin-role-delete');

        // Sync Table
        Route::get('/sync/table', function () {
            (new SyncTable)->handle();
            return ' <h3>All sync end.</h3> ';
        })->name('admin.sync-table');
    });
});

Route::get('/unsubscribe/{email}', 'Admin\NewsletterSubscribeControler@unsubscribe')->name('unsubscribe');
