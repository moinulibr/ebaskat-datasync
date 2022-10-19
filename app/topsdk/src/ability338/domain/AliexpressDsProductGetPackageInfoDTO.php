<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsProductGetPackageInfoDTO {

    /**
        Goods lead time
     **/
    private $delivery_time;

    /**
        Country
     **/
    private $ship_to_country;


    public function getDeliveryTime() : int{
        return $this->delivery_time;
    }

    public function setDeliveryTime(int $deliveryTime){
        $this->delivery_time = $deliveryTime;
    }

    public function getShipToCountry() : string{
        return $this->ship_to_country;
    }

    public function setShipToCountry(string $shipToCountry){
        $this->ship_to_country = $shipToCountry;
    }


}

