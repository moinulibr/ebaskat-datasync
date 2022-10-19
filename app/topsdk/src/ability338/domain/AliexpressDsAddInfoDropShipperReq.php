<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsAddInfoDropShipperReq {

    /**
        Store address
     **/
    private $store_url;


    public function getStoreUrl() : string{
        return $this->store_url;
    }

    public function setStoreUrl(string $storeUrl){
        $this->store_url = $storeUrl;
    }


}

