<?php

namespace App\Traits;

use Carbon\Carbon;

use App\Models\OrderTrack;
use App\Models\OrderPackage;
use App\Models\OrderProduct;

/**
 *  * aliexpress order status update
 */
trait AliexpressOrderStatus
{
    public $alix_order_id;

    /**
     * Update order status
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrderStatusFromObaskat() //764780
    {
        ini_set('max_execution_time', 28800);
        $this->customerKey      = restapiCustomerKeyForWoocommerce();
        $this->customerSecret   = restapiCustomerSecretForWoocommerce();
        $curl = curl_init();
        $url = "https://obaskat.com/wp-json/wc/v3/orders/{$this->alix_order_id}?consumer_key={$this->customerKey}&consumer_secret={$this->customerSecret}";
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
        $result = json_decode($response,true);
        if(is_array($result) && in_array('code',$result))
        {
            return $result ? $result['status'] :"Pending";
        }else{
            return "Pending";
        }
    }



}
