<?php

namespace App\Traits;

use App\Models\Product;
use App\Traits\FileStorage;
use App\Models\ProductVariant;
use App\Models\SyncingCalculation;
use App\Traits\BigbuyShippingDetails;
use App\Jobs\BigbuyProductUpdateByJob;


trait BigbuyProductUpdate
{
    use FileStorage, BigbuyShippingDetails;

    public $pageStart;
    public $pageEnd;

    public $currentPage;
    public $perPage;

    protected $customerKey;
    protected $customerSecret;

    public $productId;

    
   
    /*
    |------------------------------------------------------------------------------
    |------------------------------------------------------------------------------
    | product update by queue
    |----------------------------------------------------
    */
        /**
         * update product process by queue
         * process queue in this method
         * 10 products process at a time (per jobs)  
         */
        public function updateProductProcessByQueue($jobNo)
        {
            //skipable from database: table name : syncing _calculation
            $syncCal = SyncingCalculation::where('product_from','Bigbuy')->first();
            $callableNo = $syncCal ? $syncCal->callable_no : 1;
            $skipable = $syncCal ? $syncCal->skipable : 0;
            $totalData = Product::where('product_from',"Bigbuy")->select('ds_product_id')->count();
            
            //for four times call
            $eachTimeCallableData = ceil($totalData / $callableNo);

            if($syncCal)
            {
                $skipableNewData = $syncCal->skipable + $eachTimeCallableData;
                if($skipableNewData >= $totalData)
                {
                    $syncCal->skipable = 0 ;
                }else{
                    $syncCal->skipable = $skipableNewData;
                }
                $syncCal->save();
            }

            $daProductIds =  Product::where('product_from',"Bigbuy")->select('ds_product_id')
                            ->whereNull('deleted_at')
                            ->skip($skipable)
                            ->take($eachTimeCallableData)
                            ->pluck('ds_product_id')
                            ->toArray();
            $chunks = array_chunk($daProductIds,1000);
            foreach($chunks as $chunkData)
            {
                BigbuyProductUpdateByJob::dispatch($chunkData,$jobNo)->onQueue('dsync-big-prodt-updt-part-one-job'.$jobNo);
            }                                                    
            return true;	
        }
    /*
    |----------------------------------------------------
    | product update by queue
    |------------------------------------------------------------------------------
    |
    |
    */



    /**
     * update product by bigbuy product id
     * uses this method : update product by queue and single update by sku
     */
    public function updateProductByProductId()
    {
        ini_set('max_execution_time', 28800);
        //sleep(5);
        $curl = curl_init();
        $apiUrl = bigbuyApiUrl_hd();
        $url = "{$apiUrl}/rest/catalog/product/{$this->productId}.json";
    
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
                "Authorization: Bearer ".bigbuyApiKey_hd()
            ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $product = json_decode($response);
        if(isset($product->code) && !empty($product->code)) 
        {
            sleep(5);
            $this->changeProductStatusWhenProductNotFound($this->productId);
        }
        else if(isset($product->id) && !empty($product->id)){
            //sleep(5);
            $this->updateProductDetailsByProductId($product);
        }else{
            sleep(5);
            $this->changeProductStatusWhenProductNotFound($this->productId);
            //"not found";
        }
        return true;
    }

    /**
     * product update details function
     * updating product in this method
     * @param [product_from] $product
     */
    public function updateProductDetailsByProductId($product)
    {
        if ($input = Product::where('product_from','Bigbuy')->where('ds_product_id',$product->id)->first())
        {
            sleep(5);//product categories  5 sec sleep(5);
            $informationName   = NULL;
            $informationDetail = NULL; 
            $information = $this->productInformation($product->id);
            if(is_object($information))
            {
                $informationName   =  $information->name ?? NULL;
                $informationDetail =  $information->description ?? NULL;
            }
            /*
            |---------------------------------------------------------
            |category, sub-category, child category
            |never update in this method without Jahid's bhai permission
            |---------------------------------------------------------
            */


            //product stock api
            //api max request - period - 5sec  sleep(5);
            //sleep(5);
            $productStocks  = $this->productStock($product->id);
            if(!$productStocks || empty($productStocks) || $productStocks == NULL)
            {
                $stockQty = 0 ;
            }
            elseif(is_int(intval($productStocks)) || is_double(doubleval($productStocks)) )
            {
                $stockQty = $productStocks;
            }else{
                $stockQty = 0 ;
            }

            /*
            |---------------------------------------------
            | updateable
            |--------------------------------------------
            */
            $updateable = 0;
            $updatedFields = [];
        
            $input->isDirty(); // true

            if($input->name != $informationName && $informationName != NULL)
            {
                $updateable = 1;
                array_push($updatedFields,['name'=>$input->name]);
                $input->name        = $informationName;
            }
            if($input->description != $informationDetail && $informationDetail != NULL)
            {
                $updateable = 1;
                array_push($updatedFields,['description' => true]);
                $input->description   = $informationDetail;
            }
            if($input->sku != $product->sku)
            {
                $updateable = 1;
                array_push($updatedFields,['sku'=>$input->sku]);
                $input->sku             = $product->sku;
            } 

            if($input->current_price != floatval($product->retailPrice))
            {
                $updateable = 1;
                array_push($updatedFields,['current_price'=>$input->current_price]);
                $input->current_price   = floatval($product->retailPrice);
            } 
            if($input->regular_price   != floatval($product->retailPrice))
            {
                $updateable = 1;
                array_push($updatedFields,['regular_price'=>$input->regular_price]);
                $input->regular_price   = floatval($product->retailPrice);
            } 
            if($input->sale_price != floatval($product->wholesalePrice))
            {
                $updateable = 1;
                array_push($updatedFields,['sale_price'=>$input->sale_price]);
                $input->sale_price      = floatval($product->wholesalePrice);
            } 
            
            if( $input->stock_quantity  != $stockQty)
            {
                $updateable             = 1;
                array_push($updatedFields,['stock_quantity'=>$input->stock_quantity]);
                $input->stock_quantity  = $stockQty;
                $input->stock_status    = $stockQty > 0 ? 1 : 0;
            }
            if(strtolower($product->active) != 1)
            {
                $updateable             = 1;
                array_push($updatedFields,['status'=>$input->status]);
                $input->status          = 0;
                $input->stock_status    = 0;
                $input->stock_quantity  = 0;
            }


            // length, widht, height, weight for measure field
            $dimensions = [
                "weight" => $product->weight ?? "",
                "length" => $product->depth ?? "",
                "width" =>  $product->width ?? "",
                "height" => $product->height ?? "",
            ];
            $input->dimension = json_encode($dimensions);
            // length, widht, height, weight for measure field
                
           
            /*
            |---------------------------------------------
            | updateable
            |--------------------------------------------
            */
                if($updateable == 1)
                {
                    $input->updateable   = $input->updateable == 2 ? 2 : $updateable;
                    $input->updated_fields  = json_encode($updatedFields);
                }
            /*
            |---------------------------------------------
            | updateable
            |--------------------------------------------
            */
            // Save Data
            $input->save();

               
            $updateableFromVariant = 0;
            /*
            |------------------------------------------
            | product images
            |------------------------------------------
            */
                //sleep(5);//api max request - period - 5sec  sleep(5);
                //image
                sleep(5);
                $productImages = $this->productImages($product->id);
                $i = 1;
                $productPhoto = "";
                foreach($productImages as  $image)
                {
                    if($image['covers'] == 1)
                    {
                        $productPhoto = $image['photo'];
                        break;
                    }
                } 
                $oldProductImages = $input->images;
                $input->images = json_encode($productImages);
                if($input->isDirty('images'))
                {
                    $updateableFromVariant = 1;
                    array_push($updatedFields,['images'=>$oldProductImages]);
                    //$input->images =  json_encode($productImages);
                }else{
                    $input->images =  $oldProductImages;
                }
            /*
            |------------------------------------------
            | product
            |------------------------------------------
            */



            
            /*
            |------------------------------------------
            | when product variation found
            |------------------------------------------
            | 
            */  
                /*
                |------------------------------------------
                | take product variation price as main price
                | when product variation found
                |------------------------------------------
                | $product->wholesalePrice
                */
              
                //sleep(5);//api max request - period - 5sec  sleep(5);
                $productType        = 'normal';
                $productVariations  = $this->productVariation($product->id);
                if($productVariations && is_array($productVariations))
                {
                    foreach($productVariations as $variation)
                    {
                        $productVariationId     = $variation->id;
                        $productVariationSku    = $variation->sku;
                                               
                        $dimensions = [
                            "length" => $variation->depth ?? "",
                            "width" =>  $variation->width ?? "",
                            "height" => $variation->height ?? "",
                        ];
                        $variationDimensionJson = json_encode($dimensions);


                        sleep(5);//api max request - period - 5sec  sleep(5);
                        $variantStock =  $this->productVariationStock($productVariationId);

                        /*
                        |-------------------------------------------
                        | arrtibutes Detial (arrtibutesDetial)
                        |-------------------------------------------
                        */
                            $variationAttributes = [[]];
                            //sleep(5);//api max 1 request - period - 5sec  sleep(5);
                            $attributesDetial = $this->productVariaitonDetail($productVariationId);
                            $i = 1;
                            if($attributesDetial && is_array($attributesDetial->attributes))
                            {
                                foreach($attributesDetial->attributes as  $attribute)
                                {
                                    if($i == 1)
                                    {
                                        $arrtibuteId = $attribute->id;
                                    }
                                }
                                //sleep(5);//api max 1 request - period - 5sec  sleep(5);
                                $attributeSingle    = $this->productAttributeDetail($arrtibuteId);
                                $attributeName      =   $attributeSingle?$attributeSingle->name:NULL;
                                $attributeGroupId   =  $attributeSingle?$attributeSingle->attributeGroup:NULL;
                                
                                sleep(1);
                                $attributeGroup = $this->productAttributeGroupDetail($attributeGroupId);
                                $attributeGroupName =  $attributeGroup?$attributeGroup->name:NULL;

                                $variationAttributes = [
                                    [
                                        "name" => $attributeGroupName,
                                        "value" => $attributeName
                                    ]
                                ];
                            }//end if, attributesDetial
                            else{
                                $variationAttributes =  [["name"=>"size","value"=>"regular"]];
                            }
                        /*
                        |-------------------------------------------
                        | arrtibutes Detial (arrtibutesDetial)
                        |-------------------------------------------
                        */


                        // if variant is exist, then update variant information
                        if($productVariant = ProductVariant::where('ds_variation_id',$productVariationId)->first())
                        {
                                $productVariant->variation_sku      = $productVariationSku;
                            if($productVariant->current_price != floatval($variation->retailPrice))
                                {
                                    $updateableFromVariant = 1;
                                    array_push($updatedFields,['variant_id_'.$productVariationId =>["v_current_price" => $productVariant->current_price]]);
                                    $productVariant->current_price   = floatval($variation->retailPrice);
                                } 
                                if($productVariant->regular_price   != floatval($variation->retailPrice))
                                {
                                    $updateableFromVariant = 1;
                                    array_push($updatedFields,['variant_id_'.$productVariationId=>["v_regular_price" => $productVariant->regular_price]]);
                                    $productVariant->regular_price   = floatval($variation->retailPrice);
                                } 
                                if($productVariant->sale_price != floatval($variation->wholesalePrice))
                                {
                                    $updateableFromVariant           = 1;
                                    array_push($updatedFields,['variant_id_'.$productVariationId=>["v_sale_price" => $productVariant->sale_price]]);
                                    $productVariant->sale_price      = floatval($variation->wholesalePrice);
                                } 

                                if( $productVariant->stock_quantity  != $variantStock)
                                {
                                    $updateableFromVariant           = 1;
                                    array_push($updatedFields,['variant_id_'.$productVariationId=> ["v_stock_quantity" => $productVariant->stock_quantity]]);
                                    $productVariant->stock_quantity  = $variantStock;
                                    $productVariant->stock_status    = $variantStock > 0 ? 1 : 0;
                                }
                           
                                $variationStockStatus     = 0;
                                if(strtolower($product->active) == 1)
                                {
                                    $variationStockStatus = 1;
                                }else{
                                    $variationStockStatus = 0;
                                    $productVariant->stock_quantity  = 0;
                                    $productVariant->stock_status    = 0;
                                }

                                if( $productVariant->stock_status  != $variationStockStatus)
                                {
                                    $updateableFromVariant          = 1;
                                    array_push($updatedFields,['variant_id_'.$productVariationId=>["v_stock_status" => $productVariant->stock_status]]);
                                    $productVariant->stock_status   = $variationStockStatus;
                                }
                                
                                $oldVarAttribute = $productVariant->attributes;
                                $productVariant->attributes = json_encode($variationAttributes);
                                if($productVariant->isDirty('attributes'))
                                {
                                    $updateableFromVariant = 1;
                                    array_push($updatedFields,['variant_id_'.$productVariationId=>["v_attributes" => $oldVarAttribute]]);
                                    //$productVariant->attributes = json_encode($variationAttributes);
                                }else{
                                    $productVariant->attributes = $oldVarAttribute;
                                }

                                if( $productVariant->variation_photo  != $productPhoto)
                                {
                                    $updateableFromVariant  = 1;
                                    array_push($updatedFields,['variant_id_'.$productVariationId=> ["v_photo" => $productVariant->variation_photo]]);
                                    $productVariant->variation_photo  = $productPhoto;
                                }
                            $productVariant->dimension  = $variationDimensionJson;
                            $productVariant->save();
                        }
                        else{ // if variant is not exist, then insert new variant, 
                            //though product is same
                            $updateableFromVariant = 1;
                            array_push($updatedFields,['variant'=>["v_add_new_variant" =>1]]);
                            $newProductVariation = new ProductVariant();
                            $newProductVariation->product_id        = $input->id;
                            $newProductVariation->ds_variation_id   = $productVariationId;
                            $newProductVariation->variation_sku     = $productVariationSku;
                            $newProductVariation->current_price     = floatval($variation->retailPrice);
                            $newProductVariation->regular_price     = floatval($variation->retailPrice);
                            $newProductVariation->sale_price        = floatval($variation->wholesalePrice);
                            if(strtolower($product->active) == 1)
                            {
                                $newProductVariation->stock_quantity = $variantStock;
                            }else{
                                $newProductVariation->stock_quantity = 0;
                            }
                            $newProductVariation->attributes        = json_encode($variationAttributes);
                            $newProductVariation->dimension         = $variationDimensionJson;
                            $newProductVariation->variation_photo   = $productPhoto;
                            $newProductVariation->save();
                        }
                        $productType = 'variant';
                       
                    }//end foreach
                }//end when product variation found from bigbuy
                else{ //when product variation not found from bigbuy
                        $variantNotAvailableAttributes  = json_encode([["name"=>"size","value"=>"regular"]]);
                        if($input->product_type == 'normal')
                        {
                            $productVariant = ProductVariant::where('product_id',$input->id)->first();
                            $productVariant->product_id     = $input->id;
                            $productVariant->current_price  = $input->current_price;
                            $productVariant->regular_price  = $input->regular_price;;
                            $productVariant->sale_price     = $input->sale_price;
                            $productVariant->attributes     = $variantNotAvailableAttributes;
                            if(strtolower($product->active) == 1)
                            {
                                $productVariant->stock_quantity   = $input->stock_quantity;
                            }else{
                                $productVariant->stock_quantity   = 0;
                            }

                            if( $productVariant->variation_photo  != $productPhoto)
                            {
                                $updateableFromVariant  = 1;
                                array_push($updatedFields,['variant_id_'.$productVariant->id => ["v_photo" => $productVariant->variation_photo]]);
                                $productVariant->variation_photo  = $productPhoto;
                            }
                            $productVariant->save();
                        }else{
                            $updateableFromVariant = 1;
                            array_push($updatedFields,['variant'=>["v_add_new_variant" =>2]]);
                            $newProductVariant = new ProductVariant();
                            $newProductVariant->product_id     = $input->id;
                            $newProductVariant->current_price  = $input->current_price;
                            $newProductVariant->regular_price  = $input->regular_price;
                            $newProductVariant->sale_price     = $input->sale_price;
                            $newProductVariant->attributes     = $variantNotAvailableAttributes;
                            if(strtolower($product->active) == 1)
                            {
                                $newProductVariant->stock_quantity = $input->stock_quantity;
                            }else{
                                $newProductVariant->stock_quantity = 0;
                            }
                            $newProductVariant->variation_photo    = $productPhoto;
                            $newProductVariant->save();
                        }
                    $productType = 'normal'; 
                }
            
            /*
            |------------------------------------------
            | when product variation found
            |------------------------------------------
            | product variant table
            */
        

            if( $productPhoto != $input->photo && $productPhoto != "" )
            {
                $updateableFromVariant = 1;
                array_push($updatedFields,['photo'=>$input->photo]);
                $input->photo     = $productPhoto ?? $input->photo;
            }

            if( $productPhoto != $input->thumbnail && $productPhoto != "" )
            {
                $updateableFromVariant = 1;
                array_push($updatedFields,['thumbnail'=>$input->thumbnail]);
                $input->thumbnail   = $productPhoto ?? $input->thumbnail;
            }

            if( $productType != $input->product_type)
            {
                $updateableFromVariant = 1;
                array_push($updatedFields,['product_type'=>$input->product_type]);
                $input->product_type    = $productType;
            }
 

            if($updateableFromVariant == 1)
            {
                $input->updateable      = $input->updateable == 2 ? 2 : $updateableFromVariant ;
                $input->updated_fields  = json_encode($updatedFields);
            }
            $input->save();
            $input->isDirty(); // false

        }//end if. if product not exist
        return true;
    }



    /* 
    * product status change to 0 when product not found
    * update / check by bigbuy product id 
    */
    public function changeProductStatusWhenProductNotFound($productId)
    {
        if ($input = Product::select('ds_product_id','status','stock_quantity','updateable','updated_fields','product_from')
                ->where('product_from','Bigbuy')->where('ds_product_id',$productId)->first()
            )
        {
            $input->status = 2;
            $input->stock_quantity = 0;//$input->status;
            $input->updateable  = 1;
            $updatedFields = [];
            array_push($updatedFields,['response'=> 'product not found']);
            $input->updated_fields  = json_encode([]);
            $input->save();
            return true;
        }
        return true;
    }


    /*
    |------------------------------------------------------------------------------
    |------------------------------------------------------------------------------
    | single product update by sku
    |----------------------------------------------------
    */
        /**
         * update single product by sku
         *
         */
        public function updateSingleProduct()
        {
            //set total row and inserted row  for progress bar //
            $pathName = public_path('temp/dropship_bigbuy/');
            //set total row and inserted row  for progress bar //
            ini_set('max_execution_time', 28800);
            //$ids = Product::whereIn('sku',$this->productSkus)->select('id')->pluck('id')->toArray();
            $data = [];
            foreach($this->productSkus as $sku)
            {
                file_put_contents($pathName.'/single_current_sku_for_update.txt',$sku);

                $productUpdatedStatus = false;
                $productUpdatedResult = "SKU not match";
                $dataExist = Product::where('sku',$sku)->select('ds_product_id')->first();
                if($dataExist)
                {
                    $this->productId = $dataExist->ds_product_id;                
                    $this->updateProductByProductId();
                    $productUpdatedStatus = true;
                    $productUpdatedResult = "Product updated successful";
                }
                $data[] = [
                    "sku"       => $sku,
                    "status"    => $productUpdatedStatus,
                    'result'    => $productUpdatedResult
                ];

                $previousInsertedRow    = file_get_contents($pathName.'/single_insert_row_for_update.txt');
                $previousTotalRow       = file_get_contents($pathName.'/single_total_row_for_update.txt');

                $currentInsetingRow = intval($previousInsertedRow) + 1;
                $remainingTotalRow  = (intval($previousTotalRow) - intval($currentInsetingRow));
                file_put_contents($pathName.'/single_insert_row_for_update.txt',  $currentInsetingRow);
                file_put_contents($pathName.'/single_remaining_insert_row_for_update.txt',$remainingTotalRow);
                //for progress bar
            }
            return $data;
        }
    /*
    |----------------------------------------------------
    | single product update by sku
    |------------------------------------------------------------------------------
    |
    |
    */


  


    /*
    |-------------------------------------------------------------------------
    |-------------------------------------------------------------------------
    | all dependency api 
    |----------------------------------------------------
    |
    */
        //product details
        public function productInformation($productId)
        {
            $apiUrl = bigbuyApiUrl_hd();
            $curl = curl_init();
            $url = "{$apiUrl}/rest/catalog/productinformation/$productId.json?isoCode=en";
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
                    "Authorization: Bearer ".bigbuyApiKey_hd()
                    ),
                ));
            $response = curl_exec($curl);
            curl_close($curl);
            return $information =  json_decode($response);
            $products   = [];
            $products['name']   =  $information->name?? "No Name";
            $products['detail'] =  $information->description ?? "No Details";
            return $products;
        }


        //product Images
        public function productImages($productId)
        {
            $apiUrl = bigbuyApiUrl_hd();
            $curl = curl_init();
            $url = "{$apiUrl}/rest/catalog/productimages/$productId.json";
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
                    "Authorization: Bearer ".bigbuyApiKey_hd()
                    ),
                ));
            $response = curl_exec($curl);
            curl_close($curl);
            $productImages =  json_decode($response);
            $images = [];
            if($productImages)
            {
                foreach ($productImages->images as $index => $data) 
                {
                    $images[$index] = [
                        "id"            => $data->id,
                        "name"          => $data->name,
                        "photo"         => $data->url,
                        "covers"        => $data->isCover
                    ];  
                }
            }
            return $images;
        }

        
        //product category
        public function productCategory($categoryId)
        {
            $apiUrl = bigbuyApiUrl_hd();
            $curl = curl_init();
            $url = "{$apiUrl}/rest/catalog/category/$categoryId.json?isoCode=en";
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
                    "Authorization: Bearer ".bigbuyApiKey_hd()
                    ),
                ));
            $response = curl_exec($curl);
            curl_close($curl);
            $cat =  json_decode($response);
            $category   = [];
            $category['name']   =  $cat->name?? "No Name";
            $category['slug']   =  $cat->url ?? "No slug";
            return $category;
        }


        //product categories 
        public function productCategories($productId)
        {
            $apiUrl = bigbuyApiUrl_hd();
            $curl = curl_init();
            $url = "{$apiUrl}/rest/catalog/productcategories/$productId.json";
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
                    "Authorization: Bearer ".bigbuyApiKey_hd()
                    ),
                ));
            $response = curl_exec($curl);
            curl_close($curl);
            return $categories =  json_decode($response);
            $variations = [];
            foreach ($variations as $index => $data) 
            {
                //if(($index+1) <= 10){

                /*  $allAttributes  = [];
                    foreach($data->attributes as $inx => $attr)
                    {
                        $allAttributes[$inx] = [
                            "name"  => $attr->name,
                            "value" => $attr->option
                        ];
                    }
                    $variations[$index] = [
                        "id"            => $data->id,
                        'attributes'    => $allAttributes
                    ];  
                //} */
            }
            return $variations;
        }


        //product stock;
        public function productStock($productId)
        {
            $apiUrl = bigbuyApiUrl_hd();
            $curl = curl_init();
            $url = "{$apiUrl}/rest/catalog/productstock/$productId.json";
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
                    "Authorization: Bearer ".bigbuyApiKey_hd()
                    ),
                ));
            $response = curl_exec($curl);
            curl_close($curl);
            $productStocks = json_decode($response,true);
            if(is_array($productStocks) && array_key_exists('stocks',$productStocks))
            {
                $qty = 0;
                foreach($productStocks['stocks'] as $stock)
                {
                    $qty = $stock['quantity'];
                }
                return $qty;
            }
            return 0;
        }


        //product variation;
        public function productVariation($productId)
        {
            $apiUrl = bigbuyApiUrl_hd();
            $curl = curl_init();
            $url = "{$apiUrl}/rest/catalog/productvariations/$productId.json";
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
                    "Authorization: Bearer ".bigbuyApiKey_hd()
                    ),
                ));
            $response = curl_exec($curl);
            curl_close($curl);
            return json_decode($response);
            //have to process
        }


        //product variation stock;
        public function productVariationStock($variationId) // 5sec 1request
        {
            $apiUrl = bigbuyApiUrl_hd();
            $curl = curl_init();
            $url = "{$apiUrl}/rest/catalog/productvariationsstock/$variationId.json";
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
                    "Authorization: Bearer ".bigbuyApiKey_hd()
                    ),
                ));
            $response = curl_exec($curl);
            curl_close($curl);
            $productVariationStocks = json_decode($response);
            $qty = 0 ;
            if($productVariationStocks &&  is_array($productVariationStocks->stocks))
            { 
                foreach($productVariationStocks->stocks as $stock)
                {
                    $qty = $stock->quantity;
                }
            }
            return $qty;
        }


        //get product varaition details (by single variation)
        public function productVariaitonDetail($variationId) //5 sec 1 request
        {
            $apiUrl = bigbuyApiUrl_hd();
            $curl = curl_init();
            $url = "{$apiUrl}/rest/catalog/variation/$variationId.json";
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
                    "Authorization: Bearer ".bigbuyApiKey_hd()
                    ),
                ));
            $response = curl_exec($curl);
            curl_close($curl);
            return $productStocks = json_decode($response);
        }


        //product attribute details by attribute id
        public function productAttributeDetail($attributeId)// 5 sec 1 request
        {
            $apiUrl = bigbuyApiUrl_hd();
            $curl = curl_init();
            $url = "{$apiUrl}/rest/catalog/attribute/$attributeId.json?isoCode=en";
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
                    "Authorization: Bearer ".bigbuyApiKey_hd()
                    ),
                ));
            $response = curl_exec($curl);
            curl_close($curl);
            return json_decode($response);
        }
        

        //product attribute group detail by attribute Group Id
        public function productAttributeGroupDetail($attributeGroupId) // 1 sec 1 request
        {
            $apiUrl = bigbuyApiUrl_hd();
            $curl = curl_init();
            $url = "{$apiUrl}/rest/catalog/attributegroup/$attributeGroupId.json?isoCode=en";
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
                    "Authorization: Bearer ".bigbuyApiKey_hd()
                    ),
                ));
            $response = curl_exec($curl);
            curl_close($curl);
            return  json_decode($response);
        }
    /*
    |-----------------------------------------------------------
    | all dependency api 
    |
    |---------------------------------------------------------------------------------
    |
    |
    */
    
  
}
