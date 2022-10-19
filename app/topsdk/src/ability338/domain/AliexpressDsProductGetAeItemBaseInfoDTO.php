<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsProductGetAeItemBaseInfoDTO {

    /**
        Item ID
     **/
    private $product_id;

    /**
        ID of the category of the product
     **/
    private $category_id;

    /**
        The title of the product
     **/
    private $subject;

    /**
        The currency unit of the commodity. U.S. Dollar: USD, Ruble: RUB
     **/
    private $currency_code;

    /**
        Product status
     **/
    private $product_status_type;

    /**
        Reasons for removal of goods
     **/
    private $ws_display;

    /**
        The date the product was removed from the shelf
     **/
    private $ws_offline_date;

    /**
        Commodity creation time
     **/
    private $gmt_create;

    /**
        Change the time
     **/
    private $gmt_modified;

    /**
        Seller's master account ID
     **/
    private $owner_member_seq_long;

    /**
        Evaluation number
     **/
    private $evaluation_count;

    /**
        Average rating stars, 1-5 stars
     **/
    private $avg_evaluation_rating;

    /**
        Commodity detailed description
     **/
    private $detail;

    /**
        Mobile detailed description
     **/
    private $mobile_detail;


    public function getProductId() : int{
        return $this->product_id;
    }

    public function setProductId(int $productId){
        $this->product_id = $productId;
    }

    public function getCategoryId() : int{
        return $this->category_id;
    }

    public function setCategoryId(int $categoryId){
        $this->category_id = $categoryId;
    }

    public function getSubject() : string{
        return $this->subject;
    }

    public function setSubject(string $subject){
        $this->subject = $subject;
    }

    public function getCurrencyCode() : string{
        return $this->currency_code;
    }

    public function setCurrencyCode(string $currencyCode){
        $this->currency_code = $currencyCode;
    }

    public function getProductStatusType() : string{
        return $this->product_status_type;
    }

    public function setProductStatusType(string $productStatusType){
        $this->product_status_type = $productStatusType;
    }

    public function getWsDisplay() : string{
        return $this->ws_display;
    }

    public function setWsDisplay(string $wsDisplay){
        $this->ws_display = $wsDisplay;
    }

    public function getWsOfflineDate() : string{
        return $this->ws_offline_date;
    }

    public function setWsOfflineDate(string $wsOfflineDate){
        $this->ws_offline_date = $wsOfflineDate;
    }

    public function getGmtCreate() : string{
        return $this->gmt_create;
    }

    public function setGmtCreate(string $gmtCreate){
        $this->gmt_create = $gmtCreate;
    }

    public function getGmtModified() : string{
        return $this->gmt_modified;
    }

    public function setGmtModified(string $gmtModified){
        $this->gmt_modified = $gmtModified;
    }

    public function getOwnerMemberSeqLong() : int{
        return $this->owner_member_seq_long;
    }

    public function setOwnerMemberSeqLong(int $ownerMemberSeqLong){
        $this->owner_member_seq_long = $ownerMemberSeqLong;
    }

    public function getEvaluationCount() : string{
        return $this->evaluation_count;
    }

    public function setEvaluationCount(string $evaluationCount){
        $this->evaluation_count = $evaluationCount;
    }

    public function getAvgEvaluationRating() : string{
        return $this->avg_evaluation_rating;
    }

    public function setAvgEvaluationRating(string $avgEvaluationRating){
        $this->avg_evaluation_rating = $avgEvaluationRating;
    }

    public function getDetail() : string{
        return $this->detail;
    }

    public function setDetail(string $detail){
        $this->detail = $detail;
    }

    public function getMobileDetail() : string{
        return $this->mobile_detail;
    }

    public function setMobileDetail(string $mobileDetail){
        $this->mobile_detail = $mobileDetail;
    }


}

