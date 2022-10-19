<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsTradeOrderGetAeOrderLogisticsInfo {

    /**
        Logistics tracking number
     **/
    private $logistics_no;

    /**
        Logistics Services
     **/
    private $logistics_service;


    public function getLogisticsNo() : string{
        return $this->logistics_no;
    }

    public function setLogisticsNo(string $logisticsNo){
        $this->logistics_no = $logisticsNo;
    }

    public function getLogisticsService() : string{
        return $this->logistics_service;
    }

    public function setLogisticsService(string $logisticsService){
        $this->logistics_service = $logisticsService;
    }


}

