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
use App\Jobs\AliexpressProductUpdateByJob;
use App\Jobs\AliexpressProductUpdateByQueueJob;


/**
 * aliexpress product update, stock update
 */
trait AliexpressProductUpdate
{
    use FileStorage;

    public $pageStart;
    public $pageEnd;

    public $currentPage;
    public $perPage;

    protected $customerKey;
    protected $customerSecret;

    public $updatePageNo;

    public $dsProductId;


    /**
     * update product main method
     * this method call from another place
     */
    public function updateProduct()
    {
        ini_set('max_execution_time', 28800);
        $startPage  = $this->pageStart;
        $endPage    = $this->pageEnd;
        $allPages = "";
        for ($startPage; $startPage <= $endPage; $startPage++) { 
            $allPages .= $startPage;
            if($startPage < $endPage)
            {
                $allPages    .= " ";
            }
        }
        $pages = explode(' ',$allPages);
       
        foreach($pages as  $pageNo)
        {
            $this->customerKey = restapiCustomerKeyForWoocommerce();
            $this->customerSecret = restapiCustomerSecretForWoocommerce();
            $this->currentPage = $pageNo;
            $this->perPage = 20;
            $curl = curl_init();
            $url = "https://obaskat.com/wp-json/wc/v3/products?page={$this->currentPage}&per_page={$this->perPage}&consumer_key={$this->customerKey}&consumer_secret={$this->customerSecret}";
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
                //set total row and inserted row in session for progress bar //
                $pathName = public_path('temp/dropship_aliexpress/');
                file_put_contents($pathName.'/total_row_for_update.txt',0);
                file_put_contents($pathName.'/insert_row_for_update.txt',0);
                file_put_contents($pathName.'/remaining_insert_row_for_update.txt',0);
                //set total row and inserted row in session for progress bar //

                //set total row and inserted row in session for progress bar //
                file_put_contents($pathName.'/total_row_for_update.txt',count($datas) - 1);
                //set total row and inserted row in session for progress bar //
                if(intval($pageNo) < intval($endPage))
                {
                    file_put_contents($pathName.'/next_page_for_update.txt',intval($pageNo)+1);
                }else{
                    file_put_contents($pathName.'/next_page_for_update.txt',0);
                }
                
                file_put_contents($pathName.'/current_page_for_update.txt',$pageNo);
                $chunkData = array_chunk($datas,10,true);
                foreach($chunkData as $products)
                {
                    foreach($products as  $product)
                    {
                        $this->updateProductDetails($product,'regular');
                    }
                }
                //$this->updateProductDetails($datas,'regular');
            }
        else{
                $pathName = public_path('temp/dropship_aliexpress/');
                file_put_contents($pathName.'/total_row_for_update.txt',1);
                file_put_contents($pathName.'/insert_row_for_update.txt',1);
                file_put_contents($pathName.'/remaining_insert_row_for_update.txt',0);

                if(intval($pageNo) < intval($endPage))
                {
                    file_put_contents($pathName.'/next_page_for_update.txt',intval($pageNo)+1);
                }else{
                    file_put_contents($pathName.'/next_page_for_update.txt',0);
                }
                file_put_contents($pathName.'/current_page_for_update.txt',$pageNo);
            }
        }//end top foreach for page
        return true;
    }

    

    /**
     * update product details from aliexpress
     * here uses one api: (method) - getProductVariations()
     * page wise product update 
     * 
     * @param [product_from] $datas
     */
    public function updateProductDetails($product, $updatingFrom = NULL)
    {
        if ($input  = Product::where('product_from','Aliexpress')->where('ds_product_id',$product->id)->first())
            {
                /*
                |---------------------------------------------------------
                |category, sub-category, child category
                |never update in this method without Jahid's bhai permission
                |---------------------------------------------------------
                */

             

                /*
                |---------------------------------------------
                | updateable
                |--------------------------------------------
                */
                $updateable = $input->updateable;
                $updatedFields = [];
                
                if($input->current_price != floatval($product->price))
                {
                    $updateable = 1;
                    array_push($updatedFields,['current_price'=>$input->current_price]);
                    $input->current_price   = floatval($product->price);
                } 
                if($input->regular_price   != floatval($product->regular_price))
                {
                    $updateable = 1;
                    array_push($updatedFields,['regular_price'=>$input->regular_price]);
                    $input->regular_price   = floatval($product->regular_price);
                } 
                if($input->sale_price != floatval($product->sale_price))
                {
                    $updateable = 1;
                    array_push($updatedFields,['sale_price'=>$input->sale_price]);
                    $input->sale_price      = floatval($product->sale_price);
                } 

                /*
                |------------------------------------------------------------------------
                | color,size, size qty, size price - attribute
                |------------------------------------------------
                |
                */
                    $data = [];
                    $data['brands']     = "";
                    $productAttributes  = [];
                    if(count($product->attributes) > 0)
                    {   
                        foreach($product->attributes as $in => $attributes)
                        { 
                            $sizeSkipable = 0;
                            if(strtolower($attributes->name) == "size" || strtolower($attributes->name) == strtolower("US size"))
                            {
                                foreach($attributes->options as $j=> $attr)
                                {    
                                    if( strtolower($attr) ==  strtolower("Custom Size"))
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

                if($input->name != $product->name && $product->name != NULL)
                {
                    $updateable = 1;
                    array_push($updatedFields,['name'=>$input->name]);
                    $input->name        = $product->name;
                }
                if($input->description != $product->description && $product->description != NULL)
                {
                    $updateable = 1;
                    array_push($updatedFields,['description' => true]);
                    $input->description   = $product->description;
                }
                if($input->sku != $product->sku)
                {
                    $updateable = 1;
                    array_push($updatedFields,['sku'=>$input->sku]);
                    $input->sku             = $product->sku;
                } 
                if( $input->stock_quantity  != $stockQty)
                {
                    $updateable             = 1;
                    array_push($updatedFields,['stock_quantity'=>$input->stock_quantity]);
                    $input->stock_quantity  = $stockQty;
                    $input->stock_status    = $product->stock_status == 'instock' ? 1 : 0;
                }
    
                $productStatus = 0;
                if(strtolower($product->status) == "draft" ||
                    strtolower($product->status) == "pending"  ||
                    strtolower($product->status) == "private" 
                )
                {
                    $productStatus      = 0;
                }else{
                    $productStatus      = 1;
                }
                
                if($productStatus != 1)
                {
                    $updateable             = 1;
                    array_push($updatedFields,['status'=>$input->status]);
                    $input->status          = 0;
                    $input->stock_status    = 0;
                    $input->stock_quantity  = 0;
                }
                if($input->slug != $product->slug)
                {
                    $updateable = 1;
                    array_push($updatedFields,['slug'=>$input->slug]);
                    $input->slug            = $product->slug;
                }
                //photo ,thumbnail
                $prductImage     = "";
                $prductThumbnail = "";
                if(count($product->images) > 0)
                {
                    $prductImage        = $product->images[0]->src;
                    $prductThumbnail    = $product->images[0]->src;
                }
                //photo 
                if($input->photo != $prductImage && $prductImage != '')
                {
                    $updateable = 1;
                    array_push($updatedFields,['photo'=>$input->photo]);
                    $input->photo            = $prductImage;
                }
                //thumbnail
                if($input->photo != $prductThumbnail && $prductThumbnail != '')
                {
                    $updateable = 1;
                    array_push($updatedFields,['thumbnail'=>$input->thumbnail]);
                    $input->thumbnail            = $prductThumbnail;
                }
               

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
                    $input->brand_id = $input->brand_id;
                }
                //for brand

                
               
                /*
                |---------------------------------------------
                | updateable
                |--------------------------------------------
                */
                    if($updateable == 1)
                    {
                        $input->updateable      = $updateable;
                        $input->updated_fields  = json_encode($updatedFields);
                    }
                /*
                |---------------------------------------------
                | updateable
                |--------------------------------------------
                */
                // Save Data
                $input->save();

                /*
                |------------------------------------------
                | product gallery
                | not updating....... 25-12-2021
                |------------------------------------------
                */
                    /* $galleries = Gallery::where('product_id',$input->id)->get();
                    foreach($galleries as $gal)
                    {
                        //$gal->deleted_at = date('Y-m-d');
                        $gal->save();
                    }  
                    if(count($product->images) > 0)
                    {
                        $iii = 1;
                        foreach($product->images as  $image)
                        {
                            if($iii <= 9)
                            {
                                $gallery = new Gallery;
                                $gallery->photo = $image->src;
                                $gallery->product_id = $input->id;
                                $gallery->deleted_at = NULL;
                                $gallery->save();
                                $iii++;
                            }
                        }
                    }  */
                   
                /*
                |------------------------------------------
                | product gallery
                |------------------------------------------
                */



                /*
                |------------------------------------------
                | when product variation is exists
                | all stock make zero (0)
                |------------------------------------------
                */
                    $productType = 'normal';
                    /* $productVariantsExist = ProductVariant::where('product_id',$input->id)->get();
                    foreach($productVariantsExist as $exist)
                    {
                        $exist->stock_quantity = 0;
                        $exist->save();
                    } */  
                /*
                |------------------------------------------
                | when product variation is exists
                | all stock make zero (0)
                |------------------------------------------
                */
                

                $updateableFromVariant = 0;
                /*
                |------------------------------------------
                | when product variation  found
                |------------------------------------------
                */
                    $productVariations  = []; 
                    $productVariations  = $this->getProductVariations($product->id);
                    if(count($productVariations) > 0)
                    {
                        foreach ($productVariations as $variationValue)
                        {
                            /*
                            |-------------------------------------------
                            | arrtibutes Detial (arrtibutesDetial)
                            |-------------------------------------------
                            */
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
                                $variationDimensionJson         = json_encode($variationDimensionArray);

                                $variationAttributesEncodedData = json_encode($variationValue['attributes']);
                            /*
                            |-------------------------------------------
                            | arrtibutes Detial (arrtibutesDetial)
                            |-------------------------------------------
                            */


                            // if variant is exist, then update variant information
                            if($productVariant = ProductVariant::where('ds_variation_id',$variationValue['id'])->first())
                            { 
                                if($productVariant->current_price != floatval($variationValue['price']))
                                {
                                    $updateableFromVariant = 1;
                                    array_push($updatedFields,['variant'=>["v_current_price" => $productVariant->current_price]]);
                                    $productVariant->current_price   = floatval($variationValue['price']);
                                } 
                                if($productVariant->regular_price   != floatval($variationValue['regular_price']))
                                {
                                    $updateableFromVariant = 1;
                                    array_push($updatedFields,['variant'=>["v_regular_price" => $productVariant->regular_price]]);
                                    $productVariant->regular_price   = floatval($variationValue['regular_price']);
                                } 
                                if($productVariant->sale_price != floatval($variationValue['sale_price']))
                                {
                                    $updateableFromVariant           = 1;
                                    array_push($updatedFields,['variant'=>["v_sale_price" => $productVariant->sale_price]]);
                                    $productVariant->sale_price      = floatval($variationValue['sale_price']);
                                } 

                                if( $productVariant->stock_quantity  != $variationValue['stock_quantity'])
                                {
                                    $updateableFromVariant           = 1;
                                    array_push($updatedFields,['variant'=> ["v_stock_quantity" => $productVariant->stock_quantity]]);
                                    $productVariant->stock_quantity  = $variationValue['stock_quantity'];
                                    $productVariant->stock_status    = $variationValue['stock_quantity'] > 0 ? 1 : 0;
                                }

                                $variationStockStatus               = 0;
                                if($variationValue['stock_status'] == 'instock')
                                {
                                    $variationStockStatus           = 1;
                                }else{
                                    $variationStockStatus           = 0;
                                }

                                if( $productVariant->stock_status  != $variationStockStatus)
                                {
                                    $updateableFromVariant          = 1;
                                    array_push($updatedFields,['variant'=>["v_stock_status" => $productVariant->stock_status]]);
                                    $productVariant->stock_status   = $variationStockStatus;
                                }

                                $oldVarAttribute = $productVariant->attributes;
                                $productVariant->attributes = $variationAttributesEncodedData;
                                if($productVariant->isDirty('attributes'))
                                {
                                    $updateableFromVariant = 1;
                                    array_push($updatedFields,['variant_id_'.$variationValue['id']=>["v_attributes" => $oldVarAttribute]]);
                                    //$productVariant->attributes = json_encode($variationAttributes);
                                }else{
                                    $productVariant->attributes = $oldVarAttribute;
                                }

                                $productVariant->dimension          = $variationDimensionJson;
                                $productVariant->variation_photo    = $variationValue['photo'] ? $variationValue['photo']->src : NULL;
                                $productVariant->save();
                            }
                            else{ // if variant is not exist, then insert new variant, 
                                    //though product is same
                                    $updateableFromVariant = 1;
                                    array_push($updatedFields,['variant'=>["v_add_new_variant" =>true]]);
                                $productVariantNew = new ProductVariant();
                                $productVariantNew->product_id      = $input->id;
                                $productVariantNew->current_price   = $input->current_price;
                                $productVariantNew->regular_price   = $input->regular_price;
                                $productVariantNew->sale_price      = $input->sale_price;
                                $productVariantNew->stock_quantity  = $input->stock_quantity;
                                $productVariantNew->attributes      = $variationAttributesEncodedData;
                                $productVariantNew->variation_photo = $input->photo;
                                $productVariantNew->save();
                            }
                        }
                        $productType = 'variant';
                    }
                    // when variation not found
                    else
                    { 
                        $productAttributesEncodedData = json_encode($productAttributes);
                        if($input->product_type == 'normal')
                        {
                            $productVariant = ProductVariant::where('product_id',$input->id)->first();
                            $productVariant->product_id         = $input->id;
                            $productVariant->current_price      = $input->current_price;
                            $productVariant->regular_price      = $input->regular_price;
                            $productVariant->sale_price         = $input->sale_price;
                            $productVariant->stock_quantity     = $input->stock_quantity;
                            $productVariant->attributes         = $productAttributesEncodedData;
                            $productVariant->variation_photo    = $input->photo;
                            $productVariant->save();
                        }else{
                            $updateableFromVariant = 1;
                            array_push($updatedFields,['variant'=>["v_add_new_variant" =>2]]);
                            $newProductVariant = new ProductVariant();
                            $newProductVariant->product_id      = $input->id;
                            $newProductVariant->current_price   = $input->current_price;
                            $newProductVariant->regular_price   = $input->regular_price;
                            $newProductVariant->sale_price      = $input->sale_price;
                            $newProductVariant->stock_quantity  = $input->stock_quantity;
                            $newProductVariant->attributes      = $productAttributesEncodedData;
                            $newProductVariant->variation_photo = $input->photo;
                            $newProductVariant->save();
                        }
                        $productType = 'normal';
                    }
                /*
                |------------------------------------------
                | when product variation  found
                |------------------------------------------
                | product variant table
                */
                if($updateableFromVariant == 1)
                {
                    $input->updateable      = $updateableFromVariant;
                    $input->updated_fields  = json_encode($updatedFields);
                }
                $input->product_type = $productType;
                $input->save();
                $input->isDirty(); // false
            }//end if. if product  exist

            //for progress bar
            if($updatingFrom != "queue")
            {
                $pathName = public_path('temp/dropship_aliexpress/');
                $previousInsertedRow    = file_get_contents($pathName.'/insert_row_for_update.txt');
                $previousTotalRow       = file_get_contents($pathName.'/total_row_for_update.txt');

                $currentInsetingRow = intval($previousInsertedRow) + 1;
                $remainingTotalRow  = (intval($previousTotalRow) - intval($currentInsetingRow));
                file_put_contents($pathName.'/insert_row_for_update.txt',  $currentInsetingRow);
                file_put_contents($pathName.'/remaining_insert_row_for_update.txt',$remainingTotalRow);
            }
            //for progress bar
        return true;
    }

    /**
     * get Product Variation 
     * $product_id
     */
    public function getProductVariations($product_id)
    {
        $this->customerKey = restapiCustomerKeyForWoocommerce();
        $this->customerSecret = restapiCustomerSecretForWoocommerce();
        //$product_id = 8787;
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
                "id"            => $data->id,
                "price"         => $data->price,//$data->price,
                "regular_price" => $data->regular_price,
                "sale_price"    => $data->sale_price,//$data->sale_price,
                "stock_quantity" => $data->stock_quantity,
                "size"          => $attrSize,
                "photo"         => $data->image,        //variation_photo
                "stock_status"  => $data->stock_status, //variation_stock_status
                "dimension"     => $data->dimensions,    //variation_dimension
                'attributes'    => $allAttributes
            ];  
        }
        return $variations;
    }


    /**
     * ebaskat extra product charge for aliexpress
     * this charge is added with aliexpress incoming price
     * 
     * @param [product_from] $price
     */
    private function ebaskatExtraProductChargeForAliexpress($price)
    {
        return addExtraChargeForEbaskat_hd($price);
        /* if($price <= 50 )
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
        } */
        //return 2;
    }
        

    /**
     * get shipping charge
     * calculation shipping charge based on price
     */
    private function getShippingCharge($price)
    {
        // if($price > 0 && $price <= 10)
        // {
        //     return 4;
        // }
        // else if($price > 10 && $price <= 20)
        // {
        //     return 4.95;
        // }
        // else if($price > 20 && $price <= 30)
        // {
        //     return 7.95;
        // } 
        // else if($price > 30 && $price <= 40)
        // {
        //     return 8.95;
        // }
        // else if($price > 40 && $price <= 500)
        // {
        //     return 10.95;
        // }
        // else if($price > 500)
        // {
        //     return 10.95;
        // }
        // else{
        //     return 0;
        // }

        return 2;
    }

    
    
    /**
     * THIS METHOD IS NOT USING WHEN PRODUCT UPDATE
     * 
     * get all category when product import from aliexpress
     * get category, sub-category, child-category single data
     * though this method is not using for update product method
     * 
     * @param [product_from] $slug
     */
    public function getAllCategory($slug)
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
    }//this method is not using in the update product mehtod (page wise update)




    /*
    |------------------------------------------------------------------------------
    |------------------------------------------------------------------------------
    | Update products by queue
    |
    |----------------------------------------------------
    |
    |
    */
        /*
        | page number process for job class
        */
        public function aliexpressAllProductUpdateByQueue($lastPageNo)
        {
            $lastPage = 0;
            if($lastPageNo)
            {
                $lastPage = $lastPageNo;
            }else{
                $this->updateAllExistingProductProcessByQueue();
                return true;
                //$lastPage = $this->getTotalPagesNumberWithPublishedAndDraftProductWhenProductUpdateByQueue();
            }
           //$lastPage = $this->getTotalPagesNumberWithPublishedAndDraftProductWhenProductUpdateByQueue();
            if($lastPage)
            {
                for ($i=1; $i <= $lastPage; $i++) 
                { 
                    AliexpressProductUpdateByJob::dispatch($i)->onQueue('ali-pro-updt1');
                }
            }
            return true;	
        }


            
        /*
        |-----------------------------------------------------------------------------
        | product id wise product update by queue from aliexpess : 10-05-2022
        |----------------------------------------------------
        */
            /**
             * update product process by queue
             * process queue in this method
             * 10 products process at a time (per jobs)  
             */
            public function updateAllExistingProductProcessByQueue()
            {
                $daProductIds =  Product::where('product_from',"Aliexpress")->select('ds_product_id')
                                        ->whereNull('deleted_at')
                                        ->pluck('ds_product_id')
                                        ->toArray();
                $chunks = array_chunk($daProductIds,1000);//->where('category_id',18)//->where('user_id',1)//
                foreach($chunks as $chunkData)
                {
                    AliexpressProductUpdateByQueueJob::dispatch($chunkData)->onQueue('ali-pro-updt1');//->delay(now()->addSeconds(25))//->delay(now()->addSeconds(10))//->delay(now()->addMinutes(1))
                }                                                    //bigbuy-product-update1
                return true;	
            }


            /*
            | update product by product id
            | aliexpress product update by product id
            */
            public function updateExistingAllProductByDsProductId()
            {
                $customerKey = restapiCustomerKeyForWoocommerce();
                $customerSecret = restapiCustomerSecretForWoocommerce();
                $singleProductResponse = curl_init();
                $singleProductResponseUrl = "https://obaskat.com/wp-json/wc/v3/products/{$this->dsProductId}?consumer_key={$customerKey}&consumer_secret={$customerSecret}";
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
                    $this->changeProductStatusWhenProductAPINotFound($this->dsProductId);
                }
                else if(isset($product->id) && !empty($product->id)){
                    $this->updateProductDetails($product,'queue');
                }else{
                    $this->changeProductStatusWhenProductAPINotFound($this->dsProductId);
                    //"not found";
                }
                return true;
            }   
            /*
            | update product by product id
            | aliexpress product update by product id
            */

            /* 
            * product status change to 0 when product not found
            * update / check by Aliexpress product id 
            */
            public function changeProductStatusWhenProductAPINotFound($dsProductId)
            {
                if ($input = Product::where('product_from','Aliexpress')->where('ds_product_id',$dsProductId)->first())
                {
                    $input->status  = $input->status;
                    $input->stock   = 1;//$input->status;
                    $input->save();
                    return true;
                }
                return true;
            }
            /* 
            * product status change to 0 when product not found
            * update / check by Aliexpress product id 
            */

        /*
        |----------------------------------------------------
        | product id wise product update by queue from aliexpess : 10-05-2022
        |------------------------------------------------------------------------------
        */




        /*
        | updating data by updateProductDetails($datas) this method
        */
        public function pageWiseUpdateProductByQueue()
        {
            $this->customerKey = restapiCustomerKeyForWoocommerce();
            $this->customerSecret = restapiCustomerSecretForWoocommerce();
            //$this->currentPage = $this->updatePageNo;
            $this->perPage = 20;
            $curl = curl_init();
            $url = "https://obaskat.com/wp-json/wc/v3/products?page={$this->updatePageNo}&per_page={$this->perPage}&consumer_key={$this->customerKey}&consumer_secret={$this->customerSecret}";
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
                $chunkData = array_chunk($datas,10,true);
                foreach($chunkData as $products)
                {
                    foreach($products as  $product)
                    {
                        $this->updateProductDetails($product,'queue');
                    }
                }
            }
            return true;
        }


        /*
        | get total product from obaskat (woocommerce) 
        | 1. published product, 2. Draft
        | use here reports api/Products api
        */
        public function getTotalPagesNumberWithPublishedAndDraftProductWhenProductUpdateByQueue()
        {
            $curl = curl_init();
            $this->customerKey      = restapiCustomerKeyForWoocommerce();
            $this->customerSecret   = restapiCustomerSecretForWoocommerce(); //status=draft&
            
            $url = "https://obaskat.com/wp-json/wc/v3/reports/products/totals?consumer_key={$this->customerKey}&consumer_secret={$this->customerSecret}";
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
            return   $this->countTotalPageWhenProductUpdateByQueue($totalPages);
        }

        /*
        | get total product from obaskat (woocommerce) 
        | 1. published product, 2. Draft
        | use product api in this method
        */
        public function countTotalPageWhenProductUpdateByQueue($totalPages)
        {
            ini_set('max_execution_time', 28800);
            $finalPages     = $totalPages;
            $breakNow = 0;
            $newRow = 1;
            for ($totalPages; $newRow != 0; $totalPages++) 
            { 
                $pageNo = $totalPages;
                $this->per_page = 20;
                //--------------------------------------
                    $this->customerKey = restapiCustomerKeyForWoocommerce();
                    $this->customerSecret = restapiCustomerSecretForWoocommerce();
                    $curl = curl_init();
                    $url = "https://obaskat.com/wp-json/wc/v3/products?page={$pageNo}&per_page={$this->per_page}&consumer_key={$this->customerKey}&consumer_secret={$this->customerSecret}";
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
                }//end else
                $finalPages = $pageNo;
            }//end for
            return ($finalPages + 1); 
        }   
    /*
    |----------------------------------------------------
    | Update products by queue
    |------------------------------------------------------------------------------
    */



    /*
    |-------------------------------------------------------------------------
    | When discount is running 
    | product price : according to previous_precie_condition fields
    |----------------------------------------------------
    |
    */
        /**
         * previous price condition function
         * get price new value after all calculation (when discount is running)
         * when previous_price_condition is not null
         * @param [product_from] $price
         * @param [product_from] $decodedConditions
         */
        private function previousPriceConditon($price,$decodedConditions)
        {
            $decodedAmount  = $decodedConditions['amount'];
            $calculatedType = $decodedConditions['calculated_type'];//Minus/Plus
            $calculatedBy   = $decodedConditions['calculated_by'];//Percentage/Fixed

            $priceWithExtraCharge   = (($this->ebaskatExtraProductChargeForAliexpress($price)) + ($price));
            $calculatedValue        = $this->getConditionalAmountWithCalculatedBy($priceWithExtraCharge,$decodedAmount,$calculatedBy);
            return $this->newPriceAccordingToPreviousPriceConditon($priceWithExtraCharge,$calculatedType,$calculatedValue);
        }

        /**
         * new price according to previous price condtion function
         * 
         * @param [product_from] $productPrice
         * @param [product_from] $calculatedType
         * @param [product_from] $calculatedValue
         */
        private function newPriceAccordingToPreviousPriceConditon($productPrice,$calculatedType,$calculatedValue)
        {
            $price = $productPrice;
            if($calculatedType == 'Plus')
            {
                $price = $productPrice +  $calculatedValue;
            }else{
                $price = $productPrice -  $calculatedValue;
            }
            return $price;
        }

        /**
         * get Conditional Amount With Calculated By function
         *
         * @param [product_from] $productPrice
         * @param [product_from] $conditionalAmount
         * @param [product_from] $calculatedBy
         */
        private function getConditionalAmountWithCalculatedBy($productPrice,$conditionalAmount,$calculatedBy)
        {
            $amount = $conditionalAmount;
            if($calculatedBy == 'Percentage')
            {
                $amount = (($productPrice * $conditionalAmount) / 100 );
            }
            return $amount;
        }
    /*
    |--------------------------------------
    | When discount is running 
    | product price : according to previous_precie_condition fields
    |----------------------------------------------------
    */


    /*
    |--------------------------------------
    | Single Product Update by sku 
    |----------------------------------------------------
    */
        protected function singleProductUpdateByDaProductId($dsProductId)
        {
            $customerKey = restapiCustomerKeyForWoocommerce();
            $customerSecret = restapiCustomerSecretForWoocommerce();
            $singleProductResponse = curl_init();
            $singleProductResponseUrl = "https://obaskat.com/wp-json/wc/v3/products/{$dsProductId}?consumer_key={$customerKey}&consumer_secret={$customerSecret}";
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
                $this->changeProductStatusWhenProductAPINotFound($dsProductId);
            }
            else if(isset($product->id) && !empty($product->id)){
                $this->updateProductDetails($product,'queue');
            }else{
                $this->changeProductStatusWhenProductAPINotFound($dsProductId);
                //"not found";
            }
            return true;
        }

    /*
    |--------------------------------------
    | Single Product Update by sku 
    |----------------------------------------------------
    */





}
