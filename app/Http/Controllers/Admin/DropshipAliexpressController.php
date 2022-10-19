<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Models\Brand;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Traits\FileStorage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Childcategory;
use App\Models\ProductVariant;
use App\Traits\AliexpressOrder;
use App\Models\VendorInformation;
use App\Http\Controllers\Controller;
use App\Traits\AliexpressProductUpdate;
use App\Traits\AliexpressProductImportByQueue;

class DropshipAliexpressController extends Controller
{
    use FileStorage;
    use AliexpressOrder , AliexpressProductUpdate,AliexpressProductImportByQueue;


    protected $page;
    protected $per_page;

    protected $customerKey;
    protected $customerSecret;

    

    
    public function index()
    {    
        return view('admin.dropship.aliexpress.index');
    }


    /**
     * product import from aliexpress
     * page wise import
     * 
     * @param Request $request
     */
    public function productImportFromAliexpress(Request $request)
    {
        $pathName       = public_path('temp/dropship_aliexpress/');
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
            $this->page = $pageNo;
            $this->per_page = 20;
            $curl = curl_init();
            $this->customerKey      = restapiCustomerKeyForWoocommerce();
            $this->customerSecret   = restapiCustomerSecretForWoocommerce(); //status=draft&
            $url = "https://obaskat.com/wp-json/wc/v3/products?page={$this->page}&per_page={$this->per_page}&consumer_key={$this->customerKey}&consumer_secret={$this->customerSecret}";
            curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($curl);

            curl_close($curl);
            $datas = json_decode($response);

            if(isset($datas) && is_array($datas))
            {
                //set total row and inserted row  for progress bar //
                $pathName = public_path('temp/dropship_aliexpress/');
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
                        $this->insetProductData($product,true);
                    }
                }
                //$this->insetProductData($datas,true);
            }else{
                file_put_contents($pathName.'/total_row.txt',1);
                file_put_contents($pathName.'/insert_row.txt',1);
                file_put_contents($pathName.'/remaining_insert_row.txt',0);

                if(intval($pageNo) < intval($endPage))
                {
                    file_put_contents($pathName.'/next_page.txt',intval($pageNo)+1);
                }else{
                    file_put_contents($pathName.'/next_page.txt',0);
                }
                file_put_contents($pathName.'/current_page.txt',$pageNo);
            }
        }//end top foreach for page
        return response()->json([
            'status' => true,
        ]);
        $msg = 'Products Added Successfully.<a href="'.route('admin.product.index').'">View Product Lists.</a>';
        return response()->json($msg);
    }


    /*
    |------------------------------------------------------------------------------
    | Single Product import by product id
    |------------------------------------------------------------------------------
    */
        public function displaySingleProductImportByProductId(Request $request)
        {
            return view('admin.dropship.aliexpress.single_product_import_by_id');
        } 
        
        //single product import by product id (importing)
        public function importingSingleProductByProductId(Request $request)
        {
            $productId = $request->id;

            $customerKey = restapiCustomerKeyForWoocommerce();
            $customerSecret = restapiCustomerSecretForWoocommerce();
            $singleProductResponse = curl_init();
            $singleProductResponseUrl = "https://obaskat.com/wp-json/wc/v3/products/{$productId}?consumer_key={$customerKey}&consumer_secret={$customerSecret}";
            curl_setopt_array($singleProductResponse, array(
                CURLOPT_URL => $singleProductResponseUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($singleProductResponse);
            curl_close($singleProductResponse);
            $product = json_decode($response);
            if(isset($product->code) && !empty($product->code)) 
            {
                return response()->json([
                    "status" => false,
                    "message" => "Product ID not match"
                ]);
            }
            else if(isset($product->id) && !empty($product->id)){
                $this->insetProductData($product,false);
                    return response()->json([
                        "status" => true,
                        "message" => "Imported Successfully"
                ]);
            }else{
                //"not found";
                return response()->json([
                    "status" => false,
                    "message" => "Product ID not match"
                ]);
            }
        }
    /*
    |------------------------------------------------------------------------------
    | Single Product import by product id
    |------------------------------------------------------------------------------
    */



    /*
    |------------------------------------------------------------------------------
    | Page wise Product import by queue
    |------------------------------------------------------------------------------
    */
       public function importProductFromAliexpressByQueue()
       {
            echo "processing import by queue";
            $this->aliexpressProductImportByQueue();
            return redirect()->route('admin_dropship_aliexpress_index');
       }
    /*
    |------------------------------------------------------------------------------
    | Page wise Product import by queue
    |------------------------------------------------------------------------------
    */



    /*
    |---------------------------------------------------------------------------
    | get processing percentage and show progress bar
    |---------------------------------------------------------------------------
    */
        public function aliexpressProductImportProgressBar(Request $request)
        {
            $pathName = public_path('temp/dropship_aliexpress/');
            $totalRow       = file_get_contents($pathName.'/total_row.txt');
            $insertedRow    = file_get_contents($pathName.'/insert_row.txt');
            $remainingRow   = file_get_contents($pathName.'/remaining_insert_row.txt');
            $currentPage    = file_get_contents($pathName.'/current_page.txt');
            $nextPage       = file_get_contents($pathName.'/next_page.txt');
            $message = 'Aliexpress Product Imported Successfully.<a href="'.route('admin.product.index').'" style="margin:0px 10px;">View Product Lists.</a>';
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
        
        public function updateInsertedValueAfterCompletedProgressBar(Request $request)
        {
            //set total row and inserted row  for progress bar //
            $pathName = public_path('temp/dropship_aliexpress/');
            file_put_contents($pathName.'/total_row.txt',0);
            file_put_contents($pathName.'/insert_row.txt',0);
            file_put_contents($pathName.'/remaining_insert_row.txt',0);
            //set total row and inserted row  for progress bar //
            $message = 'Aliexpress Product Imported Successfully.<a href="'.route('admin.product.index').'" style="margin:0px 10px;">View Product Lists.</a>';
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
    /** Processing/uploading bulk product */






    /*
    |-----------------------------------------------------------------
    | aliexpress product update by queue
    |-----------------------------------------------------------------
    */
        public function updateImportedProductByQueue($lastPageNo = NULL)
        {
            echo "processing update by queue";
            $this->aliexpressAllProductUpdateByQueue($lastPageNo);
            return redirect()->route('admin_dropship_aliexpress_index');
        }
    /*
    |-----------------------------------------------------------------
    | aliexpress product update by queue
    |-----------------------------------------------------------------
    */


    /**
     * show product update blade page
     */
    public function updateImportedProductByPage()
    {
        return view('admin.dropship.aliexpress.update-product');
    }


    /*
    |------------------------------------------------------------------------------
    | product update related section
    |------------------------------------------------------------------------------
    */
        /**
         * update product : page wise update product
         * from aliexpress product update page
         * 
         * @param Request $request
         */
        public function updateImportedProduct(Request $request)
        {
            $pathName       = public_path('temp/dropship_aliexpress/');
            file_put_contents($pathName.'/next_page_for_update.txt',0);
            file_put_contents($pathName.'/current_page_for_update.txt',0);

            $this->pageStart = $request->start_page ?? 1;
            $this->pageEnd = $request->end_page ?? $this->pageStart;
            $this->updateProduct();
            $msg = 'Products Updated Successfully.<a href="'.route('admin.product.index').'">View Product Lists.</a>';
            return response()->json($msg);
        }


        public function getUpdatingRowForProductUpdateProgressBar(Request $request)
        {
            $pathName       = public_path('temp/dropship_aliexpress/');
            $totalRow       = file_get_contents($pathName.'/total_row_for_update.txt');
            $insertedRow    = file_get_contents($pathName.'/insert_row_for_update.txt');
            $remainingRow   = file_get_contents($pathName.'/remaining_insert_row_for_update.txt');
            $currentPage    = file_get_contents($pathName.'/current_page_for_update.txt');
            $nextPage       = file_get_contents($pathName.'/next_page_for_update.txt');

            $message = 'Aliexpress Product Updated Successfully.<a href="'.route('admin.product.index').'" style="margin:0px 10px;">View Product Lists.</a>';
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

        public function updatedValueAfterCompletingProgressBarForProductUpdateProgressBar(Request $request)
        {
            //set total row and inserted row  for progress bar //
            $pathName = public_path('temp/dropship_aliexpress/');
            file_put_contents($pathName.'/total_row_for_update.txt',0);
            file_put_contents($pathName.'/insert_row_for_update.txt',0);
            file_put_contents($pathName.'/remaining_insert_row_for_update.txt',0);
            $message = 'Aliexpress Product Updated Successfully.<a href="'.route('admin.product.index').'" style="margin:0px 10px;">View Product Lists.</a>';
            return response()->json([
                "status"                => true,
                "message"               => $message,
            ]);
        }



        //display single product update by product sku 
        public function displaySingleProductUpdateByProductSku(Request $request)
        {
            return view('admin.dropship.aliexpress.single_product_update_by_sku');
        } 
        
        //single product update by product sku (updating)
        public function updatingSingleProductByProductSku(Request $request)
        {
            $sku = $request->sku;
            $daProduct      = Product::where('sku','like','%'.$sku)
                            ->where('product_from','Aliexpress')
                            ->select('ds_product_id','id','sku')
                            ->first();
            $ds_product_id  = $daProduct ? $daProduct->ds_product_id : NULL;
            if($ds_product_id)
            {
            $this->singleProductUpdateByDaProductId($ds_product_id);
            return response()->json([
                    "status" => true,
                    "message" => "Updated Successfully"
            ]);
            }else{
                return response()->json([
                    "status" => false,
                    "message" => "SKU not match"
            ]);
            }
        }
    /*
    |------------------------------------------------------------------------------
    | product update related section
    |------------------------------------------------------------------------------
    */
    

}