<?php

namespace App\Traits;

use App\Models\Order;
use App\Models\OrderPackage;
use App\Models\OrderProduct;
use App\Models\OrderTrack;
use App\Models\Product;
use Illuminate\Support\Arr;

/**
 *
 */
trait AliexpressOrder
{
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
    protected $orderDataAfterOrderPlaceToObaskat;

    /**
     * cart field for getting cart details
     */
    protected $cartField;

    /**
     * Order process to aliexpress
     */
    public function processOrderToAliexpress()
    {
        $this->orderPackageIds  = OrderPackage::where('order_id',$this->orderId)
                                ->where('merchant_id',$this->merchantId)
                                ->pluck('id')
                                ->toArray();

        $orderProd  = OrderProduct::query()->whereIn('order_package_id',$this->orderPackageIds);

        $this->orderProductsQuantities = $orderProd->select('product_id','product_quantity','cart')->get();
        $this->orderProductIds = $orderProd->pluck('product_id')->toArray();

        $this->dropshipProductIds = $this->getDropshipProductIdFromProductsTable();
        $this->getOrderDetails();
        $this->orderDataAfterOrderPlaceToObaskat =  $this->createOrderForAliexpress();

        if($this->orderDataAfterOrderPlaceToObaskat != "error"){
           $this->changeStatusAfterPlaceOrder();
           return "success";
        }
        return "error";
    }


    /**
     * get dropship product id from products table
     * as da_product_id
     */
    public function getDropshipProductIdFromProductsTable() : array
    {
        $productIds = [];
        foreach($this->orderProductIds  as  $id)
        {
            $productIds[] = Product::where('id',$id)->pluck('da_product_id')->toArray();
        }
        return Arr::flatten($productIds);
        //$this->dropshipProductIds = Product::whereIn('id',$this->orderProductIds)->pluck('da_product_id')->toArray();
    }


    /**
     * Update delivery_status from OrderPackage table
     * and orderPorduct table
     */
    private function changeStatusAfterPlaceOrder()
    {
        $data = [];
        $obaskat_order_id               =  $this->orderDataAfterOrderPlaceToObaskat->id;
        $data['obaskat_order_number']   =  $this->orderDataAfterOrderPlaceToObaskat->number;
        $data['obaskat_order_key']      =  $this->orderDataAfterOrderPlaceToObaskat->order_key;

        OrderPackage::whereIn('id',$this->orderPackageIds)
            ->update(
                [
                    "delivery_status" => "Processing to Ship",
                    "alix_order_id" => $obaskat_order_id,
                    "alix_order_data" => json_encode($data)
                ]
            );
        OrderProduct::whereIn('order_package_id',$this->orderPackageIds)
            ->update(
                [   "delivery_status" => "Processing to Ship",
                    //"product_status" => json_encode($data)
                ]
            );

        $orderTrack = new OrderTrack();
        $orderTrack->order_id = $this->orderId;
        $orderTrack->title    = "Processing to Ship";
        $orderTrack->text     = "Processing to Ship";
        $orderTrack->save();

        return true;
    }


    /**
     * get order details by order ID
     */
    public function getOrderDetails()
    {
       return  $this->orderDetails = Order::findOrFail($this->orderId);
    }


    /**
     * crate order to aliexpress by php CURL
     */
    public function createOrderForAliexpress()
    {
        $this->customerKey      = restapiCustomerKeyForWoocommerce();
        $this->customerSecret   = restapiCustomerSecretForWoocommerce();

        $url = "https://obaskat.com/wp-json/wc/v3/orders?consumer_key={$this->customerKey}&consumer_secret={$this->customerSecret}";
        $sendData = [
            "payment_method" => "bacs",
            "payment_method_title" => $this->paymentMethodTitle = "Direct Bank Transfer",
            "set_paid" => true,

            "billing" =>
                $this->billing()
            ,
            "shipping"=>
                $this->shipping()
            ,
            "line_items"=>
                $this->orderLineItems() //order products and quantity
            ,
            "shipping_lines"=>
                $this->shippingLines()
        ];
        $data	= json_encode($sendData);
        /* set the content type json */
        $headers = [
            "Content-Type:application/json",
        ];
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        /* Array Parameter Data */

        /* pass encoded JSON string to the POST fields */
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        /* set return type json */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        /* execute request */
        $result = curl_exec($ch);
        $e = "";
        if($e = curl_error($ch))
        {
            return "error";
        }
        curl_close($ch);
        /* close cURL resource */
        return json_decode($result) ?? $e;
    }


    /**
     * bulling details of customer
     * @return array
     */
    private function billing() : array
    {
        return [
            "first_name"=> $this->orderDetails ? $this->orderDetails->customer_name : "empty",
            "last_name"=> "",
            "address_1"=> $this->orderDetails ? $this->orderDetails->customer_address : "empty",
            "address_2"=> "",
            "city"=> $this->orderDetails ? $this->orderDetails->customer_city : "empty",
            "state"=> $this->orderDetails ? $this->orderDetails->customer_city : "empty",
            "postcode"=> $this->orderDetails ? $this->orderDetails->customer_zip : "empty",
            "country"=> $this->orderDetails ? $this->orderDetails->customer_country : "empty",
            "email"=> $this->orderDetails ? $this->orderDetails->customer_email : "empty",
            "phone"=> $this->orderDetails ? $this->orderDetails->customer_phone : "empty"
        ];
    }

    /**
     * shipping details of this order
     */
    private function shipping() : array
    {
        return [
            "first_name"=> $this->orderDetails?$this->orderDetails->shipping_name : "empty",
            "last_name"=> "",
            "address_1"=> $this->orderDetails ? $this->orderDetails->shipping_address : "empty",
            "address_2"=> "phone : " . $this->orderDetails ? $this->orderDetails->shipping_phone : "empty",
            "city"=> $this->orderDetails ? $this->orderDetails->shipping_city : "empty",
            "state"=> $this->orderDetails ? $this->orderDetails->shipping_city : "empty",
            "postcode"=> $this->orderDetails ? $this->orderDetails->shipping_zip : "empty",
            "country"=> $this->orderDetails ? $this->orderDetails->shipping_country : "empty"
        ];
    }


    /**
     * order products and quantity
     */
    private function orderLineItems() : array
    {
        $this->lineItems = [];
        foreach($this->orderProductsQuantities as $index => $value)
        {
            $this->cartField = $value;
            if($this->getCartAttribute())
            {
                $this->lineItems[$index] = [
                    'product_id'    => $this->dropshipProductIds[$index], //$value->product_id
                    'variation_id'  => $this->getCartAttribute() ?? NULL,
                    'quantity'      => $value->product_quantity
                ];
            }else{
                $this->lineItems[$index] = [
                    'product_id'    => $this->dropshipProductIds[$index], //$value->product_id
                    'quantity'      => $value->product_quantity
                ];
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
     * shipping lines
     */
    public function shippingLines() : array
    {
        return
        [
            [
                "method_id"=> "flat_rate",
                "method_title"=> "Flat Rate",
                "total"=> "0"
            ]
        ];
    }






    /*
    |------------------------------------------------------------------------------------------------------------------
    |                                           -----------bulk order-----------
    |------------------------------------------------------------------------------------------------------------------
    */
        /**
         * Order process to aliexpress
         */
        public function bulkOrderProcessToAliexpress()
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

                $this->dropshipProductIds = $this->getDropshipProductIdFromProductsTable();
                $this->getOrderDetails();
                $this->orderDataAfterOrderPlaceToObaskat =  $this->createOrderForAliexpress();


                $orderResults[$index]['order_id'] = $this->orderId;
                //$orderResults[$index]['order_package_number'] = $this->getOrderPackageNumber();
                $orderResults[$index]['order_package_number'] = $this->getOrderPackageNumberForBulkOrder();

                if($this->orderDataAfterOrderPlaceToObaskat != "error"){
                    $this->changeStatusAfterPlaceOrder();
                    $orderResults[$index]['action'] = "success";
                }else{
                    $orderResults[$index]['action'] = "error";
                }
            }
            return $orderResults;
        }


        /**
         * get order package number from order_packages table
         * against of merchant id = ebaskat prime (for aliexpress) and order id
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



        //may not need to use all below methods
           /*  public function bulkOrderProcessToAliexpress()
            {
                $orderResults = [];
                foreach($this->bulkOrderIds as $index => $order)
                {
                    $this->orderId = $order;

                    $this->orderPackageIds  = OrderPackage::where('order_id',$this->orderId)
                                        ->where('merchant_id',$this->merchantId)
                                        ->pluck('id')
                                        ->toArray();

                    $orderProd  = OrderProduct::query()->whereIn('order_package_id',$this->orderPackageIds);

                    $this->orderProductsQuantities = $orderProd->select('product_id','product_quantity','cart')->get();
                    $this->orderProductIds = $orderProd->pluck('product_id')->toArray();

                    $this->dropshipProductIds = $this->getDropshipProductIdFromProductsTableForBulkOrder();
                    $this->getOrderDetailsForBulkOrder();
                    $this->orderDataAfterOrderPlaceToObaskat =  $this->createOrderForAliexpressForBulkOrder();


                    $orderResults[$index]['order_id'] = $this->orderId;
                    $orderResults[$index]['order_package_number'] = $this->getOrderPackageNumberForBulkOrder();

                    if($this->orderDataAfterOrderPlaceToObaskat != "error"){
                        $this->changeStatusAfterPlaceOrderForBulkOrder();
                        $orderResults[$index]['action'] = "success";
                    }else{
                        $orderResults[$index]['action'] = "error";
                    }
                }
                return $orderResults;
            } */


        /** not use this for bulk order process
         * get dropship product id from products table
         * as da_product_id
         */
        public function getDropshipProductIdFromProductsTableForBulkOrder() : array
        {
            $productIds = [];
            foreach($this->orderProductIds  as  $id)
            {
                $productIds[] = Product::where('id',$id)->pluck('da_product_id')->toArray();
            }
            return Arr::flatten($productIds);
            //$this->dropshipProductIds = Product::whereIn('id',$this->orderProductIds)->pluck('da_product_id')->toArray();
        }


        /** not use this for bulk order process
         * Update delivery_status from OrderPackage table
         * and orderPorduct table
         */
        private function changeStatusAfterPlaceOrderForBulkOrder()
        {
            $data = [];
            $obaskat_order_id               =  $this->orderDataAfterOrderPlaceToObaskat->id;
            $data['obaskat_order_number']   =  $this->orderDataAfterOrderPlaceToObaskat->number;
            $data['obaskat_order_key']      =  $this->orderDataAfterOrderPlaceToObaskat->order_key;

            OrderPackage::whereIn('id',$this->orderPackageIds)
                ->update(
                    [
                        "delivery_status" => "Processing to Ship",
                        "alix_order_id" => $obaskat_order_id,
                        "alix_order_data" => json_encode($data)
                    ]
                );
            OrderProduct::whereIn('order_package_id',$this->orderPackageIds)
                ->update(
                    [   "delivery_status" => "Processing to Ship",
                        //"product_status" => json_encode($data)
                    ]
                );

            $orderTrack = new OrderTrack();
            $orderTrack->order_id = $this->orderId;
            $orderTrack->title    = "Processing to Ship";
            $orderTrack->text     = "Processing to Ship";
            $orderTrack->save();

            return true;
        }


        /** not use this for bulk order process
         * get order details by order ID
         */
        public function getOrderDetailsForBulkOrder()
        {
            return  $this->orderDetails = Order::findOrFail($this->orderId);
        }


        /**  not use this for bulk order process
         * crate order to aliexpress by php CURL
         */
        public function createOrderForAliexpressForBulkOrder()
        {
            $url = 'https://obaskat.com/wp-json/wc/v3/orders?consumer_key=ck_ae7e79108beb39e854e95babf9344c3283f452fc&consumer_secret=cs_78af601a396f2f236345d9351e068f9f79d4a084';
            $sendData = [
                "payment_method" => "bacs",
                "payment_method_title" => $this->paymentMethodTitle = "Direct Bank Transfer",
                "set_paid" => true,

                "billing" =>
                    $this->billingForBulkOrder()
                ,
                "shipping"=>
                    $this->shippingForBulkOrder()
                ,
                "line_items"=>
                    $this->orderLineItemsForBulkOrder() //order products and quantity
                ,
                "shipping_lines"=>
                    $this->shippingLinesForBulkOrder()
            ];
            $data	= json_encode($sendData);
            /* set the content type json */
            $headers = [
                "Content-Type:application/json",
            ];
            $ch = curl_init($url);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
            /* Array Parameter Data */

            /* pass encoded JSON string to the POST fields */
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            /* set return type json */
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            /* execute request */
            $result = curl_exec($ch);
            $e = "";
            if($e = curl_error($ch))
            {
                return "error";
            }
            curl_close($ch);
            /* close cURL resource */
            return json_decode($result) ?? $e;
        }


        /**
         * bulling details of customer
         * @return array
         */
        private function billingForBulkOrder() : array
        {
            return [
                "first_name"=> $this->orderDetails ? $this->orderDetails->customer_name : "empty",
                "last_name"=> "",
                "address_1"=> $this->orderDetails ? $this->orderDetails->customer_address : "empty",
                "address_2"=> "",
                "city"=> $this->orderDetails ? $this->orderDetails->customer_city : "empty",
                "state"=> $this->orderDetails ? $this->orderDetails->customer_city : "empty",
                "postcode"=> $this->orderDetails ? $this->orderDetails->customer_zip : "empty",
                "country"=> $this->orderDetails ? $this->orderDetails->customer_country : "empty",
                "email"=> $this->orderDetails ? $this->orderDetails->customer_email : "empty",
                "phone"=> $this->orderDetails ? $this->orderDetails->customer_phone : "empty"
            ];
        }

        /**  not use this for bulk order process
         * shipping details of this order
         */
        private function shippingForBulkOrder() : array
        {
            return [
                "first_name"=> $this->orderDetails?$this->orderDetails->shipping_name : "empty",
                "last_name"=> "",
                "address_1"=> $this->orderDetails ? $this->orderDetails->shipping_address : "empty",
                "address_2"=> "phone : " . $this->orderDetails ? $this->orderDetails->shipping_phone : "empty",
                "city"=> $this->orderDetails ? $this->orderDetails->shipping_city : "empty",
                "state"=> $this->orderDetails ? $this->orderDetails->shipping_city : "empty",
                "postcode"=> $this->orderDetails ? $this->orderDetails->shipping_zip : "empty",
                "country"=> $this->orderDetails ? $this->orderDetails->shipping_country : "empty"
            ];
        }


        /**  not use this for bulk order process
         * order products and quantity
         */
        private function orderLineItemsForBulkOrder() : array
        {
            $this->lineItems = [];
            foreach($this->orderProductsQuantities as $index => $value)
            {
                $this->cartField = $value;
                if($this->getCartAttributeForBulkOrder())
                {
                    $this->lineItems[$index] = [
                        'product_id'    => $this->dropshipProductIds[$index], //$value->product_id
                        'variation_id'  => $this->getCartAttributeForBulkOrder() ?? NULL,
                        'quantity'      => $value->product_quantity
                    ];
                }else{
                    $this->lineItems[$index] = [
                        'product_id'    => $this->dropshipProductIds[$index], //$value->product_id
                        'quantity'      => $value->product_quantity
                    ];
                }
            }
            return $this->lineItems;
        }



        /**  not use this for bulk order process
         * get cart attribute from cart field of order_products table
         */
        private function getCartAttributeForBulkOrder()
        {
            $carts = json_decode($this->cartField->cart);
            return $carts->productVariationId ? $carts->productVariationId : NULL;
        }



        /** not use this for bulk order process
         * shipping lines
         */
        public function shippingLinesForBulkOrder() : array
        {
            return
            [
                [
                    "method_id"=> "flat_rate",
                    "method_title"=> "Flat Rate",
                    "total"=> "0"
                ]
            ];
        }







}
