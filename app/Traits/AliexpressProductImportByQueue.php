<?php

namespace App\Traits;
use DB;
use App\Models\Brand;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Traits\FileStorage;
use Illuminate\Support\Str;
use App\Models\Childcategory;
use App\Models\ProductVariant;
use App\Jobs\AliexpressProductImportByJob;


/**
 * aliexpress product update, stock update
 */
trait AliexpressProductImportByQueue
{
    public $perPageProductWhenImport;

    protected $customer_key;
    protected $customer_secret;

    public $importPageNo;



    /**
     * insert product data  
     * page wise insert
     * 
     * @param [product_from] $datas
     */
    //private function insetProductData($datas,false)
    private function insetProductData($product,$progressbar = NULL)
    {
        if((strtolower($product->status) != "draft") &&
        (preg_match('/\b' . preg_quote("Import placeholder", '/') . '\b/i', $product->name, $matches)) === 0
        )
        {
            if (!Product::where('product_from','Aliexpress')->select('ds_product_id')->where('ds_product_id',$product->id)->exists())
            {
                if(!$product->stock_quantity || empty($product->stock_quantity) || $product->stock_quantity == NULL)
                {
                    $stockQty = 0 ;
                }
                elseif(is_int(intval($product->stock_quantity)) || is_double(doubleval($product->stock_quantity)) )
                {
                    $stockQty = $product->stock_quantity ;
                }else{
                    $stockQty = 0 ;
                }

                $importable = false;
                if($progressbar ==  true)
                {
                    $importable = $stockQty >  5 ? 1 : false ;
                }else{
                    $importable = true;
                }
                if($importable == true)
                {
                    $input  = new Product();

                    $input->product_from    = 'Aliexpress';
                    $input->sku             = $product->sku; 
                    $input->ds_product_id   = $product->id;

                    $data['category']           = "";
                    $data['categorySlug']       = "";
                    $data['subCategory']        = "";
                    $data['subCategorySlug']    = "";
                    $data['childCategory']      = "";
                    $data['childCategorySlug']  = "";
                    if(count($product->categories) > 0)
                    {
                        foreach($product->categories as $j => $cat)//product
                        {
                            if(($j+1) == 1)
                            {
                                //cat
                                $data['category']           =   $cat->name;
                                $data['categorySlug']       =   $cat->slug;
                            }
                            if(($j+1) == 2)
                            {
                                //sub cat
                                $data['subCategory']        =   $cat->name;
                                $data['subCategorySlug']    =   $cat->slug;
                            }
                            if(($j+1) == 3)
                            {
                                //child cat
                                $data['childCategory']      =   $cat->name;
                                $data['childCategorySlug']  =   $cat->slug;
                            }
                        }
                    }

                    $main_categoryId     = defaultCategoryId_hd();
                    $sub_categoryId      = defaultSubCategoryId_hd();
                    $child_categoryId    = defaultChildCategoryId_hd();
                    if($data['category'])
                    {
                        $mainCats = $this->getAllCategoryWhenProductImport($data['categorySlug']);
                        if($mainCats['categoryId'] != "" && $mainCats['subCategoryId'] == "" && $mainCats['childCategoryId'] == "")
                        {
                            $main_categoryId    = $mainCats['categoryId'];
                        }
                        elseif( $mainCats['subCategoryId'] != "" && $mainCats['categoryId'] == "" && $mainCats['childCategoryId'] == "")
                        {
                            $sub_categoryId    = $mainCats['subCategoryId'];
                        }
                        elseif($mainCats['childCategoryId'] != "" && $mainCats['categoryId'] == "" && $mainCats['subCategoryId'] == "")
                        {
                            $sub_categoryId    = $mainCats['childCategoryId'];
                        }
                    }
                    if($data['subCategory'])
                    {
                        $subCats = $this->getAllCategoryWhenProductImport($data['subCategorySlug']);
                        if($subCats['categoryId'] != "" && $subCats['subCategoryId'] == "" && $subCats['childCategoryId'] == "")
                        {
                            $main_categoryId    = $subCats['categoryId'];
                        }
                        elseif($subCats['subCategoryId'] != ""  && $subCats['categoryId'] == "" && $subCats['childCategoryId'] == "")
                        {
                            $sub_categoryId    = $subCats['subCategoryId'];
                        }
                        elseif($subCats['childCategoryId'] != ""  && $subCats['categoryId'] == "" && $subCats['subCategoryId'] == "")
                        {
                            $sub_categoryId    = $subCats['childCategoryId'];
                        }
                    }
                    if($data['childCategory'])
                    {
                        $childCats = $this->getAllCategoryWhenProductImport($data['childCategorySlug']);
                        if($childCats['categoryId'] != "" && $childCats['subCategoryId'] == "" && $childCats['childCategoryId'] == "")
                        {
                            $main_categoryId    = $childCats['categoryId'];
                        }
                        elseif($childCats['subCategoryId'] != ""  && $childCats['categoryId'] == "" && $childCats['childCategoryId'] == "")
                        {
                            $sub_categoryId    = $childCats['subCategoryId'];
                        }
                        elseif($childCats['childCategoryId'] != ""  && $childCats['categoryId'] == "" && $childCats['subCategoryId'] == "")
                        {
                            $sub_categoryId    = $childCats['childCategoryId'];
                        }
                    }
                    $input->category_id         = $main_categoryId ;
                    $input->subcategory_id      = $sub_categoryId ;

                    //add extra charge with price
                    $input->current_price       = floatval($product->price);
                    $input->regular_price       = floatval($product->regular_price);
                    $input->sale_price          = floatval($product->sale_price);
                    $input->shipping_cost       = $this->getShippingChargeWhenProductImport(($product->price)); 

                    
                    /*
                    |------------------------------------------------------------------------
                    | color,size, size qty, size price - attribute
                    |------------------------------------------------
                    |
                    */
                        $data = [];
                        $data['brands']     = "";
                        $productAttributes  = [];
                        // product attributes
                        if(count($product->attributes) > 0)
                        {
                            foreach($product->attributes as $in => $attributes)
                            {
                                $sizeSkipable = 0;
                                if(strtolower($attributes->name) == "size" || strtolower($attributes->name) == strtolower("US size"))
                                {
                                    foreach($attributes->options as $j=> $attr)
                                    {    
                                        if(strtolower($attr) == strtolower("Custom Size")) 
                                        {
                                            $sizeSkipable = 1;
                                        }
                                    }
                                }
                                if($sizeSkipable > 0)
                                {
                                    continue;
                                }
                                if(strtolower($attributes->name) == strtolower("Brand Name"))
                                {
                                    foreach($attributes->options as $i=> $attr)
                                    {
                                        $data['brands'] .= $attr;
                                        if(($i+1) < count($attributes->options))
                                        {
                                            $data['brands'] .= ",";
                                        }
                                    }
                                }

                                $productAttributes[$in] = [
                                    "name" => $attributes->name,
                                    "value" => $attributes->options[0]
                                ];

                            } //end foreach for attributes
                        }//end if for attributes
                    /*
                    |----------------------------------------
                    | color,size, size qty, size price - attribute
                    |----------------------------------------------------------------------------
                    */  

                    $input->name            = $product->name ? $product->name : "product name empty";
                    $input->description     = $product->description;

                    if(strtolower($product->status) == "draft" ||
                        strtolower($product->status) == "pending"  ||
                        strtolower($product->status) == "private" 
                    )
                    {
                        $input->status      = 0;
                    }else{
                        $input->status      = 1;
                    }
                    $input->updateable      = 2;
                    $input->stock_quantity  = $stockQty;
                    if($product->stock_status == 'instock')
                    {
                        $input->stock_status = 1;
                    }else{
                        $input->stock_status = 0;
                    }
                    $input->tax_status      = $product->tax_status;
                    //for brand
                    if($data['brands'])
                    {
                        $brandWithoutAnd =  Str::slug($data['brands'], '-');
                        $brandSlug = str_replace('amp', 'and', $brandWithoutAnd);
                        $brand = Brand::where(DB::raw('lower(slug)'), strtolower($brandSlug))->first();
                        if($brand)
                        {
                            $input->brand_id = $brand->id;
                        }else{
                            $input->brand_id = defaultBrandId_hd();
                        }
                    }else{
                        $input->brand_id = defaultBrandId_hd();
                    }
                    //for brand

                    // length, widht, height, weight for measure field
                    $length = ""; $width  = ""; $height = "";
                    if($dimension = $product->dimensions)
                    {
                        $length = $dimension->length;
                        $width  = $dimension->width;
                        $height = $dimension->height;
                    }
                    $dimensions = [
                        "weight" => $product->weight ?? "",
                        "length" => $length,
                        "width" => $width,
                        "height" => $height,
                    ];
                    $input->dimension = json_encode($dimensions);
                    // length, widht, height, weight for measure field

                    //photo ,thumbnail
                    if(count($product->images) > 0)
                    {
                        $input->photo       =   $product->images[0]->src;
                        $input->thumbnail   =   $product->images[0]->src;
                    }else{
                        $input->photo       =   "no image";
                        $input->thumbnail   =   "no image";
                    }
                    //photo ,thumbnail 

                    $input->product_type = 'normal';
                    $input->slug         = $product->slug;
                    // Save Data
                    $input->save();
                    // product gallery
                    if(count($product->images) > 0)
                    {
                        foreach($product->images as  $image)
                        {
                            $gallery = new Gallery;
                            $gallery->ds_photo_id   = $image->id;
                            $gallery->name          = $image->name;
                            $gallery->photo         = $image->src;
                            $gallery->product_id    = $input->id;//product id
                            $gallery->save();
                        }
                    }
                    // product gallery

                    
                    /*
                    |------------------------------------------
                    | when product variation is found
                    | $productVariations = $this->getProductVariation($product->id);
                    |------------------------------------------
                    */
                        $productType = 'normal';
                        $productVariations  = []; 
                        $productVariations  = $this->getProductVariation($product->id);
                        if(count($productVariations) > 0)
                        {
                            foreach ($productVariations as  $variationValue)
                            {
                                if(strtolower($variationValue['size']) == strtolower("custom size"))
                                {
                                    continue;
                                }
                            
                                $variation_length = "";
                                $variation_width  = "";
                                $variation_height = "";
                                if($variationDimension =  $variationValue['dimension'])
                                {
                                    $variation_length = $variationDimension->length;
                                    $variation_width  = $variationDimension->width;
                                    $variation_height = $variationDimension->height;
                                }
                                $variationDimensionArray = [
                                    "length" => $variation_length,
                                    "width" => $variation_width,
                                    "height" => $variation_height,
                                ];
                                $variationDimensionJson = json_encode($variationDimensionArray);

                                $attributes = json_encode($variationValue['attributes']);

                                $productVariant = new ProductVariant();
                                $productVariant->product_id         = $input->id;
                                $productVariant->ds_variation_id    = $variationValue['id'];
                                $productVariant->current_price      = floatval($variationValue['price']);
                                $productVariant->regular_price      = floatval($variationValue['regular_price']);
                                $productVariant->sale_price         = floatval($variationValue['sale_price']);
                                $productVariant->stock_quantity     = $variationValue['stock_quantity'];

                                if($variationValue['stock_status'] == 'instock')
                                {
                                    $productVariant->stock_status       = 1;
                                }else{
                                    $productVariant->stock_status       = 0;
                                }

                                $productVariant->attributes         = $attributes;
                                $productVariant->dimension          = $variationDimensionJson;
                                $productVariant->variation_photo    = $variationValue['photo'] ? $variationValue['photo']->src : NULL;
                                $productVariant->save();
                            }
                            $productType = 'variant';
                        }
                        else{
                                $attributes = json_encode($productAttributes);
                                $productVariant = new ProductVariant();
                                $productVariant->product_id         = $input->id;
                                $productVariant->current_price      = $input->current_price;
                                $productVariant->regular_price      = $input->regular_price;
                                $productVariant->sale_price         = $input->sale_price;
                                $productVariant->stock_quantity     = $input->stock_quantity;
                                $productVariant->attributes         = $attributes;
                                $productVariant->variation_photo    = $input->photo;
                                $productVariant->save();
                                $productType = 'normal';
                        }
                        $input->product_type = $productType;
                        $input->save();
                    /*
                    |------------------------------------------
                    | when product variation is found
                    |------------------------------------------
                    */
                }//end if, if stock is more then 5
            
            }//end if. if product not exist
        }

        //for progress bar
        if($progressbar == true)
        {
            $pathName = public_path('temp/dropship_aliexpress/');
            $previousInsertedRow    = file_get_contents($pathName.'/insert_row.txt');
            $previousTotalRow       = file_get_contents($pathName.'/total_row.txt');

            $currentInsetingRow = intval($previousInsertedRow) + 1;
            $remainingTotalRow  = (intval($previousTotalRow) - intval($currentInsetingRow));
            file_put_contents($pathName.'/insert_row.txt',  $currentInsetingRow);
            file_put_contents($pathName.'/remaining_insert_row.txt',$remainingTotalRow);
        }
        //for progress bar
        
        return true;
    }

    /**
     * get all category when product import from aliexpress
     * get category, sub-category, child-category single data
     *
     * @param [product_from] $slug
     */
    public function getAllCategoryWhenProductImport($slug)
    {
        $data = [];
        $data['categoryId'] ="";
        $data['subCategoryId'] ="";
        $data['childCategoryId'] ="";
        if($cat = Category::where(DB::raw('lower(slug)'), strtolower($slug))->first()){
            $data['categoryId'] = $cat->id;
        }
        elseif($sub = Subcategory::where(DB::raw('lower(slug)'), strtolower($slug))->first()){
            $data['subCategoryId'] = $sub->id;
        }elseif($child = Childcategory::where(DB::raw('lower(slug)'), strtolower($slug))->first()){
            $data['childCategoryId'] = $child->id;
        }
        return $data;
    }


    /**
     * get product variation 
     * when product import from aliexpress
     *
     * @param [type] $product_id
     */
    public function getProductVariation($product_id)
    {
        $this->customerKey = restapiCustomerKeyForWoocommerce();
        $this->customerSecret = restapiCustomerSecretForWoocommerce();
        $curlProductVariation = curl_init();
        $urlProductVariation = "https://obaskat.com/wp-json/wc/v3/products/{$product_id}/variations?per_page=100&consumer_key={$this->customerKey}&consumer_secret={$this->customerSecret}";
        curl_setopt_array($curlProductVariation, array(
            CURLOPT_URL => $urlProductVariation,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $responseProductVariaton = curl_exec($curlProductVariation);
        curl_close($curlProductVariation);
        $productVariations = json_decode($responseProductVariaton);

        $variations = [];
        foreach ($productVariations as $index => $data)
        {
            $attrSize       = "";
            $allAttributes  = [];
            foreach($data->attributes as $inx => $attr)
            {
                if(strtolower($attr->name) == "size" || strtolower($attr->name) == strtolower("US size"))
                {
                    $attrSize = $attr->option;
                }
                $allAttributes[$inx] = [
                    "name"  => $attr->name,
                    "value" => $attr->option
                ];
            }
            
            $variations[$index] = [
                "id"            => $data->id,   //ds_variation_id
                "price"         => $data->price, //variation_price
                "regular_price" => $data->regular_price,
                "sale_price"    => $data->sale_price,//$data->sale_price,   //variation_sale_price
                "stock_quantity" => $data->stock_quantity,//variation_stock_quantity
                "size"          => $attrSize,           //variation_size
                "photo"         => $data->image,        //variation_photo
                "stock_status"  => $data->stock_status, //variation_stock_status
                "dimension"     => $data->dimensions,    //variation_dimension
                'attributes'    => $allAttributes
            ];
        }
        return $variations;
    }


    /**
     * ebaskat extra product charge
     * this charge is added with aliexpress incoming price
     * 
     * @param [product_from] $price
     */
    private function ebaskatExtraProductCharge($price)
    {
        return addExtraChargeForEbaskat_hd($price);
    }

     
    /**
     * get shipping charge when product import from aliexpress
     * its using, but fixed amount : 2
     *
     * @param [product_from] $price
     */
    private function getShippingChargeWhenProductImport($price)
    {
        return 2;
    }






    // import products by queue
    //------------------------------------------------------------------------------
        /*
        | page number process for job class
        */
        public function aliexpressProductImportByQueue()
        {
           $totalPages = $this->getTotalPagesNumberWithPublishedAndDraftProductsWhenProductImportByQueue();
            for ($i=1; $i <= $totalPages; $i++) 
            { 
                AliexpressProductImportByJob::dispatch($i);
            }
            return true;	
        }


        /*
        | Importing data by queue : 
        | send page wise data in to the insetProductData() method
        */
        public function pageWiseImportProductByQueue()
        {
            $this->customer_key = restapiCustomerKeyForWoocommerce();
            $this->customer_secret = restapiCustomerSecretForWoocommerce();
            $this->perPageProductWhenImport = 20;
            $curl = curl_init();
            $url = "https://obaskat.com/wp-json/wc/v3/products?page={$this->importPageNo}&per_page={$this->perPageProductWhenImport}&consumer_key={$this->customer_key}&consumer_secret={$this->customer_secret}";
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
            $chunkData = array_chunk($datas,10,true);
            foreach($chunkData as  $products)
            {
                foreach($products as  $product)
                {
                    $this->insetProductData($product,false);
                }
            }
            //$this->insetProductData($datas,false);
            return true;
        }


        /*
        | get total product from obaskat (woocommerce) 
        | 1. published product, 2. Draft
        | use here reports api/Products api
        */
        public function getTotalPagesNumberWithPublishedAndDraftProductsWhenProductImportByQueue()
        {
            $curl = curl_init();
            $this->customer_key      = restapiCustomerKeyForWoocommerce();
            $this->customer_secret   = restapiCustomerSecretForWoocommerce(); //status=draft&
            
            $url = "https://obaskat.com/wp-json/wc/v3/reports/products/totals?consumer_key={$this->customer_key}&consumer_secret={$this->customer_secret}";
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
            $key = 'total';
            $products_count = array_sum(array_column($datas,$key));
            $totalPages = round($products_count/20);
            return   $this->countTotalPagesWhenProductImportByQueue($totalPages);
        }

        /*
        | get total product from obaskat (woocommerce) 
        | 1. published product, 2. Draft
        | use product api in this method
        */
        public function countTotalPagesWhenProductImportByQueue($totalPages)
        {
            ini_set('max_execution_time', 28800);
            $finalPages     = $totalPages;
            $breakNow = 0;
            $newRow = 1;
            for ($totalPages; $newRow != 0; $totalPages++) 
            { 
                $page_no = $totalPages;
                $this->perPageProductWhenImport = 20;
                //--------------------------------------
                    $this->customer_key = restapiCustomerKeyForWoocommerce();
                    $this->customer_secret = restapiCustomerSecretForWoocommerce();
                    $curl = curl_init();
                    $url = "https://obaskat.com/wp-json/wc/v3/products?page={$page_no}&per_page={$this->perPageProductWhenImport}&consumer_key={$this->customer_key}&consumer_secret={$this->customer_secret}";
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
                //--------------------------------------
                
                if(is_array($datas) && count($datas) > 0)
                {
                    $noDataFound =0;
                    foreach($datas as $index =>  $data)
                    {
                        if(preg_match('/\b' . preg_quote("Import placeholder", '/') . '\b/i', $data->name, $matches))
                        {
                            //echo " match, Import placeholder,  ";
                            $noDataFound++;
                            if($noDataFound == 2)
                            {
                                $breakNow = 1;
                            }
                            continue;
                        }
                    }//end foreach 
                if($breakNow == 1)
                {
                        $newRow = 0;
                        break;
                }//end if
                }//end if
                else{
                    $newRow = 0;
                    break;
                }
                $finalPages = $page_no;
            }//end for
            return ($finalPages + 1); 
        }   
    //------------------------------------------------------------------------------
    // Import products by queue


}

















        //22-06-2022/* $chunkData = array_chunk($datas,10,true);foreach($chunkData as  $products){foreach($products as  $product){ */  
        //}//end products foreach//} //end chunkdata foreach