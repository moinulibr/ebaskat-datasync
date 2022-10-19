<?php

namespace App\Traits;

use Carbon\Carbon;

use App\Models\OrderTrack;
use App\Models\OrderPackage;
use App\Models\OrderProduct;

/**
 *  * aliexpress order status update
 */
trait BigbuyOrderStatus
{
    public $alix_order_id;


    /**
     * Update order status
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrderStatusFromBigbuy() //764780
    {
        $response = $this->getBigbuyOrderDetailsByBigbiyOrderId($this->alix_order_id);
        $objectData = json_decode($response,true);
        if(is_array($objectData) && !array_key_exists('code',$objectData)) //in_array('404',$objectData) true
        {
            return $objectData['status']; 
        }else{
            return ""; //wrong Order No ... order no not match 
        }
    }


    // 1 request 1 sec  
    public function getBigbuyOrderDetailsByBigbiyOrderId($bigbuyOrderId)
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
    }




    /**
     * get bigbuy order id by internalReference
     * 1 request 1 sec
     * @param [type] $internalReference   
     * @return void
     */
    public function getBigbuyOrderNumberByInternalReference($internalReference)
    {
        $curl   = curl_init();
        $apiUrl = bigbuyApiUrl_hd();
        $url = "{$apiUrl}/rest/order/reference/{$internalReference}";
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
        
        $objectData = json_decode($response,true);
        if(is_array($objectData) && !array_key_exists('code',$objectData)) //in_array('404',$objectData) true
        {
            return $objectData['id']; 
        }else{
            return "";
        }
    }




}
