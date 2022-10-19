<?php
namespace Topsdk\Topapi\Ability338\Request;
use Topsdk\Topapi\TopUtil;

class AliexpressDsMemberOrderdataSubmitRequest {

    /**
        AE product ID
     **/
    private $aeProductId;

    /**
        SKU sales amount outside the station, to 2 decimal places
     **/
    private $productAmount;

    /**
        Order sales amount outside the station, keep 2 decimal places
     **/
    private $orderAmount;

    /**
        Off-site payment time, GMT time, format YYYYMMDD:HHMMSS
     **/
    private $paytime;

    /**
        AE product SKU information, SKU key-value pair: "200000182:193;200007763:201336100"
     **/
    private $aeSkuInfo;

    /**
        Commodity site url
     **/
    private $productUrl;

    /**
        AE order id
     **/
    private $aeOrderid;


    public function getAeProductId() : string{
        return $this->aeProductId;
    }

    public function setAeProductId(string $aeProductId){
        $this->aeProductId = $aeProductId;
    }

    public function getProductAmount() : string{
        return $this->productAmount;
    }

    public function setProductAmount(string $productAmount){
        $this->productAmount = $productAmount;
    }

    public function getOrderAmount() : string{
        return $this->orderAmount;
    }

    public function setOrderAmount(string $orderAmount){
        $this->orderAmount = $orderAmount;
    }

    public function getPaytime() : string{
        return $this->paytime;
    }

    public function setPaytime(string $paytime){
        $this->paytime = $paytime;
    }

    public function getAeSkuInfo() : string{
        return $this->aeSkuInfo;
    }

    public function setAeSkuInfo(string $aeSkuInfo){
        $this->aeSkuInfo = $aeSkuInfo;
    }

    public function getProductUrl() : string{
        return $this->productUrl;
    }

    public function setProductUrl(string $productUrl){
        $this->productUrl = $productUrl;
    }

    public function getAeOrderid() : string{
        return $this->aeOrderid;
    }

    public function setAeOrderid(string $aeOrderid){
        $this->aeOrderid = $aeOrderid;
    }


    public function getApiName() : string {
        return "aliexpress.ds.member.orderdata.submit";
    }

    public function toMap() : array{
        $requestParam = array();
        if (!TopUtil::checkEmpty($this->aeProductId)) {
            $requestParam["ae_product_id"] = TopUtil::convertBasic($this->aeProductId);
        }

        if (!TopUtil::checkEmpty($this->productAmount)) {
            $requestParam["product_amount"] = TopUtil::convertBasic($this->productAmount);
        }

        if (!TopUtil::checkEmpty($this->orderAmount)) {
            $requestParam["order_amount"] = TopUtil::convertBasic($this->orderAmount);
        }

        if (!TopUtil::checkEmpty($this->paytime)) {
            $requestParam["paytime"] = TopUtil::convertBasic($this->paytime);
        }

        if (!TopUtil::checkEmpty($this->aeSkuInfo)) {
            $requestParam["ae_sku_info"] = TopUtil::convertBasic($this->aeSkuInfo);
        }

        if (!TopUtil::checkEmpty($this->productUrl)) {
            $requestParam["product_url"] = TopUtil::convertBasic($this->productUrl);
        }

        if (!TopUtil::checkEmpty($this->aeOrderid)) {
            $requestParam["ae_orderid"] = TopUtil::convertBasic($this->aeOrderid);
        }

        return $requestParam;
    }

    public function toFileParamMap() : array{
        $fileParam = array();
        return $fileParam;
    }

}

