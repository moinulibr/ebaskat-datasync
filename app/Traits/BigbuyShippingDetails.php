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
/**
 *
 */
trait BigbuyShippingDetails
{
    public $orderId;
    public $merchantId;

    /**
     * contain order details
     */
    public $orderDetails;


    /**
     * ebakset product and quantity of this order
     */
    public $orderProductsQuantities;


    /**
     *product and quantity of this order
     */
    public $lineItems;


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
    protected $orderDataAfterOrderPlaceToObaskat;

    /**
     * cart field for getting cart details
     */
    protected $cartField; //array

    //shipping details for single product sku 
    protected $countryShortCode; //string
    protected $postCode; //string
    protected $productSku; //string
    protected $orderQuantity; //integer
    protected $shippingOption; //array

    //shipping check before crate order
    public $productsSkus; //array
    protected $productsQtyAndSkus; //array

    /*  how to call the methods with property
        $this->countryShortCode = "ES";
        $this->postCode = "46005";
        $this->productSku   = "S0433665";
        $this->orderQuantity    = 1;
        //get shipping details by single sku of single product #[get json encoded data]
        $data = $this->getShippingDetailsBySingleProductSku();
        //convert to json decode from json encoded data
        $this->shippingOption = json_decode($data);
        //get minimum shipping cost by single sku of single product
        echo $this->getMinimumShippingOnlyCostFromShippingDetails();
        
        //process shipping details data #[just check/test data]
        $this->processedShippingDetailsData();
        exit;


        $this->orderId  = 3;
        $this->merchantId = 2;
        //get shipping details by single order id #[get json encoded data]
        $data = $this->shippingDetailsByOrderId();
        //convert to json decode from json encoded data
        $this->shippingOption = json_decode($data);
        //process shipping details data #[just check/test data]
        $this->processedShippingDetailsData();
        exit;
    */


    /*
    |---------------------------------------------------------------------------------------------------
    |   shipping details by order id and merchant id
    |---------------------------------------------------------------------------------------------------
    */

            /*
            |--------------------------------------------------------
            | It's not needed, it will be remove
            | order created section
            |--------------------------------------------------------
            */
                /**
                 * Order process to bigbuy
                 */
                public function shippingDetailsByOrderId()
                {
                    $this->orderPackageIds  = OrderPackage::where('order_id',$this->orderId)
                                            ->where('merchant_id',$this->merchantId)
                                            ->pluck('id')
                                            ->toArray();

                    $orderProd  = OrderProduct::query()->whereIn('order_package_id',$this->orderPackageIds);

                    $this->orderProductsQuantities = $orderProd->select('product_id','product_quantity','cart')->get();
                    $this->orderProductIds = $orderProd->pluck('product_id')->toArray();

                    $this->dropshipProductSkus = $this->getDropshipProductIdFromProductsTableForShipping();
                    $this->getOrderDetailsForShipping();
                    return $this->orderDataAfterOrderPlaceToObaskat =  $this->getShippingDetailsByOrderId();
                    $result =  json_decode($this->orderDataAfterOrderPlaceToObaskat);
                    return $result;
                    if($result->code  == 201)
                    {
                        //have to process
                        //get response data, after place order to bigbuy
                        //and process this data
                    }
                    return $this->statusCodeWiseMessage($result->code);
                }
                /**
                 * order create response Status:
                 * Status code wise message.
                 */
                public function statusCodeWiseMessage($statusCode)
                {
                    $data['msg']    = "";
                    $data['status'] = "";
                    if($statusCode == 200)
                    {
                        $data['msg']    = 'Successfully.';
                        $data['status'] = "success";
                    }
                    else if($statusCode == 400)
                    {
                        $data['msg']    = 'Validation errors';
                        $data['status'] = "error";
                    }
                    else if($statusCode == 404)
                    {
                        $data['msg']    = 'Carriers has been not found';
                        $data['status'] = "error";
                    }
                    else if($statusCode == 415)
                    {
                        $data['msg']    = 'Invalid Content-Type header';
                        $data['status'] = "error";
                    }
                    else if($statusCode == 429)
                    {
                        $data['msg']    = 'Exceeded requests limits.';
                        $data['status'] = "error";
                    }
                    return $data;
                }
            /*
            |--------------------------------------------------------
            | It's not needed, it will be remove
            | order created section
            |--------------------------------------------------------
            */


    /*
    |--------------------------------------------------------
    | shipping related section
    |--------------------------------------------------------
    */
        //this is perfectly working
        protected function getShippingStatusWiseMessage($result)
        {
            $data['error_status']       = false;
            $data['error_message']      = false;
            $data['shipping_status']    = false;
            $shippingResponse = json_decode($result,true);
            
            if(array_key_exists('code',$shippingResponse) || 
                in_array('code',$shippingResponse)
            )
            {
                $data['error_status']   = true;
                $error                  = [];
                $error['type']          = "";
                $error['sku']           = "";
                $data['error_message']  = "";
                if($shippingResponse['code'] == 200)
                {
                    $data['error_message'] = 'Successfully.';
                    $data['shipping_status'] = "success";
                    $data['error_status']   = false;
                }
                else if($shippingResponse['code'] == 409)
                {
                    $data['error_status']   = true;
                    $shipErrerRespons = json_decode( $shippingResponse['message'],true);
                    if(array_key_exists('info',$shipErrerRespons))
                    {
                        $error['type'] = $shipErrerRespons['info'] . ":- ";
                    }
                    if(array_key_exists('data',$shipErrerRespons))
                    {
                        foreach($shipErrerRespons['data'] as $errIndex)
                        {
                            $data['error_message'] .= " sku : ". $errIndex['sku'] . " - message : ".$errIndex['message'] .", "; 
                        }
                        $data['error_message']      = $error['type'] . " " . $data['error_message'];
                    }
                }
                else if($shippingResponse['code'] == 400)
                {
                    $data['error_message']      = 'Validation errors';
                    $data['shipping_status']    = "error";
                    $data['error_status']       = true;
                }
                else if($shippingResponse['code'] == 404)
                {
                    $data['error_message']      = 'Carriers has been not found';
                    $data['shipping_status']    = "error";
                    $data['error_status']       = true;
                }
                else if($shippingResponse['code'] == 415)
                {
                    $data['error_message']      = 'Invalid Content-Type header';
                    $data['shipping_status']    = "error";
                    $data['error_status']       = true;
                }
                else if($shippingResponse['code'] == 429)
                {
                    $data['error_message']      = 'Exceeded requests limits.';
                    $data['shipping_status']    = "error";
                    $data['error_status']       = true;
                }
            }
            return $data;
        }




        /**
         * get dropship product id from products table
         * as da_product_id
         */
        public function getDropshipProductIdFromProductsTableForShipping() : array
        {
            $productSkus = [];
            foreach($this->orderProductIds  as $key=>  $id)
            {
                //$productIds[] = Product::where('id',$id)->pluck('da_product_id')->toArray();
                $productSkus[] = Product::where('id',$id)->pluck('sku')->toArray();
            }
            return Arr::flatten($productSkus);
        }



        /**
         * get order details by order ID
         */
        public function getOrderDetailsForShipping()
        {
            return  $this->orderDetails = Order::findOrFail($this->orderId);
        }


        /**
         * required parametter : 3
         * 1. Country short code,  2.postcde, and 3. product sku
         */
        public function getShippingDetailsByOrderId()
        {
            $customerKey    = bigbuyApiKey_hd();
            $apiUrl         = bigbuyApiUrl_hd();
            $headers = [
                "Content-Type:application/json",
                "Authorization: Bearer ".$customerKey
            ];
            $url = "{$apiUrl}/rest/shipping/orders.json";

            $sendData = [
                "order" => [
                    "delivery"=> [
                        "isoCountry"=>$this->orderDetails ? "{$this->orderDetails->shipping_country}" : "empty",//$this->countryShortCode,
                        "postcode"  =>$this->orderDetails ? "{$this->orderDetails->shipping_zip}" : "empty",//$this->postCode,
                    ],
                    "products" => $this->products()
                    /* "products"=> [
                        [
                            "reference" => "S13015215",
                            "quantity"=> 2,
                        ] ,[
                            "reference" => "S4511787",
                            "quantity"=> 1,
                        ]
                    ] */
                ]
            ];
            $data	= json_encode($sendData);
            
            $ch = curl_init($url);
            //curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
            /* Array Parameter Data */
        
            /* pass encoded JSON string to the POST fields */
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            /* set return type json */
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
            /* execute request */
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
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
                if($this->getCartAttributeForShipping())
                {
                    $this->lineItems[$index] = [
                        'reference'         => "{$this->variationSku()}", //$value->product_id
                        'quantity'          => $value->product_quantity,
                    ];
                }else{
                    $this->lineItems[$index] = [
                        'reference'         => "{$this->dropshipProductSkus[$index]}", //$value->product_id
                        'quantity'          => $value->product_quantity,
                    ];
                }
            }
            return $this->lineItems;
        }



        /**
         * get cart attribute from cart field of order_products table
         */
        private function getCartAttributeForShipping()
        {
            $carts = json_decode($this->cartField->cart);
            return $carts->productVariationId ? $carts->productVariationId : NULL;
        }

        /**
         * get variation sku from variation_sku field of product_variants table
         */
        private function variationSku()
        {
            $variationId    =   $this->getCartAttributeForShipping();
            $variation      =   ProductVariant::where('alix_variation_id',$variationId)->first();
            return $variation?$variation->variation_sku:NULL;
        }
    /*
    |---------------------------------------------------------------------------------------------------
    |   end shipping details by order id and merchant id
    |---------------------------------------------------------------------------------------------------
    */

    


    /*
    |---------------------------------------------------------------------------------------------------
    |   single product sku shipping details by product sku
    |---------------------------------------------------------------------------------------------------
    */
        /**
         * parameter : 4, country short code, post code, 
         * product sku , quantity
         */
        public function getShippingDetailsBySingleProductSku()
        {
            $customerKey    = bigbuyApiKey_hd();
            $apiUrl         = bigbuyApiUrl_hd();
            $headers = [
                "Content-Type:application/json",
                "Authorization: Bearer ".$customerKey
            ];
            $url = "{$apiUrl}/rest/shipping/orders.json";

            $sendData = [
                "order" => [
                    "delivery"=> [
                        "isoCountry"=> "{$this->countryShortCode}",
                        "postcode"  => "{$this->postCode}"
                    ],
                    "products" => [
                        [
                            "reference" => "{$this->productSku}",
                            "quantity"  => $this->orderQuantity,
                        ]
                    ]
                ]
            ];
            $data	= json_encode($sendData);
            
            $ch = curl_init($url);
            //curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
            /* Array Parameter Data */
        
            /* pass encoded JSON string to the POST fields */
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            /* set return type json */
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
            /* execute request */
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }
    
    /*
    |---------------------------------------------------------------------------------------------------
    |  End single product sku shipping details by product sku
    |---------------------------------------------------------------------------------------------------
    */


   
    
    /*
    |---------------------------------------------------------------------------------------------------
    |   get minimum shipping cost from shipping Details
    |---------------------------------------------------------------------------------------------------
    */
        public function getMinimumShippingOnlyCostFromShippingDetails()
        {
            $shippingCost = [];
            if(is_object($this->shippingOption) && 
                is_array($this->shippingOption->shippingOptions)
            ){
                foreach($this->shippingOption->shippingOptions as $i => $shippingOption)
                {
                    $shippingCost[$i] = $shippingOption->cost;
                }
               return min($shippingCost);
            }
            else{
                return 0;
            }
        }
    /*
    |---------------------------------------------------------------------------------------------------
    |  End get minimum shipping cost from shipping Details
    |---------------------------------------------------------------------------------------------------
    */



   
    /*
    |---------------------------------------------------------------------------------------------------
    |   get minimum shipping cost and carrier name from shipping Details
    |---------------------------------------------------------------------------------------------------
    */
        /**
         * return array
         */
        public function getMinimumShippingCostAndCarierNameFromShippingDetails()
        {
            $shippingCost = [];
            $shippingName = [];
            if(is_object($this->shippingOption) && 
                is_array($this->shippingOption->shippingOptions)
            ){
                foreach($this->shippingOption->shippingOptions as $i => $shippingOption)
                {
                    $shippingObject = $shippingOption->shippingService;
                    $shippingCost[$i] = $shippingOption->cost;
                    $shippingName[$shippingOption->cost] = $shippingObject->name;
                }
                $minimumCost =  min($shippingCost);
                $carrierName =  $shippingName[$minimumCost];
                return [
                    "minimum_cost" => $minimumCost,
                    "carrier_name" => $carrierName
                ];
            }
            else{
                return [
                    "minimum_cost" => 0,
                    "carrier_name" => "NULL"
                ];
            }
        }
    /*
    |---------------------------------------------------------------------------------------------------
    |  End get minimum shipping cost and carrier name  from shipping Details
    |---------------------------------------------------------------------------------------------------
    */








    //****************************************************************************************************************/
    //****************************************************************************************************************/
            //check shipping cost from multiple products before creating order
    //****************************************************************************************************************/
    //****************************************************************************************************************/
            //how to call this method with parameter 
            //$this->countryShortCode = defaultShippingCountryForBigbuy_hd();
            //$this->postCode = defaultShippingPostCodeForBigbuy_hd();
            //$this->countryShortCode = "";
            //$this->postCode = ""; 
            //$data  = $this->checkShippingDetailsBeforeCreatingOrder();
            //$this->shippingOption = json_decode($data);
            //$datas = $this->processedShippingDetailsData();
            //return $data;
            //echo "<pre>";
            //print_r($data);
            //echo "</pre>";
            //exit;

        public function checkShippingDetailsBeforeCreatingOrder()
        {
            $this->productsSkus = $this->productsQtyAndSkus;//this value get by this property from another page..
            $this->productsAndQtyBeforeOrder();
            return $this->getShippingDetailsBeforeOrderByMultipleProductsSku();

            /* 
                $skus = [
                    [
                        "sku" => "S2404513",
                        "quantity" => 1
                    ],[
                        "sku" => "S2413218",
                        "quantity" => 1
                    ],[
                        "sku" => "S2413331",
                        "quantity" => 1
                    ],[
                        "sku" => "S2413387",
                        "quantity" => 1
                    ]
                ];

                //$skus = ["S2404513","S2413218","S2413331","S2413387"];
                $this->productsSkus = $skus; 
                $this->productsQtyAndSkus ;
                $this->countryShortCode ;
                $this->postCode; 
            */
        }


        /* get shipping details before order creating by multiple products sku 
         * required parametter : 3
         * 1. Country short code,  2.postcde, and 3. product sku
         */
        public function getShippingDetailsBeforeOrderByMultipleProductsSku()
        {
            $customerKey    = bigbuyApiKey_hd();
            $apiUrl         = bigbuyApiUrl_hd();
            $headers = [
                "Content-Type:application/json",
                "Authorization: Bearer ".$customerKey
            ];
            $url = "{$apiUrl}/rest/shipping/orders.json";

            $sendData = [
                "order" => [
                    "delivery"=> [
                        "isoCountry"=> "{$this->countryShortCode}",
                        "postcode"  => "{$this->postCode}",
                    ],
                    "products" => $this->productsAndQtyBeforeOrder()
                ]
            ];
            $data	= json_encode($sendData);
            
            $ch = curl_init($url);
            //curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
            /* Array Parameter Data */
        
            /* pass encoded JSON string to the POST fields */
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            /* set return type json */
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
            /* execute request */
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }


        /**
         * products and quantities before order
         */
        private function productsAndQtyBeforeOrder()
        {
            $productSkus = [];
            if(is_array($this->productsSkus))
            {
                foreach($this->productsSkus as $index => $value)
                {
                    $productSkus[$index] = [
                        'reference'         => $value['sku'],
                        'quantity'          => $value['quantity'],
                    ];   
                }
            }
            return $productSkus;
        }
    //****************************************************************************************************************/
    //****************************************************************************************************************/
    //****************************************************************************************************************/
        








 
    /*
    |---------------------------------------------------------------------------------------------------
    |   display : processed shipping data just for test purpose
    |---------------------------------------------------------------------------------------------------
    */
        public function processedShippingDetailsData()
        {
            echo " ----------------------------------------------------<br/>";
            $shippingCost = [];
            foreach($this->shippingOption->shippingOptions as $i => $shippingOptionArrary)
            {
                $shippingObject = $shippingOptionArrary->shippingService;
                echo "  carrier name : ". $shippingObject->name;
                echo "  ,  cost : ". $shippingOptionArrary->cost;
                echo "<br/>";
                $shippingCost[$i] = $shippingOptionArrary->cost;
            }
            echo "<pre>";
            print_r($shippingCost);
            echo "</pre>";
            echo "min cost : ". min($shippingCost);
            echo "<br/> ----------------------------------------------------<br/>";


            foreach($this->shippingOption->shippingOptions as $shippingOption)
            {
                $shipping = $shippingOption->shippingService;
                echo "<br/> Id : ". $shipping->id;
                echo " ,  carrier name : ". $shipping->name;
                echo " , delay : ". $shipping->delay;
                echo " , transportMethod : ". $shipping->transportMethod;
                echo " , serviceName : ". $shipping->serviceName;
                echo "  ,  cost : ". $shippingOption->cost;
                echo "  ,  weight : ". $shippingOption->weight;
                echo "<br/>";
                // foreach($shippingOption->shippingService as $k)
                //{
                    //echo "<br/>" . $k;
                //}
            }
            echo "<br/>";
            echo "First rows data :- ";
            $single = $this->shippingOption->shippingOptions[0];
            $arrayData =  $single->shippingService;
            echo "id : ". $arrayData->id;
            echo " , name : ". $arrayData->name;
            echo " , delay : ". $arrayData->delay;
            echo " , transportMethod : ". $arrayData->transportMethod;
            echo " , serviceName : ". $arrayData->serviceName;
            echo " ,  cost : ".$single->cost;
            echo " , weight : ".$single->weight;
            exit;
        
        }//function

        public function getShippingDetailsBySingleProductSkuForTesting()
        {
            $customerKey    = bigbuyApiKey_hd();
            $apiUrl         = bigbuyApiUrl_hd();
            $headers = [
                "Content-Type:application/json",
                "Authorization: Bearer ".$customerKey
            ];
            $url = "{$apiUrl}/rest/shipping/orders.json";

            $sendData = [
                "order" => [
                    "delivery"=> [
                        "isoCountry"=> "IE",
                        "postcode"  => "D01XR68"
                    ],
                    "products" => [
                        [
                            "reference" => "S2411980",
                            "quantity"  => 1,
                        ]
                    ]
                ]
            ];
            $data	= json_encode($sendData);
            
            $ch = curl_init($url);
            //curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
            /* Array Parameter Data */
        
            /* pass encoded JSON string to the POST fields */
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            /* set return type json */
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
            /* execute request */
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }
    
    /*
    |---------------------------------------------------------------------------------------------------
    |  End display : processed shipping data just for test purpose
    |---------------------------------------------------------------------------------------------------
    */



}
