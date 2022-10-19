<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsProductGetAeItemProperty {

    /**
        Store ID
     **/
    private $store_id;

    /**
        Shop name
     **/
    private $store_name;

    /**
        Product description, 1-5 stars
     **/
    private $item_as_described_rating;

    /**
        Seller service, 1-5 stars
     **/
    private $communication_rating;

    /**
        Logistics, 1-5 stars
     **/
    private $shipping_speed_rating;


    public function getStoreId() : int{
        return $this->store_id;
    }

    public function setStoreId(int $storeId){
        $this->store_id = $storeId;
    }

    public function getStoreName() : string{
        return $this->store_name;
    }

    public function setStoreName(string $storeName){
        $this->store_name = $storeName;
    }

    public function getItemAsDescribedRating() : string{
        return $this->item_as_described_rating;
    }

    public function setItemAsDescribedRating(string $itemAsDescribedRating){
        $this->item_as_described_rating = $itemAsDescribedRating;
    }

    public function getCommunicationRating() : string{
        return $this->communication_rating;
    }

    public function setCommunicationRating(string $communicationRating){
        $this->communication_rating = $communicationRating;
    }

    public function getShippingSpeedRating() : string{
        return $this->shipping_speed_rating;
    }

    public function setShippingSpeedRating(string $shippingSpeedRating){
        $this->shipping_speed_rating = $shippingSpeedRating;
    }


}

