<?php
namespace Topsdk\Topapi\Ability338\Request;
use Topsdk\Topapi\TopUtil;

class AliexpressDsRecommendFeedGetRequest {

    /**
        screens the subject product library for the target country
     **/
    private $country;

    /**
        target currency:USD, GBP, CAD, EUR, UAH, MXN, TRY, RUB, BRL, AUD, INR, JPY, IDR, SEK,KRW
     **/
    private $targetCurrency;

    /**
        target language:EN,RU,PT,ES,FR,ID,IT,TH,JA,AR,VI,TR,DE,HE,KO,NL,PL,MX,CL,IN
     **/
    private $targetLanguage;

    /**
        record count of each page, 1 - 50
     **/
    private $pageSize;

    /**
        sort by：priceAsc，priceDesc，volumeAsc、volumeDesc, discountAsc, discountDesc, DSRratingAsc，DSRratingDesc,
     **/
    private $sort;

    /**
        Page number
     **/
    private $pageNo;

    /**
        Category ID, you can get category ID via "get category" API https://developers.aliexpress.com/en/doc.htm?docId=45801&docType=2
     **/
    private $categoryId;

    /**
        feed name, eg. "DS bestseller"
     **/
    private $feedName;


    public function getCountry() : string{
        return $this->country;
    }

    public function setCountry(string $country){
        $this->country = $country;
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

    public function getPageSize() : int{
        return $this->pageSize;
    }

    public function setPageSize(int $pageSize){
        $this->pageSize = $pageSize;
    }

    public function getSort() : string{
        return $this->sort;
    }

    public function setSort(string $sort){
        $this->sort = $sort;
    }

    public function getPageNo() : int{
        return $this->pageNo;
    }

    public function setPageNo(int $pageNo){
        $this->pageNo = $pageNo;
    }

    public function getCategoryId() : string{
        return $this->categoryId;
    }

    public function setCategoryId(string $categoryId){
        $this->categoryId = $categoryId;
    }

    public function getFeedName() : string{
        return $this->feedName;
    }

    public function setFeedName(string $feedName){
        $this->feedName = $feedName;
    }


    public function getApiName() : string {
        return "aliexpress.ds.recommend.feed.get";
    }

    public function toMap() : array{
        $requestParam = array();
        if (!TopUtil::checkEmpty($this->country)) {
            $requestParam["country"] = TopUtil::convertBasic($this->country);
        }

        if (!TopUtil::checkEmpty($this->targetCurrency)) {
            $requestParam["target_currency"] = TopUtil::convertBasic($this->targetCurrency);
        }

        if (!TopUtil::checkEmpty($this->targetLanguage)) {
            $requestParam["target_language"] = TopUtil::convertBasic($this->targetLanguage);
        }

        if (!TopUtil::checkEmpty($this->pageSize)) {
            $requestParam["page_size"] = TopUtil::convertBasic($this->pageSize);
        }

        if (!TopUtil::checkEmpty($this->sort)) {
            $requestParam["sort"] = TopUtil::convertBasic($this->sort);
        }

        if (!TopUtil::checkEmpty($this->pageNo)) {
            $requestParam["page_no"] = TopUtil::convertBasic($this->pageNo);
        }

        if (!TopUtil::checkEmpty($this->categoryId)) {
            $requestParam["category_id"] = TopUtil::convertBasic($this->categoryId);
        }

        if (!TopUtil::checkEmpty($this->feedName)) {
            $requestParam["feed_name"] = TopUtil::convertBasic($this->feedName);
        }

        return $requestParam;
    }

    public function toFileParamMap() : array{
        $fileParam = array();
        return $fileParam;
    }

}

