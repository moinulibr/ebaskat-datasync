<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsTradeOrderGetAeStoreSimpleInfo {

    /**
        Store ID
     **/
    private $store_id;

    /**
        Store name
     **/
    private $store_name;

    /**
        Store address
     **/
    private $store_url;


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

    public function getStoreUrl() : string{
        return $this->store_url;
    }

    public function setStoreUrl(string $storeUrl){
        $this->store_url = $storeUrl;
    }


}

