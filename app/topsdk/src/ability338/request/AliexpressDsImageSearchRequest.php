<?php
namespace Topsdk\Topapi\Ability338\Request;
use Topsdk\Topapi\TopUtil;

class AliexpressDsImageSearchRequest {

    /**
        image name in fileserver，max size 100 KB
     **/
    private $imageFileBytes;

    /**
        target_language:EN,RU,PT,ES,FR,ID,IT,TH,JA,AR,VI,TR,DE,HE,KO,NL,PL,MX,CL,IW,IN
     **/
    private $targetLanguage;

    /**
        target_currency:USD, GBP, CAD, EUR, UAH, MXN, TRY, RUB, BRL, AUD, INR, JPY, IDR, SEK,KRW
     **/
    private $targetCurrency;

    /**
        count of products， max 150.
     **/
    private $productCnt;

    /**
        SALE_PRICE_ASC, SALE_PRICE_DESC, LAST_VOLUME_ASC, LAST_VOLUME_DESC
     **/
    private $sort;

    /**
        optional  Ship to Country
     **/
    private $shptTo;


    public function getImageFileBytes() : string{
        return $this->imageFileBytes;
    }

    public function setImageFileBytes(string $imageFileBytes){
        $this->imageFileBytes = $imageFileBytes;
    }

    public function getTargetLanguage() : string{
        return $this->targetLanguage;
    }

    public function setTargetLanguage(string $targetLanguage){
        $this->targetLanguage = $targetLanguage;
    }

    public function getTargetCurrency() : string{
        return $this->targetCurrency;
    }

    public function setTargetCurrency(string $targetCurrency){
        $this->targetCurrency = $targetCurrency;
    }

    public function getProductCnt() : int{
        return $this->productCnt;
    }

    public function setProductCnt(int $productCnt){
        $this->productCnt = $productCnt;
    }

    public function getSort() : string{
        return $this->sort;
    }

    public function setSort(string $sort){
        $this->sort = $sort;
    }

    public function getShptTo() : string{
        return $this->shptTo;
    }

    public function setShptTo(string $shptTo){
        $this->shptTo = $shptTo;
    }


    public function getApiName() : string {
        return "aliexpress.ds.image.search";
    }

    public function toMap() : array{
        $requestParam = array();
        if (!TopUtil::checkEmpty($this->targetLanguage)) {
            $requestParam["target_language"] = TopUtil::convertBasic($this->targetLanguage);
        }

        if (!TopUtil::checkEmpty($this->targetCurrency)) {
            $requestParam["target_currency"] = TopUtil::convertBasic($this->targetCurrency);
        }

        if (!TopUtil::checkEmpty($this->productCnt)) {
            $requestParam["product_cnt"] = TopUtil::convertBasic($this->productCnt);
        }

        if (!TopUtil::checkEmpty($this->sort)) {
            $requestParam["sort"] = TopUtil::convertBasic($this->sort);
        }

        if (!TopUtil::checkEmpty($this->shptTo)) {
            $requestParam["shpt_to"] = TopUtil::convertBasic($this->shptTo);
        }

        return $requestParam;
    }

    public function toFileParamMap() : array{
        $fileParam = array();
        if (!TopUtil::checkEmpty($this->imageFileBytes)){
            $fileParam["image_file_bytes"] = TopUtil::convertBasic($this->imageFileBytes);
        }
        return $fileParam;
    }

}

