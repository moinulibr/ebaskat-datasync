<?php

namespace App\Traits;

use App\Models\Order;
use App\Models\OrderPackage;
use App\Models\OrderProduct;
use App\Models\OrderTrack;
use Illuminate\Support\Arr;

trait OrderStatusMailing
{
    
    /**
     *containt single order details from orders table
     */
    public $mainOrderDetails;


    
    /**
     * main order status chaning message send to the customer by mail
     */
    public function mainOrderStatusChangingMail()
    {   
        $formData = [
            'customer_name' =>$this->mainOrderDetails->customer_name,
            'email' => $this->mainOrderDetails->customer_email,
            'order_id' => $this->mainOrderDetails->order_number,
            'order_status' => $this->mainOrderDetails->status,
        ];//array('customer_name' => 'Dada','email' => 'dada@gmail.com','order_id' => '99890','order_status' => 'Awaiting to Shiped ')
        
        $requestUrl="send-mail/order/status-change";
        $mthod="POST";
        $this->api($formData,$requestUrl,$mthod);
        return true;
    }

   


    /**
     * partial order chaning message send to the customer by mail
     */
    public function splittedOrderStatusChangingMail()
    {   
        $packagesId = OrderPackage::where('order_id',$this->mainOrderDetails->id)->pluck('id')->toArray();
        $orderProducts = OrderProduct::whereIn('order_package_id',$packagesId)
                        ->select('delivery_status','cart','order_package_id')
                        ->get();
        $formData = [
            'customer_name' =>$this->mainOrderDetails->customer_name,
            'email' => $this->mainOrderDetails->customer_email,
            'order_id' => $this->mainOrderDetails->order_number,
            'order_status' => $this->mainOrderDetails->status,
            'order_product' => $orderProducts,
        ];
       
        $requestUrl="send-mail/splitted/order/status-change";
        $mthod="POST";
        $this->api($formData,$requestUrl,$mthod);
        return true;
    }



    /*
    |------------------------------------------------------------
    | api function
    | param array $formData
    | param string $requestUrl
    | param string $mthod
    | return void
    |------------------------------------------------------------
    */ 
    public function api($formData,$requestUrl,$mthod)
    {
        //$formData =[];
        $baseUrl = queueBaseUrl_hd(); //from helpers/dependency fiels
        $xAppKey = queueHeaderXAppKey_hd(); //from helpers/dependency fiels
        //$requestUrl = 'send-mail/order/status-change';
        $apiUrl = "{$baseUrl}/{$requestUrl}";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            //CURLOPT_URL => 'localhost:8000/send-mail/order/status-change',
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_CUSTOMREQUEST => $mthod,
            CURLOPT_POSTFIELDS => $formData ,
            CURLOPT_HTTPHEADER => array(
                //'X-APP-KEY: 3jaww6dXHjNq8Frc9AveaWI87PJUeBfs'
                'X-APP-KEY: '.$xAppKey
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }//end pai function


}
