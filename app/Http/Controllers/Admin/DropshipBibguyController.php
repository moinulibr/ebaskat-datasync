<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use Topsdk\Topapi\TopApiClient;
use App\Traits\BigbuyProductUpdate;
use App\Http\Controllers\Controller;
use App\Traits\BigbuyShippingDetails;


use Topsdk\Topapi\Ability338\Ability338;
use App\Jobs\BigbuyProductImportableByJob;
use App\Traits\BigbuyProductImportByQueue;
use Topsdk\Topapi\Ability338\Request\AliexpressDsRecommendFeedGetRequest;
use Topsdk\Topapi\Ability338\Request\AliexpressDsCommissionorderListbyindexRequest;

class DropshipBibguyController extends Controller
{
    use BigbuyProductImportByQueue ,BigbuyProductUpdate , BigbuyShippingDetails;

    protected $page;
    protected $per_page;

    protected $customerKey;
    protected $customerSecret;

    //for single product update by sku
    protected $productSkus;
    protected $updatedStatusWithMessage;
   

    /**
     * Display import product from bigbuy blade page
    */
    public function index()
    {  
        return view('admin.dropship.bigbuy.index');
    }

 

    /**
     * product import from bigbuy
     * page wise import product from bigbuy
     * 
     * @param Request $request
     */
    public function productImportFromBigbuy(Request $request)
    {
        $pathName       = public_path('temp/dropship_bigbuy/');
        file_put_contents($pathName.'/next_page.txt',0);
        file_put_contents($pathName.'/current_page.txt',0);
        
        ini_set('max_execution_time', 28800);
        $startPage  = $request->start_page ?? 1;
        $endPage    = $request->end_page ?? $startPage;
        $allPages = "";
        for ($startPage; $startPage <= $endPage; $startPage++) {
            $allPages .= $startPage;
            if($startPage < $endPage)
            {
                $allPages    .= " ";
            }
        }
        $pages = explode(' ',$allPages);

        foreach($pages as $pageNo)
        {
            $page = $pageNo;
            $per_page = 50;
            $curl = curl_init();
            $apiUrl = bigbuyApiUrl_hd(); // this from helpers folder dependency files
            $url = "{$apiUrl}/rest/catalog/products.json?pageSize={$per_page}&page={$page}";
           
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ".bigbuyApiKey_hd() // this from helpers folder dependency files
                ),
              ));
              
            $response = curl_exec($curl);
            curl_close($curl);
            $datas = json_decode($response);
            if(!is_object($datas) && !isset($datas->code))
            {   
                //set total row and inserted row  for progress bar //
                $pathName = public_path('temp/dropship_bigbuy/');
                file_put_contents($pathName.'/total_row.txt',0);
                file_put_contents($pathName.'/insert_row.txt',0);
                file_put_contents($pathName.'/remaining_insert_row.txt',0);
                //set total row and inserted row  for progress bar //

                //set total row and inserted row  for progress bar //
                file_put_contents($pathName.'/total_row.txt',count($datas) - 1);
                //set total row and inserted row  for progress bar //

                if(intval($pageNo) < intval($endPage))
                {
                    file_put_contents($pathName.'/next_page.txt',intval($pageNo)+1);
                }else{
                    file_put_contents($pathName.'/next_page.txt',0);
                }
                file_put_contents($pathName.'/current_page.txt',$pageNo);
                
                $chunkData = array_chunk($datas,10,true);
                foreach($chunkData as  $products)
                {
                    foreach($products as  $product)
                    {
                        $this->insetingProductData($product,true);
                    }
                }
            }
            
        }//end top foreach for page
        return response()->json([
            'status' => true,
        ]);
    }



    /*
    |---------------------------------------------------------------------------
    | get processing percentage and show progress bar
    |---------------------------------------------------------------------------
    */
        //showing progress bar
        public function bigbuyProductImportProgressBar(Request $request)
        {
            $pathName = public_path('temp/dropship_bigbuy/');
            $totalRow       = file_get_contents($pathName.'/total_row.txt');
            $insertedRow    = file_get_contents($pathName.'/insert_row.txt');
            $remainingRow   = file_get_contents($pathName.'/remaining_insert_row.txt');
            $currentPage    = file_get_contents($pathName.'/current_page.txt');
            $nextPage       = file_get_contents($pathName.'/next_page.txt');
            $message = 'Bigbuy Product Imported Successfully.<a href="'.route('admin.product.index').'" style="margin:0px 10px;">View Product Lists.</a>';
            if(intval($totalRow) > 0)
            {
                return response()->json([
                    "status"                => true,
                    "totalRow"              => intval($totalRow),
                    "insertedRow"           => intval($insertedRow),
                    "remainingInsertRow"    => intval($remainingRow),
                    "currentPage"           => intval($currentPage),
                    "nextPage"              => intval($nextPage),
                    "message"               => $message,
                ]);
            }
            return response()->json([
                "status"                => true,
                "totalRow"              => 0,
                "insertedRow"           => 0,
                "remainingInsertRow"    => 0,
                "message"               => $message,
            ]);
        } 
        
        //update page no, and others
        public function updateInsertedValueAfterCompletedProgressBar(Request $request)
        {
            //set total row and inserted row  for progress bar //
            $pathName = public_path('temp/dropship_bigbuy/');
            file_put_contents($pathName.'/total_row.txt',0);
            file_put_contents($pathName.'/insert_row.txt',0);
            file_put_contents($pathName.'/remaining_insert_row.txt',0);
            //set total row and inserted row  for progress bar //
            $message = 'Bigbuy Product Imported Successfully.<a href="'.route('admin.product.index').'" style="margin:0px 10px;">View Product Lists.</a>';
            return response()->json([
                "status"                => true,
                "message"               => $message,
            ]);
        }
    /* 
    |---------------------------------------------------------------------------
    | The End get processing percentage and show progress bar
    |---------------------------------------------------------------------------
    */

 




    
    /* 
    |---------------------------------------------------------------------------
    | bigbuy product update by queue   
    |---------------------------------------------------------------------------
    */
        /**
         * bigbuy product update by queue
         * update imported product by queue function
         * all product update from our database by queue
         */
        public function updateImportedProductByQueue($jobNo = 1)
        {
            echo "processing update by queue";
            ini_set('max_execution_time', 28800);
            $this->updateProductProcessByQueue($jobNo);
            return redirect()->route('adminDropshippingBigbuyIndex');
        }
    /* 
    |---------------------------------------------------------------------------
    | bigbuy product update by queue   
    |---------------------------------------------------------------------------
    */



    /**
     * single product update by sku
     * show/display single product update page :blade file  (sku wise update)
     * update imported single product
     */
    public function updateImportedSingleProduct()
    {
        return view('admin.dropship.bigbuy.update-product');
    }
    //sku wise product update

    /**
     * update imported single product by sku
     * 
     * @param Request $request
     */
    public function updateImportedSingleProductBySku(Request $request)
    {
        if(isset($request->sku) && count($request->sku) > 0)
        {
            $pathName = public_path('temp/dropship_bigbuy/');
            //set total row and inserted row  for progress bar //
            file_put_contents($pathName.'/single_total_row_for_update.txt',0);
            file_put_contents($pathName.'/single_total_row_for_update.txt',count($request->sku));
            //set total row and inserted row  for progress bar //
            file_put_contents($pathName.'/single_remaining_insert_row_for_update.txt',0);
            file_put_contents($pathName.'/single_insert_row_for_update.txt',0);
            file_put_contents($pathName.'/single_current_sku_for_update.txt',NULL);
            $this->productSkus = $request->sku;
            ini_set('max_execution_time', 28800);
            $this->updateSingleProduct();

            $message = 'Bigbuy Products Updated Successfully.<a href="'.route('admin.product.index').'">View Product Lists.</a>';
            return response()->json($message);
        }
    }

    public function getUpdatingRowWhenUpdatingSingleProductBySkuForProgressBar(Request $request)
    {
        $pathName       = public_path('temp/dropship_bigbuy/');
        $totalRow       = file_get_contents($pathName.'/single_total_row_for_update.txt');
        $insertedRow    = file_get_contents($pathName.'/single_insert_row_for_update.txt');
        $remainingRow   = file_get_contents($pathName.'/single_remaining_insert_row_for_update.txt');
        $currentSku     = file_get_contents($pathName.'/single_current_sku_for_update.txt');
        $message = 'Bigbuy Product Updated Successfully.<a href="'.route('admin.product.index').'" style="margin:0px 10px;">View Product Lists.</a>';
        if(intval($totalRow) > 0)
        {
            return response()->json([
                "status"                => true,
                "totalRow"              => intval($totalRow),
                "insertedRow"           => intval($insertedRow),
                "remainingInsertRow"    => intval($remainingRow),
                "currentSku"           => $currentSku,
                "message"               => $message,
            ]);
        }
        return response()->json([
            "status"                => true,
            "totalRow"              => 0,
            "insertedRow"           => 0,
            "remainingInsertRow"    => 0,
            "currentSku"            => $currentSku,
            "message"               => $message,
        ]);
    }

    public function updatedValueAfterCompletingProgressBarWhenSingleProductUpdatedBySkuWithProgressBar(Request $request)
    {
        //set total row and inserted row  for progress bar //
        $pathName = public_path('temp/dropship_bigbuy/');
        file_put_contents($pathName.'/single_total_row_for_update.txt',0);
        file_put_contents($pathName.'/single_insert_row_for_update.txt',0);
        file_put_contents($pathName.'/single_remaining_insert_row_for_update.txt',0);
        $message = 'Bigbuy Product Updated Successfully.<a href="'.route('admin.product.index').'" style="margin:0px 10px;">View Product Lists.</a>';
        return response()->json([
            "status"                => true,
            "message"               => $message,
        ]);
    }
    //---------------------------------------------------------------------------
    //sku wise product update



    //display single product import by product sku 
    public function displaySingleProductImportByProductSku(Request $request)
    {
        return view('admin.dropship.bigbuy.single_product_import_by_sku');
    } 
      
    //single product import by product sku (importing)
    public function importingSingleProductByProductSku(Request $request)
    {
        if($request->sku)
        {
            ini_set('max_execution_time', 28800);
           $response =  $this->productResponseByProductIdThroughSku($request->sku);
            return response()->json([
                "status" => $response['status'],
                "message" => $response['message']
            ]);
        }
        else{
            return response()->json([
                "status" => false,
                "message" => "SKU not match"
            ]);
        }
    }



    //bigbuy product import from primary database
    public function bigbuyProductImportFromPrimaryDB()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://admin.ebaskat.com/api/v1/bigbuy/importable/products/from/primary/db',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $datas = json_decode($response,true);
       
        $chunks = array_chunk(json_decode($datas['data'],true),1000);
        foreach($chunks as $chunkData)
        {
            BigbuyProductImportableByJob::dispatch($chunkData)->onQueue('dsync-big-prodt-import-part-one-job');
        }  

        echo "<pre>";
        print_r($datas['data']);
        echo "</pre>";
        return 1;
    }
    
}