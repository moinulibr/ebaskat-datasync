<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsTradeOrderGetAeOrderInfoResultDTO {

    /**
        Order creation time
     **/
    private $gmt_create;

    /**
        Order logistics information list
     **/
    private $logistics_info_list;

    /**
        Logistics status
     **/
    private $logistics_status;

    /**
        Order Status
     **/
    private $order_status;

    /**
        Order amount
     **/
    private $order_amount;

    /**
        Sub-order list
     **/
    private $child_order_list;

    /**
        Store Information
     **/
    private $store_info;


    public function getGmtCreate() : string{
        return $this->gmt_create;
    }

    public function setGmtCreate(string $gmtCreate){
        $this->gmt_create = $gmtCreate;
    }

    public function getLogisticsInfoList() : array{
        return $this->logistics_info_list;
    }

    public function setLogisticsInfoList(array $logisticsInfoList){
        $this->logistics_info_list = $logisticsInfoList;
    }

    public function getLogisticsStatus() : string{
        return $this->logistics_status;
    }

    public function setLogisticsStatus(string $logisticsStatus){
        $this->logistics_status = $logisticsStatus;
    }

    public function getOrderStatus() : string{
        return $this->order_status;
    }

    public function setOrderStatus(string $orderStatus){
        $this->order_status = $orderStatus;
    }

    public function getOrderAmount() : AliexpressDsTradeOrderGetSimpleMoney{
        return $this->order_amount;
    }

    public function setOrderAmount(AliexpressDsTradeOrderGetSimpleMoney $orderAmount){
        $this->order_amount = $orderAmount;
    }

    public function getChildOrderList() : array{
        return $this->child_order_list;
    }

    public function setChildOrderList(array $childOrderList){
        $this->child_order_list = $childOrderList;
    }

    public function getStoreInfo() : AliexpressDsTradeOrderGetAeStoreSimpleInfo{
        return $this->store_info;
    }

    public function setStoreInfo(AliexpressDsTradeOrderGetAeStoreSimpleInfo $storeInfo){
        $this->store_info = $storeInfo;
    }


}

