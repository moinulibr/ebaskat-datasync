<?php

namespace App\Traits;

use App\Models\Order;
use App\Models\OrderPackage;
use App\Models\OrderProduct;
use App\Models\OrderTrack;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Traits\BigbuyShippingDetails;
use App\Traits\BigbuyOrderStatus;
/**
 *
 */
trait BigbuyOrder
{
    use BigbuyShippingDetails, BigbuyOrderStatus;

    public $orderId;
    public $bulkOrderIds;
    public $merchantId;

    protected $customerKey;
    protected $customerSecret;
    /**
     * contain order details
     */
    public $orderDetails;

    public $paymentMethodTitle;

    /**
     * ebakset product and quantity of this order
     */
    public $orderProductsQuantities;


    /**
     *product and quantity of this order
     */
    public $lineItems;

    /**
     * da_product_id from products table against of product_id
     */
    public  $dropshipProductIds;


    /**
     *containt product_id from order_products table
     */
    public $orderProductIds;

    /**
     * Order Package ids
     */
    public  $orderPackageIds;


    /**
     * Contain order data after order placed to obasket
     */
    protected $orderedDataAfterOrderPlacedToBigbuy;

    /**
     * cart field for getting cart details
     */
    protected $cartField;

    /**
     * update unique field in the order_packages table
     * for bigbuy order create;
     */
    private $internalReference;
    private $singleProductSku;


    
    
    /**
     *containt id (primay key ) from order_products table (single)  
     */
    protected $orderProductId;

    /**
     * Order Package id (single) (primay key from order_packages table) 
     */
    protected  $orderPackageId;

    /**
     * containt product id from order_products table (single)
     */
    protected  $productId;

    /**
     * containt ebaskat order number string (single)
     */
    protected $ebaskatOrderNumber;

    /**
     * containt ebaskat order number string (single)
     */
    protected $ebaskatOrderPackageNumber;

    /**
     * containt carrier name 
     */
    private $carrierNameArrayFormated;


    
    /*
    |------------------------------------------------------------------------------------------------------------------
    |            -----------Package wise order place (all package's order products) -----------
    |------------------------------------------------------------------------------------------------------------------
    */
        /**
         * Order process to bigbuy
         */
        public function processOrderToBigbuy()
        {
            $this->orderPackageIds  = OrderPackage::where('order_id',$this->orderId)
                                    ->where('merchant_id',$this->merchantId)
                                    ->pluck('id')
                                    ->toArray();    

            $orderProd  = OrderProduct::query()->whereIn('order_package_id',$this->orderPackageIds);

            $this->orderProductsQuantities  = $orderProd->select('order_package_number','product_id','product_quantity','cart')->get();
            $this->orderProductIds          = $orderProd->pluck('product_id')->toArray();
        
            $this->dropshipProductSkus      = $this->getDropshipProductSkuFromProductsTable();
            $this->getOrderDetails();
            $this->ebaskatOrderNumber       = $this->orderDetails ? $this->orderDetails->order_number : "XYZ";
            $this->makeInternalReferenceForPackageWiseBigbuyOrder();

            //return $this->products();

            $carrierResponse =  $this->carriers();
            if(is_array($carrierResponse) && array_key_exists('carrier_status',$carrierResponse))
            {
                if($carrierResponse['carrier_message_status'] == 'error')
                {
                    $data['msg']    = $carrierResponse['carrier_message'];
                    $data['status'] = $carrierResponse['carrier_message_status'];
                    return $data;
                }
            }
            $this->carrierNameArrayFormated             = $carrierResponse;
                
            $this->orderedDataAfterOrderPlacedToBigbuy  =  $this->createOrderForBigbuy();

            $result =  json_decode($this->orderedDataAfterOrderPlacedToBigbuy,true);
            if(!isset($result) || !is_array($result) || $result  == NULL)
            {
                $data['msg']    = 'API Not Responsed!';
                $data['status'] = "error";
                return $data;
            }
            else if((is_array($result) && array_key_exists('code',$result))
                || (is_array($result) && in_array('code',$result))
            )
            {
                if($result['code']  != 201)
                {
                    return $this->statusCodeWiseMessage($result['code']);
                }
            }
            else if((is_array($result) && array_key_exists('order_id',$result))
                || (is_array($result) && in_array('order_id',$result))
            )
            {
                $bigbuyOrderId      = $result['order_id']; 
                $this->updateInternalReferenceAfterPackageWiseOrderPlacementToBigbuy();//update customerReference in the order_pacakges table// alix_order_data => json format
                if($bigbuyOrderId){
                    $this->updateBigbuyOrderIdAfterPackageWiseOrderPlacementToBigbuy($bigbuyOrderId);
                }
                //get bigbuy order id by internalReference  
                $data['msg']    = 'Order Placed to Bigbuy Successfully.';
                $data['status'] = "success";
                return $data;
            }
            $dataResult['msg']      = 'Order not placed to the bigbuy!';
            $dataResult['status']   = "error";
            return $dataResult;
        }

        private function makeInternalReferenceForPackageWiseBigbuyOrder()
        {
            $orderPackage = OrderPackage::where('order_id',$this->orderId)
                        ->where('merchant_id',$this->merchantId)
                        ->select('order_package_number')
                        ->first();
            $this->internalReference = $orderPackage->order_package_number;
            $this->internalReference = $orderPackage->order_package_number .'-'.$orderPackage->order_package_number;
            return $orderPackage;
        }

        /**
        * update customerReference in the order_pacakges table
        * alix_order_data => json format 
        */
        private function updateInternalReferenceAfterPackageWiseOrderPlacementToBigbuy()
        {
            $data = [
                "order_type" => "Bigbuy",
                "internalReference" => $this->internalReference,
            ];
            OrderPackage::where('order_id',$this->orderId)
                ->where('merchant_id',$this->merchantId)
                ->update([
                    'alix_order_data' => json_encode($data)
                ]);
            return $this->internalReference;
        }

        /**
         * update bigbuy order number by Internal Reference
         * @param [type] $bigbuyOrderId
         */
        private function updateBigbuyOrderIdAfterPackageWiseOrderPlacementToBigbuy($bigbuyOrderId)
        {
            $orderPackage = OrderPackage::where('order_id',$this->orderId)
                            ->where('merchant_id',$this->merchantId)
                            ->first();
            $orderPackage->alix_order_id    =  $bigbuyOrderId;
            $orderPackage->order_place_from =  1;
            $orderPackage->save();
            
            $data = ['order_type'=>'Bigbuy','' => $this->internalReference];
            OrderProduct::where('order_package_id',$orderPackage->id)
                    ->update([
                        'ds_order_data' => json_encode($data),
                        'ds_order_no'   => $bigbuyOrderId
                    ]);
            return true;
        }

            /*
            |----------------------------------------------------------------------
            |    -----package wise and bulk order :- order place method------
            |----------------------------------------------------------------------
            */
                /**
                 * crate order to bigbuy by php CURL
                 */
                public function createOrderForBigbuy()
                {
                    $customerKey    = bigbuyApiKey_hd();
                    $apiUrl         = bigbuyApiUrl_hd();
                    $headers = [
                        "Content-Type:application/json",
                        "Authorization: Bearer ".$customerKey
                    ];
                    
                    $url = "{$apiUrl}/rest/order/create.json";
                    $sendData = [
                        "order" => [
                            "internalReference" => $this->internalReference, //Str::random(6),
                            "language"=> "en",
                            "paymentMethod"=>  "moneybox",
                            "carriers"=>//
                                //[['name' => 'gls']]
                            //$this->carriers()
                            $this->carrierNameArrayFormated
                            ,
                            "shippingAddress"=>//
                                $this->shippingAddress()
                            ,
                            "products"=>//
                                $this->products() //order products and quantity
                            ,
                        ]
                    ];
                    $data	= json_encode($sendData);
        
                    $ch = curl_init($url);
                    //curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
                    // Array Parameter Data //

                    // pass encoded JSON string to the POST fields //
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                    curl_setopt($ch, CURLOPT_HEADER, true); // To retrieve from (parse the location) header.
                    curl_setopt($ch, CURLOPT_NOBODY, 0);    // 0=we need body, 1 = we don't need body , 
                    
                    // set return type json //
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    // execute request //
                    $result = curl_exec($ch);

                    // After curl_exec
                    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                    curl_close($ch);
                    $header = substr($result, 0, $header_size);
                    $body   = substr($result, $header_size);
                    return $body;
                }
            /*
            |----------------------------------------------------------------------
            |    -----package wise and bulk order :- order place method------
            |----------------------------------------------------------------------
            */

    /*
    |------------------------------------------------------------------------------------------------------------------
    |            -----------Package wise order place (all package's order products) -----------
    |------------------------------------------------------------------------------------------------------------------
    */




    /*
    |------------------------------------------------------------------------------------------------------------------
    |                             -----------single order place-----------
    |------------------------------------------------------------------------------------------------------------------
    */
        /**
         * single Order process to bigbuy
         */
        public function singleOrderProcessToBigbuy()
        {
            $orderProd  = OrderProduct::query()->where('id',$this->orderProductId);

            $this->orderProductsQuantities  = $orderProd->select('product_id','product_quantity','cart')->get();
            $this->orderProductIds          = $orderProd->pluck('product_id')->toArray();
            
            $this->dropshipProductSkus      = $this->getDropshipProductSkuFromProductsTable();
            $this->getOrderDetails();
            $this->ebaskatOrderNumber       = $this->orderDetails ? $this->orderDetails->order_number : "XYZ";
            
            //return $this->products();
            //return $this->carriers();
            
            $carrierResponse =  $this->carriers();
            if(is_array($carrierResponse) && array_key_exists('carrier_status',$carrierResponse))
            {
                if($carrierResponse['carrier_message_status'] == 'error')
                {
                    $carrierData['msg']    = $carrierResponse['carrier_message'];
                    $carrierData['status'] = $carrierResponse['carrier_message_status'];
                    return $carrierData;
                }
            }
            $this->makeInternalReferenceForBigbuySingleOrder();
            
            $this->carrierNameArrayFormated             = $carrierResponse;
            $this->orderedDataAfterOrderPlacedToBigbuy  = $this->createSingleOrderToBigbuy();

            $result =  json_decode($this->orderedDataAfterOrderPlacedToBigbuy,true);
            if(!isset($result) || !is_array($result) || $result  == NULL)
            {
                $apiNotResponseData['msg']      = 'API Not Responsed!';
                $apiNotResponseData['status']   = "error";
                return $apiNotResponseData;
            }
            //return $result;
            else if((is_array($result) && array_key_exists('code',$result))
                || (is_array($result) && in_array('code',$result))
            )
            {
                if($result['code']  != 201)
                {
                    return $this->statusCodeWiseMessage($result['code']);
                }
            }
            else if((is_array($result) && array_key_exists('order_id',$result))
                || (is_array($result) && in_array('order_id',$result))
            )
            {
                $bigbuyOrderId = $result['order_id']; 
                $this->updateInternalReferenceWhenSingleOrderPlace();//update  in the order_products table // ds_order_data => json format
                if($bigbuyOrderId){
                    //if order created successfully,then update bigbuy order id in the order_products table
                    $this->updateBigbuyOrderIdWhenSingleOrderPlacedToBigbuy($bigbuyOrderId);
                }  
                $successData['msg'] = 'Order Placed to Bigbuy Successfully.';
                $successData['status'] = "success";
                return $successData;
            }
            $dataResult['msg'] = 'Order not placed to the bigbuy!';
            $dataResult['status'] = "error";
            return $dataResult;
        }


        /**
        * make internal reference 
        * by order package number 
        */
        private function makeInternalReferenceForBigbuySingleOrder()
        {
            $updatePackage = OrderPackage::where('order_id',$this->orderId)
                            ->where('merchant_id',$this->merchantId)
                            ->select('order_package_number')
                            ->first();
            $this->internalReference = $updatePackage->order_package_number;
            if(isset($this->singleProductSku))
            {
                $this->internalReference = $this->internalReference .'-'. $this->singleProductSku;
            }
            else{
                $this->internalReference = $this->internalReference .'-'. mt_rand(0,999);
            }
            return $updatePackage;
        }

        /**
         * crate order to bigbuy 
         */
        public function createSingleOrderToBigbuy()
        {
            $customerKey    = bigbuyApiKey_hd();
            $apiUrl         = bigbuyApiUrl_hd();
            $headers = [
                "Content-Type:application/json",
                "Authorization: Bearer ".$customerKey
            ];
            
            $url = "{$apiUrl}/rest/order/create.json";
            $sendData = [
                "order" => [
                    "internalReference" => $this->internalReference, //50,
                    "language"=> "en",
                    "paymentMethod"=>  "moneybox",
                    "carriers"=>//
                        //[['name' => 'gls']]
                        //$this->carriers()
                        $this->carrierNameArrayFormated
                    ,
                    "shippingAddress"=>//
                        $this->shippingAddress()
                    ,
                    "products"=>//
                        $this->products() //order products and quantity
                    ,
                ]
            ];
            $data	= json_encode($sendData);
        
            $ch = curl_init($url);
            //curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
            // Array Parameter Data //

            // pass encoded JSON string to the POST fields //
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            curl_setopt($ch, CURLOPT_HEADER, true); // To retrieve from (parse the location) header.
            curl_setopt($ch, CURLOPT_NOBODY, 0);    // 0=we need body, 1 = we don't need body , 
            
            // set return type json //
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // execute request //
            $result = curl_exec($ch);

           // After curl_exec
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            curl_close($ch);
            $header = substr($result, 0, $header_size);
            $body   = substr($result, $header_size);
            return $body;
            /* echo $header;
            echo "<br/> end header; <br/> ";
            echo $body;
            echo "<br/> end body. <br/>";
            return $body; */
            // close cURL resource //
            //return json_decode($result) ?? $e; 
        }

        
        /**
        * update updateInternalReferenceWhenSingleOrderPlace() in the order_products table
        * ds_order_data => json format 
        */
        private function updateInternalReferenceWhenSingleOrderPlace()
        {
            $data = [
                "order_type" => "Bigbuy",
                "internalReference" => $this->internalReference,
            ];
            OrderProduct::where('id',$this->orderProductId)->update([
                'ds_order_data' => json_encode($data)
            ]);
            return $this->internalReference;
        }

        /**
        * update ds_order_no fields in the order_products table
        * by the bigbuy order no 
        */
        private function updateBigbuyOrderIdWhenSingleOrderPlacedToBigbuy($bigbuyOrderId)
        {
            OrderProduct::where('id',$this->orderProductId)->select('id','ds_order_no')->update([
                'ds_order_no' => $bigbuyOrderId
            ]);

            OrderPackage::where('order_id',$this->orderId)
            ->where('merchant_id',$this->merchantId)
            ->select('order_place_from','order_id','order_place_from')
            ->update([ 'order_place_from' => 2]);
            return true;
        }
    
    /*
    |------------------------------------------------------------------------------------------------------------------
    |                            -----------single order place End-----------
    |------------------------------------------------------------------------------------------------------------------
    */



    /*
    |------------------------------------------------------------------------------------------------------------------
    |      -----------others all necessary method for placing all order-----------
    |------------------------------------------------------------------------------------------------------------------
    */
        /**
         * order create response Status: 
         * Status code wise message. 
         */
        public function statusCodeWiseMessage($statusCode)
        {
            $data['msg'] = "";
            $data['status'] = "";
            if($statusCode == 201)
            {
                $data['msg'] = 'Order Placed to Bigbuy Successfully.';
                $data['status'] = "success";
            }
            else if($statusCode == 'ER005')
            {
                $data['msg'] = 'Not enough money in the money box.';
                $data['status'] = "error";
            }
            else if($statusCode == 'ER001')
            {
                $data['msg'] = 'Products not found';
                $data['status'] = "error";
            }
            else if($statusCode == 'ER002')
            {
                $data['msg'] = 'Incorrect product reference. This product has attributes.';
                $data['status'] = "error";
            }
            else if($statusCode == 'ER003')
            {
                $data['msg'] = 'Products have no stock.';
                $data['status'] = "error";
            }
            else if($statusCode == 'ER004')
            {
                $data['msg'] = 'Zip code format incorrect.';
                $data['status'] = "error";
            }
            else if($statusCode == 'ER007')
            {
                $data['msg'] = 'Minimum order not reached.';
                $data['status'] = "error";
            }
            else if($statusCode == 'ER008')
            {
                $data['msg'] = 'Order internal reference already exists for the order.';
                $data['status'] = "error";
            }
            else if($statusCode == 'ER009')
            {
                $data['msg'] = 'Could not place order. Please try again later.';
                $data['status'] = "error";
            }
            else if($statusCode == 'ER0010')
            {
                $data['msg'] = 'No carriers found.';
                $data['status'] = "error";
            }
            else if($statusCode == 'ER011')
            {
                $data['msg'] = 'Payment not valid';
                $data['status'] = "error";
            }
            else if($statusCode == 'ER012')
            {
                $data['msg'] = 'The total amount does not exceed the minimum amount for transfer orders Dropshipping';
                $data['status'] = "error";
            }
            else if($statusCode == 'ER013')
            {
                $data['msg'] = 'You can not export to this destination unless you are a Spanish exporter';
                $data['status'] = "error";
            }
            else if($statusCode == 'ER014')
            {
                $data['msg'] = 'Your pack does not allow API orders';
                $data['status'] = "error";
            }
            else if($statusCode == 400)
            {
                $data['msg'] = 'Validation error, please validate this order';
                $data['status'] = "error";
            }
            else if($statusCode == 404)
            {
                $data['msg'] = 'Returned when no carriers/products has been found.';
                $data['status'] = "error";
            }
            else if($statusCode == 409)
            {
                $data['msg'] = 'Returned when there are constrain conflicts.';
                $data['status'] = "error";
            }
            else if($statusCode == 415)
            {
                $data['msg'] = 'Returned on invalid Content-Type header.';
                $data['status'] = "error";
            }
            else if($statusCode == 429)
            {
                $data['msg'] = 'Exceeded requests limits.';
                $data['status'] = "error";
            }else{
                $data['msg'] = 'Something went wrong.';
                $data['status'] = "error";
            }
            return $data;
        }


        /**
         * get dropship product id from products table
         * as da_product_id
         */
        public function getDropshipProductSkuFromProductsTable() : array
        {
            $productSkus = [];
            foreach($this->orderProductIds  as $key=>  $id)
            {
                //$productIds[] = Product::where('id',$id)->pluck('da_product_id')->toArray();
                $productSkus[] = Product::where('id',$id)->pluck('sku')->toArray();
            }
            return Arr::flatten($productSkus);
            //$this->dropshipProductIds = Product::whereIn('id',$this->orderProductIds)->pluck('da_product_id')->toArray();
        }



        /**
         * get order details by order ID
         */
        public function getOrderDetails()
        {
        return  $this->orderDetails = Order::findOrFail($this->orderId);
        }


        
        /**
         * shipping details of this order
         */
        private function shippingAddress() : array
        {
            return [
                "firstName"=>   $this->orderDetails ? $this->orderDetails->shipping_name : "First Name",
                "lastName"=>    $this->orderDetails ? $this->orderDetails->shipping_lastname : $this->orderDetails->shipping_name,//not null
                "address"=>     $this->orderDetails ? $this->orderDetails->shipping_address : "Address",
                "phone" =>      $this->orderDetails->shipping_phone,
                "email" =>      $this->orderDetails->shipping_email,
                "town"=>        $this->orderDetails ? $this->orderDetails->shipping_city : "Town/City",
                
                "postcode"=> $this->orderDetails ? $this->orderDetails->shipping_zip : "Post Code",
                "country"=>  $this->orderDetails ? $this->orderDetails->shipping_country : "Country",
                "comment"=>  bigbuyOrderNote_hd()//$this->orderDetails?$this->orderDetails->order_note:bigbuyOrderNote_hd()//nullable
            ];
        }


        /**
         * minimum carrier cost of this order
        */
        private function carriers()
        {
            $productSkuQuantity = [];
            foreach($this->orderProductsQuantities as $index => $value)
            {
                $this->cartField = $value;
                if($this->getCartAttribute())
                {
                    $productSkuQuantity[$index] = [
                        'sku'         => $this->variationSku(), //$value->product_id
                        'quantity'    => $value->product_quantity ?? 1,
                    ];
                }else{
                    $productSkuQuantity[$index] = [
                        'sku'         => $this->dropshipProductSkus[$index], //$value->product_id
                        'quantity'    => $value->product_quantity ?? 1,
                    ];
                }
            }
            //return $productSkuQuantity;
            //$this->countryShortCode   = "ES";
            //$this->postCode           = "46005";
            $this->productsQtyAndSkus       = $productSkuQuantity;
            $this->countryShortCode         = $this->orderDetails ? $this->orderDetails->shipping_country : "empty";
            $this->postCode                 = $this->orderDetails ? $this->orderDetails->shipping_zip : "empty";
            $shippingResponsedata           = $this->checkShippingDetailsBeforeCreatingOrder();
            

            $shippingStatus = $this->getShippingStatusWiseMessage($shippingResponsedata); 

            $data['carrier_message']        = "";
            $data['carrier_message_status'] = "";
            $data['carrier_status']         = false;
            if($shippingStatus['error_status'] == true)
            {
                $data['carrier_message']        =  $shippingStatus['error_message'];
                $data['carrier_message_status'] = "error";
                $data['carrier_status']         = true;
                return $data;
            }

            $this->shippingOption           = json_decode($shippingResponsedata);
            $minimum                        = $this->getMinimumShippingCostAndCarierNameFromShippingDetails();//array
            return [['name' => strtolower($minimum['carrier_name'])]]; 
            /*  
                return [
                    ["name"=> strtolower("Correos")],//must be strtolower();
                    //["name"=> strtolower("Chrono")] //must be strtolower();
                ]; 
            */
        }

        
        /**
         * order products and quantity
         */
        private function products() : array
        {
            $this->lineItems = [];
            foreach($this->orderProductsQuantities as $index => $value)
            {
                $this->cartField = $value;
                if($this->getCartAttribute())
                {
                    $this->lineItems[$index] = [
                        'reference'         => $this->variationSku(), //$value->product_id
                        'quantity'          => $value->product_quantity ?? NULL,
                        'internalReference' => $this->ebaskatOrderNumber .'_'.$this->variationSku(),
                    ];
                    $this->singleProductSku = $this->variationSku();//when single product wise order place
                }else{
                    $this->lineItems[$index] = [
                        'reference'         => $this->dropshipProductSkus[$index], //$value->product_id
                        'quantity'          => $value->product_quantity ?? NULL,
                        'internalReference' => $this->ebaskatOrderNumber .'_'.$this->dropshipProductSkus[$index],
                    ];
                    $this->singleProductSku = $this->dropshipProductSkus[$index];//when single product wise order place
                }
            }
            return $this->lineItems;
        }




        /**
         * get cart attribute from cart field of order_products table
         */
        private function getCartAttribute()
        {
            $carts = json_decode($this->cartField->cart);
            //return $carts->productVariationId ? $carts->productVariationId : NULL;
            return $carts->productVariationId != null || 
                $carts->productVariationId != "" ? $carts->productVariationId : NULL;
        }

        /**
         * get variation sku from variation_sku field of product_variants table
         */
        private function variationSku()
        {
            $variationId    =   $this->getCartAttribute();
            $variation      =   ProductVariant::where('alix_variation_id',$variationId)->first();
            return $variation?$variation->variation_sku:NULL;
        }
    /*
    |------------------------------------------------------------------------------------------------------------------
    |      -----------others all necessary method for placing all order-----------
    |------------------------------------------------------------------------------------------------------------------
    */



    

    /*
    |------------------------------------------------------------------------------------------------------------------
    |                                           -----------bulk order-----------
    |------------------------------------------------------------------------------------------------------------------
    */
        /**
         * Order process to bigbuy
         */
        public function bulkOrderProcessToBigbuy()
        {
            $orderResults = [];
            foreach($this->bulkOrderIds as $index => $order)
            {
                $this->orderId = $order;

                $this->orderPackageIds  = OrderPackage::where('order_id',$this->orderId)
                                    ->where('merchant_id',$this->merchantId)
                                    ->whereNull('alix_order_id')
                                    ->pluck('id')
                                    ->toArray();

                $orderProd  = OrderProduct::query()->whereIn('order_package_id',$this->orderPackageIds);

                $this->orderProductsQuantities = $orderProd->select('product_id','product_quantity','cart')->get();
                $this->orderProductIds = $orderProd->pluck('product_id')->toArray();

                $this->dropshipProductSkus = $this->getDropshipProductSkuFromProductsTable();
                $this->getOrderDetails();


                $carrierResponse =  $this->carriers();
                if(is_array($carrierResponse) && array_key_exists('carrier_status',$carrierResponse))
                {
                    if($carrierResponse['carrier_message_status'] == 'error')
                    {
                        $data['msg']    = $carrierResponse['carrier_message'];
                        $data['status'] = $carrierResponse['carrier_message_status'];
                        return $data;
                    }
                }
                $this->carrierNameArrayFormated = $carrierResponse;
                

                $this->orderedDataAfterOrderPlacedToBigbuy =  $this->createOrderForBigbuy();
                

                $result =  json_decode($this->orderedDataAfterOrderPlacedToBigbuy,true);
                /* if(!isset($result) || !is_array($result) || $result  == NULL)
                {
                }
                //return $result;
                else if((is_array($result) && array_key_exists('code',$result))
                    || (is_array($result) && in_array('code',$result))
                )
                {
                    if($result['code']  == 201)
                    {
                        
                    }else{
                        return $this->statusCodeWiseMessage($result['code']);
                    }
                    
                } */
        
                /* if($result->code  == 201)
                {
                    //have to process
                    //get response data, after place order to bigbuy
                    //and process this data
                } */
                $orderStatusMessage =  $this->statusCodeWiseMessage($result['code']);
                $message            = $orderStatusMessage['msg'];
                $messageStatus      = $orderStatusMessage['status'];

                $orderResults[$index]['order_id'] = $this->orderId;
                $orderResults[$index]['order_package_number'] = $this->getOrderPackageNumberForBulkOrder();

                if($messageStatus ==  "success"){
                    $this->updateInternalReferenceAfterPackageWiseOrderPlacementToBigbuy();//update customerReference in the order_pacakges table
                    // alix_order_data => json format
                    //have to process
                    //get response data, after place order to bigbuy
                    //and process this data
                    $orderResults[$index]['action']     = "success";
                }else{
                    $orderResults[$index]['action']     = "error";
                }
                $orderResults[$index]['message']    = $message;
            }//end foreach
            return $orderResults;
        }


        
        /**
         * get order package number from order_packages table
         * against of merchant id = ebaskat prime (for bigbuy) and order id
         */
        public function getOrderPackageNumberForBulkOrder()
        {
            $odrPak = OrderPackage::where('order_id',$this->orderId)
                        ->where('merchant_id',$this->merchantId)
                        ->select('order_package_number')
                        ->latest() //orderBy('id', 'desc')
                        ->first();
            return $odrPak ? $odrPak->order_package_number : NULL;
        }
    /*
    |------------------------------------------------------------------------------------------------------------------
    |                                           -----------bulk order-----------
    |------------------------------------------------------------------------------------------------------------------
    */





    
    /*
    |------------------------------------------------------------------------------------------------------------------
    |            ----------- bigbuy Single order details by bigbuy order id-----------
    |------------------------------------------------------------------------------------------------------------------
    */
        // 1 request 1 sec  
        public function getOrderDetailsByBigbiyOrderId($bigbuyOrderId)
        {
            $curl   = curl_init();
            $apiUrl = bigbuyApiUrl_hd();
            $url = "{$apiUrl}/rest/order/{$bigbuyOrderId}";
            //$url = "{$apiUrl}/rest/order/reference/{$bigbuyOrderId}";
           
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
            return $response;
            $datas = json_decode($response);
        }
    /*
    |------------------------------------------------------------------------------------------------------------------
    |            -----------End bigbuy Single order details by bigbuy order id-----------
    |------------------------------------------------------------------------------------------------------------------
    */





    /*
    |--------------------------------------------------------------------
    | Custom order place / test 
    |---------------------------------------------------------------
    */
        public function testOrder()
        {
            $sendData = [
                "order" => [
                "internalReference" => "123456",
                "language"=> "en",
                "paymentMethod"=> "moneybox",
                "carriers"=> [
                    [
                    "name"=> "correos"
                    ],
                    /* [
                    "name"=> "chrono"
                    ] */
                ],
                "shippingAddress"=> [
                    "firstName"=> "John",
                    "lastName"=> "Doe",
                    "country"=> "ES",
                    "postcode"=> "46005",
                    "town"=> "Valencia",
                    "address"=> "C/ Altea",
                    "phone"=> "664869570",
                    "email"=> "moinul35@email.com",
                    "comment"=> ""
                ],
                "products"=> [
                    [                           //S0593013
                        "reference"=> "S0588001", //product sku  //variant sku = S5001964, product id= 292174, product sku = S5001988
                        "quantity"=> 1,
                        "internalReference"=> "LO5632"
                    ],
                    /* [
                    "reference"=> "S5001942",
                    "quantity"=> 4,
                    "internalReference"=> "LA4289"
                    ] */
                ]
                ]
            ];

            $customerKey    = bigbuyApiKey_hd();
            $apiUrl         = bigbuyApiUrl_hd();
            $headers = [
                "Content-Type:application/json",
                "Authorization: Bearer ".$customerKey
            ];
            $url = "{$apiUrl}/rest/order/create.json";

            $data	= json_encode($sendData);
            $ch = curl_init($url);
            //curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
            /* Array Parameter Data */
        
            /* pass encoded JSON string to the POST fields */
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            //curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
            //curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body

            curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            /* set return type json */
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
            /* execute request */
            $result = curl_exec($ch);
            return  json_decode($result);
            
            $e = "";
            if($e = curl_error($ch))
            {
                echo "error";
            }
            curl_close($ch);
            /* close cURL resource */
            echo $result;
            exit;
            return json_decode($result) ?? $e;


            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            echo 'HTTP code: ' . $httpcode;

            //---
            $response = curl_exec($ch);
            // Then, after your curl_exec call:
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $header_size);
            //---
            $body = substr($response, $header_size);
            //---


            //---
            $url = 'http://www.example.com';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
            curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_TIMEOUT,10);
            $output = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            echo 'HTTP code: ' . $httpcode;
            /*
            |--------------------------------------------------------------------
            | Custom order place / test 
            |---------------------------------------------------------------
            */
        }   
    /*
    |--------------------------------------------------------------------
    | Custom order place / test 
    |---------------------------------------------------------------
    */


}



        
    /* 
        return [
            "firstName"=> "John",
            "lastName"=> "Doe",
            "country"=> "ES",
            "postcode"=> "46005",
            "town"=> "Valencia",
            "address"=> "C/ Altea",
            "phone"=> "664869570",
            "email"=> "moinul35@email.com",
            "comment"=> ""
        ]; 
    */