<?php
namespace Topsdk\Topapi\Ability338\Request;
use Topsdk\Topapi\TopUtil;

class AliexpressDsProductGetRequest {

    /**
        Country
     **/
    private $shipToCountry;

    /**
        Item ID
     **/
    private $productId;

    /**
        Target currency
     **/
    private $targetCurrency;

    /**
        Target language
     **/
    private $targetLanguage;


    public function getShipToCountry() : string{
        return $this->shipToCountry;
    }

    public function setShipToCountry(string $shipToCountry){
        $this->shipToCountry = $shipToCountry;
    }

    public function getProductId() : int{
        return $this->productId;
    }

    public function setProductId(int $productId){
        $this->productId = $productId;
    }

    public function getTargetCurrency() : string{
        return $this->targetCurrency;
    }

    public function setTargetCurrency(string $targetCurrency){
        $this->targetCurrency = $targetCurrency;
    }

    public function getTargetLanguage() : string{
        return $this->targetLanguage;
    }

    public function setTargetLanguage(string $targetLanguage){
        $this->targetLanguage = $targetLanguage;
    }


    public function getApiName() : string {
        return "aliexpress.ds.product.get";
    }

    public function toMap() : array{
        $requestParam = array();
        if (!TopUtil::checkEmpty($this->shipToCountry)) {
            $requestParam["ship_to_country"] = TopUtil::convertBasic($this->shipToCountry);
        }

        if (!TopUtil::checkEmpty($this->productId)) {
            $requestParam["product_id"] = TopUtil::convertBasic($this->productId);
        }

        if (!TopUtil::checkEmpty($this->targetCurrency)) {
            $requestParam["target_currency"] = TopUtil::convertBasic($this->targetCurrency);
        }

        if (!TopUtil::checkEmpty($this->targetLanguage)) {
            $requestParam["target_language"] = TopUtil::convertBasic($this->targetLanguage);
        }

        return $requestParam;
    }

    public function toFileParamMap() : array{
        $fileParam = array();
        return $fileParam;
    }

}

