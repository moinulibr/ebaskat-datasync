<?php

namespace App\Traits;
use DB;
use App\Models\Brand;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use App\Models\Childcategory;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Log;
use App\Traits\BigbuyShippingDetails;
use App\Jobs\BigbuyProductImportByJob;


trait BigbuyProductImportByQueue
{
    use BigbuyShippingDetails;
    public $importingPageNo;


    /**
     * product import : from admin menu
     * page wise product import from amdin
     * insert product data
     * $product
     */
    //private function insetingProductData($datas,false)
    private function insetingProductData($product,$progressbar = false)
    {
        sleep(5);
        $informationName   = NULL;
        $informationDetail = NULL;
        $information = $this->productInformationForBigbuyImport($product->id);
        if(is_object($information))
        {
            $informationName   =  $information->name?? NULL;
            $informationDetail =  $information->description ?? NULL;
        }

        //stock
        $productStocks = $this->productStockForBigbuyImport($product->id);
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
        

        // product id, default category id
        $adultCategoryExist = $this->checkAdultCategoryExistOrNot($product->id,$product->category);
        $importable     = false;
        if($progressbar ==  true)
        {
            if (($informationName != NULL) && ($adultCategoryExist == 0) && ($product->active == 1) &&
                ($stockQty >  5)
            ){
                $importable = true;
            }else{
                $importable = false;
            }
        }else{
            $importable = true;
        }

        
        if (($importable ==  true) &&
            (!Product::where('product_from','Bigbuy')->select('product_from','ds_product_id')->where('ds_product_id',$product->id)->exists())
        )
        {
            $input                  = new Product();
            $input->product_from    = 'Bigbuy';
            $input->sku             = $product->sku;
            $input->ds_product_id   = $product->id;

            $firstCatId             = NULL;
            $getCategory            = NULL;
            $categories             = [];

            /*
            |------------------------------------------
            | Category related section
            |------------------------------------------
            | sleep(5);product categories  5 sec sleep(5);
            */
                $categories =  $this->productCategoriesForBigbuyImport($product->id);
                if(is_array($categories))
                {
                    $firstCatId = $categories[0]->id;
                }
                if($firstCatId == NULL)
                {
                    $getCategory = $product->category;
                }else{
                    $getCategory = $firstCatId;
                }
                $category = [];
                sleep(5);//5 sec sleep(5);
                $category = $this->productCategoryForBigbuyImport($getCategory);

                $subcategoryAndCategory = $this->bigbuySubCategoryForBigbuyImport($category['slug']);
                $input->category_id     = $subcategoryAndCategory['categoryId'];
                $input->subcategory_id  = $subcategoryAndCategory['subCategoryId'];
            /*
            |------------------------------------------
            | Category related section
            |------------------------------------------
            */


            //sleep(5);//api max 1 request - period - 5sec  sleep(5);
            $input->name            = $informationName;
            $input->description     = $informationDetail;

            $input->stock_quantity  = $stockQty;
            $input->stock_status    = $stockQty > 0 ? 1 : 0;
          
            //for brand
            $input->brand_id = defaultBrandId_hd();

            // length, widht, height, weight for measure field
            $dimensions = [
                "weight" => $product->weight ?? "",
                "length" => $product->depth ?? "",
                "width" =>  $product->width ?? "",
                "height" => $product->height ?? "",
            ];
            $input->dimension = json_encode($dimensions);
            // length, widht, height, weight for measure field

            //photo ,thumbnail
            $input->photo           = "no image";
            $input->thumbnail       = "no image";
            //photo ,thumbnail
            $input->product_type    = 'normal';

            /*
            |---------------------------
            | price
            | $product->retailPrice
            | $product->wholesalePrice;
            |---------------------------
            */
            $input->current_price   = floatval($product->retailPrice);
            $input->regular_price   = floatval($product->retailPrice); //dropshipping product real price
            $input->sale_price      = floatval($product->wholesalePrice);

            $input->status          = $product->active == 1 ? 1 : 0;
            $input->updateable      = 2 ;
            // Save Data
            $input->save();


            /*
            |------------------------------------------
            | gallery related section : 5 sec
            |------------------------------------------
            */
                $productImages = [];
                //sleep(5);//api max 1 request - period - 5sec  sleep(5);
                $productImages = $this->productImagesForBigbuyImport($product->id);
                $i = 1;
                foreach($productImages as  $image)
                {
                    if($image['covers'] == 1)
                    {
                        $productPhoto = $image['photo'];
                        //break;
                    }
                    /* $gallery = new Gallery();
                    $gallery->ds_photo_id   = $image['id'];
                    $gallery->name          = $image['name'];
                    $gallery->photo         = $image['photo'];
                    $gallery->product_id    = $input->id;//product id
                    $gallery->save();
                    $iii++; */
                }
                $input->images = json_encode($productImages);
            /*
            |------------------------------------------
            | gallery related section : 5 sec
            |------------------------------------------
            */



            /*
            |----------------------------------------------------------
            | when product variation found
            |----------------------------------------------------------
            |
            */
                $productVariantSku = "";
                //sleep(5);//api max 1 request - period - 5sec  sleep(5);
                $productType        = 'normal';
                $productVariations  = $this->productVariationForBigbuyImport($product->id);
                if(isset($productVariations) && !empty($productVariations) && $productVariations && is_array($productVariations))
                {
                    foreach($productVariations as $variation)
                    {   //we accept all variation
                        $productVariationId     = $variation->id;
                        $productVariationSku    = $variation->sku;
                        $productVariantSku      = $variation->sku;
                        //$variation->wholesalePrice;

                        $dimensions = [
                            "length" => $variation->depth ?? "",
                            "width" =>  $variation->width ?? "",
                            "height" => $variation->height ?? "",
                        ];
                        $variationDimensionJson = json_encode($dimensions);

                        sleep(5);  //api max 1 request - period - 5sec  sleep(5);
                        $variantStock =  $this->productVariationStockForBigbuyImport($productVariationId);
                            /*
                            |-------------------------------------------
                            | arrtibutes Detial (arrtibutesDetial)
                            |-------------------------------------------
                            */
                                $variationAttributes = [[]];
                                //sleep(5);//api max 1 request - period - 5sec  sleep(5);
                                $arrtibutesDetial = $this->productVariaitonDetailForBigbuyImport($productVariationId);
                                $i = 1;
                                if($arrtibutesDetial && is_array($arrtibutesDetial->attributes))
                                {
                                    foreach($arrtibutesDetial->attributes as  $attribute)
                                    {
                                        if($i == 1)
                                        {
                                            $arrtibuteId = $attribute->id;
                                        }
                                    }
                                    //sleep(5);//api max 1 request - period - 5sec  sleep(5);
                                    $attributeSingle    = $this->productAttributeDetailForBigbuyImport($arrtibuteId);
                                    $attributeName      = $attributeSingle?$attributeSingle->name:NULL;
                                    $attributeGroupId   = $attributeSingle?$attributeSingle->attributeGroup:NULL;

                                    sleep(1); //api max 1 request - period - 1sec  sleep(1);
                                    $attributeGroup = $this->productAttributeGroupDetailForBigbuyImport($attributeGroupId);
                                    $attributeGroupName =  $attributeGroup?$attributeGroup->name:NULL;

                                    $variationAttributes = [
                                        [
                                            "name" => $attributeGroupName,
                                            "value" => $attributeName
                                        ]
                                    ];
                                }//end if, arrtibutesDetial
                            /*
                            |-------------------------------------------
                            | arrtibutes Detial (arrtibutesDetial)
                            |-------------------------------------------
                            */

                        

                        $productVariant = new ProductVariant();
                        $productVariant->product_id = $input->id;

                        $productVariant->ds_product_id = $input->ds_product_id;
                        $productVariant->product_from  = "Bigbuy";
                        $productVariant->ds_variation_id = $productVariationId;
                        $productVariant->variation_sku = $productVariationSku;

                        $productVariant->current_price = floatval($variation->retailPrice);
                        $productVariant->regular_price = $variation->retailPrice;
                        $productVariant->sale_price = $variation->retailPrice;

                        $productVariant->stock_quantity = $variantStock;
                        $productVariant->stock_status = $variantStock > 0 ? 1 : 0;

                        $productVariant->attributes = json_encode($variationAttributes);
                        $productVariant->dimension = $variationDimensionJson;
                        $productVariant->variation_photo = $productPhoto;
                        $productVariant->save();
                        $productType = 'variant';
                    }//end foreach
                }
                //when variant not found
                else{
                    $attributes = json_encode([["name"=>"size","value"=>"regular"]]);
                    $productVariant = new ProductVariant();
                    $productVariant->product_id = $input->id;
                    $productVariant->ds_product_id = $input->ds_product_id;
                    $productVariant->product_from  = "Bigbuy";
                    $productVariant->current_price = $input->current_price;
                    $productVariant->regular_price = $input->regular_price;
                    $productVariant->sale_price = $input->sale_price;
                    $productVariant->stock_quantity = $input->stock_quantity;
                    $productVariant->stock_status = $input->stock_quantity > 0 ? 1 : 0;

                    $productVariant->attributes = $attributes;
                    $productVariant->variation_photo = $productPhoto;
                    $productVariant->dimension =  json_encode($dimensions);
                    $productVariant->save();
                    $productType = 'normal';
                }
            /*
            |----------------------------------------------------------
            | when product variation found
            |----------------------------------------------------------
            */

            $input->photo           = $productPhoto ?? $input->photo;
            $input->thumbnail       = $productPhoto ?? $input->thumbnail;
            $input->product_type    = $productType;

            if($input->photo == "no image" || $input->photo == NULL)
            {
                $input->stock_quantity   = $input->stock_quantity;
                $input->status  = 0;
            }else{
                $input->stock_quantity  = $input->stock_quantity;
                $input->status          = $input->status;
            }


            //shipping cost
            $this->countryShortCode = defaultShippingCountryForBigbuy_hd();
            $this->postCode         = defaultShippingPostCodeForBigbuy_hd();
            $this->productSku       = $productVariantSku ? $productVariantSku : $product->sku;
            $this->orderQuantity    = 1;
            //get shipping details by single sku of single product #[get json encoded data]
            $data = $this->getShippingDetailsBySingleProductSku();
            //convert to json decode from json encoded data
            $this->shippingOption = json_decode($data);
            if(is_object($this->shippingOption) && !isset($this->shippingOption->code)
                    && !is_string($this->shippingOption)
                )
            {
                //get minimum shipping cost by single sku of single product
                $input->shipping_cost =   $this->getMinimumShippingOnlyCostFromShippingDetails();
            }else{
                $input->shipping_cost = $this->getShippingChargeForBigbuy();
            }

            $input->save();

        }//end if. if product not exist


        if($progressbar == true)
        {
            //for progress bar
            $pathName = public_path('temp/dropship_bigbuy/');
            $previousInsertedRow    = file_get_contents($pathName.'/insert_row.txt');
            $previousTotalRow       = file_get_contents($pathName.'/total_row.txt');

            $currentInsetingRow = intval($previousInsertedRow) + 1;
            $remainingTotalRow  = (intval($previousTotalRow) - intval($currentInsetingRow));
            file_put_contents($pathName.'/insert_row.txt',  $currentInsetingRow);
            file_put_contents($pathName.'/remaining_insert_row.txt',$remainingTotalRow);
            //for progress bar
        }
        return true;
    }
    /**
     * product import : from admin menu
     * page wise product import from amdin
     * insert product data
     * $product
     */



    /**
     * ebaskat extra charge added with bigbuy product price
     * this is working...
     * @param [product_from] $price
     */
    private function ebaskatExtraProductChargeForBigbuy($price)
    {
        return addExtraChargeForEbaskat_hd($price);
    }


    /**
     * get shipping charge for bigbuy
     * when carrier cost not found
     */
    private function getShippingChargeForBigbuy()
    {
        return 2;
    }



    /*
    |-------------------------------------------------------------------------
    |-------------------------------------------------------------------------
    | get single product by product sku
    |----------------------------------------------------
    |
    */
        protected function productResponseByProductIdThroughSku($productSku)
        {
            $productId  = $this->getProductIdImportBySku($productSku);
            if($productId)
            {
                $apiUrl = bigbuyApiUrl_hd();
                $url = "{$apiUrl}/rest/catalog/product/{$productId}.json";

                $client = new Client(['verify' => false,'http_errors' => false]);
                $response = $client->request('GET', $url, [
                    'headers' => [
                        'Authorization' => 'Bearer '.bigbuyApiKey_hd(),
                        'Accept' => 'application/json',
                    ],
                ]);
                $arrayFormatedData = $response->getStatusCode()==404?[]:json_decode($response->getBody()->getContents(),true);
                if(array_key_exists('id',$arrayFormatedData))
                {
                    $product = $arrayFormatedData;
                    $this->insetingProductData((object)$product,false);
                    return [
                        'status'    => true,
                        'message'   => 'Product imported successfully'
                    ];
                }
            }
            return [
                'status'    => false,
                'message'   => 'Product not match'
            ];
        }

        protected function getProductIdImportBySku($productSku)
        {
            $apiUrl = bigbuyApiUrl_hd();
            $url = "{$apiUrl}/rest/catalog/productinformationbysku/{$productSku}.json?isoCode=en";

            $client = new Client(['verify' => false,'http_errors' => false]);
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer '.bigbuyApiKey_hd(),
                    'Accept' => 'application/json',
                ],
            ]);

            $arrayData = $response->getStatusCode()==404?[]:json_decode($response->getBody()->getContents(),true);

            if(array_key_exists('id',$arrayData)){
                return $arrayData['id'];
            }
            return false;
        }
    /*
    |-------------------------------------------------------------------------
    |-------------------------------------------------------------------------
    | get single product by product sku
    |----------------------------------------------------
    |
    */





    /*
    |-------------------------------------------------------------------------
    |-------------------------------------------------------------------------
    | all dependency api
    |----------------------------------------------------
    |
    */
        //check checkAdultCategoryExistOrNot()
        public function checkAdultCategoryExistOrNot($productId,$defaultCategoryId)
        {
            $defaultCategoryFound = 0;
            //sleep(5);//product categories  5 sec sleep(5);
            $defaultCategory = $this->productCategoryForBigbuyImport($defaultCategoryId);
            if($this->categorySlugMatch($defaultCategory['slug']) == "slugable")
            {
                return $defaultCategoryFound = 1;
            }
            return $defaultCategoryFound;
            /* $categories =  $this->productCategoriesForBigbuyImport($product->id);
            if(is_array($categories))
            {
                foreach($categories as $category)
                {
                    sleep(5);
                    $categoryFromCategories = $this->productCategoryForBigbuyImport($category->id);
                    if($this->categorySlugMatch($categoryFromCategories['slug']) == "slugable")
                    {
                        $defaultCategoryFound = 1;
                    }
                }
            }
            return  $defaultCategoryFound; */
        }
        private function categorySlugMatch($slug)
        {
            if(($slug != 'No slug') && (
                ($slug == "sex-cards") || ($slug == "sex-shop-erotic") ||
                ($slug == "erotic-toys-and-games") || ($slug == "erotic-toys-game") ||
                ($slug == "massages-and-rotic-games") || ($slug == "massages-rotic-games") ||
                ($slug == "fancy-dress-and-celebration-items") || ($slug == "fancy-dress-celebration-items") ||
                ($slug == "toys-fancy-dress") || ($slug == "parties-and-celebrations")
                )
            )
            {
                return "slugable";
            }else{
                return "no";
            }
        }


        //product details 5 sec
        public function productInformationForBigbuyImport($productId)
        {
            $apiUrl = bigbuyApiUrl_hd();
            $url = "{$apiUrl}/rest/catalog/productinformation/$productId.json?isoCode=en";
            $client = new Client(['verify' => false,'http_errors' => false]);
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer '.bigbuyApiKey_hd(),
                    'Accept' => 'application/json',
                ],
            ]);
            return $information =  $response->getStatusCode()==404?[]:json_decode($response->getBody()->getContents());

            $products   = [];
            $products['name']   =  $information->name?? "No Name";
            $products['detail'] =  $information->description ?? "No Details";
            return $products;
        }

        //product Images 5 sec
        public function productImagesForBigbuyImport($productId)
        {
            $apiUrl = bigbuyApiUrl_hd();
            $url = "{$apiUrl}/rest/catalog/productimages/$productId.json";
            $client = new Client(['verify' => false,'http_errors' => false]);
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer '.bigbuyApiKey_hd(),
                    'Accept' => 'application/json',
                ],
            ]);
            $productImages =  $response->getStatusCode()==404?[]:json_decode($response->getBody()->getContents());
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


        //product category 5 sec sleep(5);
        public function productCategoryForBigbuyImport($categoryId)
        {
            $apiUrl = bigbuyApiUrl_hd();
            $url = "{$apiUrl}/rest/catalog/category/$categoryId.json?isoCode=en";
            $client = new Client(['verify' => false,'http_errors' => false]);
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer '.bigbuyApiKey_hd(),
                    'Accept' => 'application/json',
                ],
            ]);
            $cat =  $response->getStatusCode()==404?[]:json_decode($response->getBody()->getContents());
            $category   = [];
            $category['name']   =  $cat->name?? "No Name";
            $category['slug']   =  $cat->url ?? "No slug";
            return $category;
        }

        //product categories  5 sec sleep(5);
        public function productCategoriesForBigbuyImport($productId)
        {
            $apiUrl = bigbuyApiUrl_hd();
            $url = "{$apiUrl}/rest/catalog/productcategories/$productId.json";
            $client = new Client(['verify' => false,'http_errors' => false]);
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer '.bigbuyApiKey_hd(),
                    'Accept' => 'application/json',
                ],
            ]);
            return $response->getStatusCode()==404?[]:json_decode($response->getBody()->getContents());
        }


        //product stock; 5 sec
        public function productStockForBigbuyImport($productId)
        {
            $apiUrl = bigbuyApiUrl_hd();
            $url = "{$apiUrl}/rest/catalog/productstock/$productId.json";
            $client = new Client(['verify' => false,'http_errors' => false]);
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer '.bigbuyApiKey_hd(),
                    'Accept' => 'application/json',
                ],
            ]);
            $productStocks = $response->getStatusCode()==404?[]:json_decode($response->getBody()->getContents(),true);
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
        public function productVariationForBigbuyImport($productId)
        {
            $apiUrl = bigbuyApiUrl_hd();
            $url = "{$apiUrl}/rest/catalog/productvariations/$productId.json";
            $client = new Client(['verify' => false,'http_errors' => false]);
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer '.bigbuyApiKey_hd(),
                ],
            ]);

            if ($response->getStatusCode()==404){
                return [];
            }else{
                return json_decode($response->getBody()->getContents());
            }
        }

        //product variation stock;
        public function productVariationStockForBigbuyImport($variationId) // 5sec 1request
        {
            $apiUrl = bigbuyApiUrl_hd();
            $url = "{$apiUrl}/rest/catalog/productvariationsstock/$variationId.json";
            $client = new Client(['verify' => false,'http_errors' => false]);
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer '.bigbuyApiKey_hd(),
                    'Accept' => 'application/json',
                ],
            ]);

            $productVariationStocks = $response->getStatusCode()==404?[]:json_decode($response->getBody()->getContents());
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
        public function productVariaitonDetailForBigbuyImport($variationId) //5 sec 1 request
        {
            $apiUrl = bigbuyApiUrl_hd();
            $url = "{$apiUrl}/rest/catalog/variation/$variationId.json";
            $client = new Client(['verify' => false,'http_errors' => false]);
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer '.bigbuyApiKey_hd(),
                    'Accept' => 'application/json',
                ],
            ]);

            return $productStocks = $response->getStatusCode()==404?[]:json_decode($response->getBody()->getContents());
        }

        //product attribute details by attribute id
        public function productAttributeDetailForBigbuyImport($attributeId)// 5 sec 1 request
        {
            $apiUrl = bigbuyApiUrl_hd();
            $url = "{$apiUrl}/rest/catalog/attribute/$attributeId.json?isoCode=en";
            $client = new Client(['verify' => false,'http_errors' => false]);
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer '.bigbuyApiKey_hd(),
                    'Accept' => 'application/json',
                ],
            ]);
            return $response->getStatusCode()==404?[]:json_decode($response->getBody()->getContents());
        }

        //product attribute group detail by attribute Group Id
        public function productAttributeGroupDetailForBigbuyImport($attributeGroupId) // 1 sec 1 request
        {
            $apiUrl = bigbuyApiUrl_hd();
            $url = "{$apiUrl}/rest/catalog/attributegroup/$attributeGroupId.json?isoCode=en";
            $client = new Client(['verify' => false,'http_errors' => false]);
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer '.bigbuyApiKey_hd(),
                    'Accept' => 'application/json',
                ],
            ]);
            return $response->getStatusCode()==404?[]:json_decode($response->getBody()->getContents());
        }


        /*
        | get ebaskat category and subcategory by bigbuy
        | sub-category slug
        */
        public function bigbuySubCategoryForBigbuyImport($bigbuyKey)
        {
            $helperFileSubcategory = subCategory_hh();
            $subCategory_id = NULL;
            $category_id    = NULL;
            //$bigbuyKey      = "accessories-for-mobile-phones-and-tablets";//"Accessories-for-cameras-and-camcorders";
            $bigbuyKeies    = explode('-',$bigbuyKey);
            $matchStatus    = 0;
            if(array_key_exists($bigbuyKey,$helperFileSubcategory))
            {
                $subCategory_id     = $helperFileSubcategory[$bigbuyKey]['id'];
                $category_id        = $helperFileSubcategory[$bigbuyKey]['parentId'];
            }
            else if(array_key_exists(substr($bigbuyKey,0,-1),$helperFileSubcategory)){
                $subCategory_id     = $helperFileSubcategory[substr($bigbuyKey,0,-1)]['id'];
                $category_id        = $helperFileSubcategory[substr($bigbuyKey,0,-1)]['parentId'];
            }
            else if(array_key_exists(substr($bigbuyKey,0,-2),$helperFileSubcategory)){
                $subCategory_id     = $helperFileSubcategory[substr($bigbuyKey,0,-2)]['id'];
                $category_id        = $helperFileSubcategory[substr($bigbuyKey,0,-2)]['parentId'];
            }else{
                // if not direct match with ebaskat subcategory
                foreach($bigbuyKeies as $bigbuy_key => $bigbuy_value)
                {
                    foreach($helperFileSubcategory as $ebaskat_category_key =>$x_value)
                    {
                        if(preg_match('/\b' . preg_quote($bigbuy_value, '/') . '\b/i', $ebaskat_category_key, $matches))
                        {
                            $matchStatus = 1;
                            if(array_key_exists($ebaskat_category_key,$helperFileSubcategory))
                            {
                                $subCategory_id     = $helperFileSubcategory[$ebaskat_category_key]['id'];
                                $category_id        = $helperFileSubcategory[$ebaskat_category_key]['parentId'];
                            }
                            break;
                        }
                    }
                    if($matchStatus ==1)break;
                }
                if($subCategory_id == NULL)
                {
                    $category_id    = defaultCategoryId_hd();
                    $subCategory_id = defaultSubCategoryId_hd();
                }
            }
            //echo "<br/> sub category id : ".$subCategory_id;
            //echo ", category id : ".$category_id;
            return ["categoryId" => $category_id,"subCategoryId" => $subCategory_id];
        }
    /*
    |-----------------------------------------------------------
    | all dependency api
    |
    |---------------------------------------------------------------------------------
    |
    |
    */


    //
    public function importableProductByProductId($productId)
    {
        ini_set('max_execution_time', 28800);
        //sleep(5);
        $curl = curl_init();
        $apiUrl = bigbuyApiUrl_hd();
        $url = "{$apiUrl}/rest/catalog/product/{$productId}.json";
    
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
            //$this->changeProductStatusWhenProductNotFound($productId);
            return true;
        }
        else if(isset($product->id) && !empty($product->id)){
            //sleep(5);
            $this->insetingProductData($product);
        }else{
            sleep(5);
            return true;
            //$this->changeProductStatusWhenProductNotFound($productId);
            //"not found";
        }
        return true;
    }




    //NOT USING THIS SECTION
        // category check or create.. not using now
        public function createOrExistCheckCategory($slug,$name)
        {
            if($cat = Category::where(DB::raw('lower(slug)'), strtolower($slug))->first()){
                return $cat->id;
            }else{
                $newCat = new Category();
                $newCat->name   = $name;
                $newCat->slug   = $slug;
                if($name == "No Name")
                {
                    $newCat->status = 0;
                }else{
                    $newCat->status = 1;
                }
                $newCat->save();
                return $newCat->id;
            }
        }
    //NOT USING THIS SECTION

}









//14-06-2022/* $chunkData = array_chunk($datas,10,true);foreach($chunkData as  $products){foreach($products as  $product){  14-06-2022 */
//}//end products foreach 14-06-2022 //} //end chunkdata foreach 14-06-2022    