<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsCommissionorderListbyindexTrafficOrderEffectDTO {

    /**
        commission rate
     **/
    private $commission_rate;

    /**
        order number
     **/
    private $order_number;

    /**
        finished amount of the order, unit cent
     **/
    private $finished_amount;

    /**
        is affiliate product
     **/
    private $is_affiliate_product;

    /**
        is new buyer
     **/
    private $is_new_buyer;

    /**
        item title
     **/
    private $item_title;

    /**
        Additional order status, eg: full refund order, antispam order
     **/
    private $effect_detail_status;

    /**
        estimated commission for finished incentive order
     **/
    private $estimated_incentive_finished_commission;

    /**
        estimated commission for paid incentive order
     **/
    private $estimated_incentive_paid_commission;

    /**
        publisher id
     **/
    private $publisher_id;

    /**
        is hot product
     **/
    private $is_hot_product;

    /**
        parent order number
     **/
    private $parent_order_number;

    /**
        item detail url
     **/
    private $item_detail_url;

    /**
        created time of this order
     **/
    private $created_time;

    /**
        publisher settled currency
     **/
    private $publisher_settled_currency;

    /**
        product shipping country
     **/
    private $ship_to_country;

    /**
        order id
     **/
    private $order_id;

    /**
        item main image url
     **/
    private $item_main_image_url;

    /**
        paid time
     **/
    private $paid_time;

    /**
        item count
     **/
    private $item_count;

    /**
        item id
     **/
    private $item_id;

    /**
        effect status
     **/
    private $effect_status;

    /**
        estimated commission for finished order
     **/
    private $estimated_finished_commission;

    /**
        sub order id
     **/
    private $sub_order_id;

    /**
        estimated commission for paid order
     **/
    private $estimated_paid_commission;

    /**
        Order finish time
     **/
    private $finished_time;

    /**
        payment amount of the order, unit cent
     **/
    private $paid_amount;

    /**
        category id
     **/
    private $category_id;

    /**
        incentive commission rate
     **/
    private $incentive_commission_rate;


    public function getCommissionRate() : string{
        return $this->commission_rate;
    }

    public function setCommissionRate(string $commissionRate){
        $this->commission_rate = $commissionRate;
    }

    public function getOrderNumber() : int{
        return $this->order_number;
    }

    public function setOrderNumber(int $orderNumber){
        $this->order_number = $orderNumber;
    }

    public function getFinishedAmount() : string{
        return $this->finished_amount;
    }

    public function setFinishedAmount(string $finishedAmount){
        $this->finished_amount = $finishedAmount;
    }

    public function getIsAffiliateProduct() : string{
        return $this->is_affiliate_product;
    }

    public function setIsAffiliateProduct(string $isAffiliateProduct){
        $this->is_affiliate_product = $isAffiliateProduct;
    }

    public function getIsNewBuyer() : string{
        return $this->is_new_buyer;
    }

    public function setIsNewBuyer(string $isNewBuyer){
        $this->is_new_buyer = $isNewBuyer;
    }

    public function getItemTitle() : string{
        return $this->item_title;
    }

    public function setItemTitle(string $itemTitle){
        $this->item_title = $itemTitle;
    }

    public function getEffectDetailStatus() : string{
        return $this->effect_detail_status;
    }

    public function setEffectDetailStatus(string $effectDetailStatus){
        $this->effect_detail_status = $effectDetailStatus;
    }

    public function getEstimatedIncentiveFinishedCommission() : string{
        return $this->estimated_incentive_finished_commission;
    }

    public function setEstimatedIncentiveFinishedCommission(string $estimatedIncentiveFinishedCommission){
        $this->estimated_incentive_finished_commission = $estimatedIncentiveFinishedCommission;
    }

    public function getEstimatedIncentivePaidCommission() : string{
        return $this->estimated_incentive_paid_commission;
    }

    public function setEstimatedIncentivePaidCommission(string $estimatedIncentivePaidCommission){
        $this->estimated_incentive_paid_commission = $estimatedIncentivePaidCommission;
    }

    public function getPublisherId() : int{
        return $this->publisher_id;
    }

    public function setPublisherId(int $publisherId){
        $this->publisher_id = $publisherId;
    }

    public function getIsHotProduct() : string{
        return $this->is_hot_product;
    }

    public function setIsHotProduct(string $isHotProduct){
        $this->is_hot_product = $isHotProduct;
    }

    public function getParentOrderNumber() : int{
        return $this->parent_order_number;
    }

    public function setParentOrderNumber(int $parentOrderNumber){
        $this->parent_order_number = $parentOrderNumber;
    }

    public function getItemDetailUrl() : string{
        return $this->item_detail_url;
    }

    public function setItemDetailUrl(string $itemDetailUrl){
        $this->item_detail_url = $itemDetailUrl;
    }

    public function getCreatedTime() : string{
        return $this->created_time;
    }

    public function setCreatedTime(string $createdTime){
        $this->created_time = $createdTime;
    }

    public function getPublisherSettledCurrency() : string{
        return $this->publisher_settled_currency;
    }

    public function setPublisherSettledCurrency(string $publisherSettledCurrency){
        $this->publisher_settled_currency = $publisherSettledCurrency;
    }

    public function getShipToCountry() : string{
        return $this->ship_to_country;
    }

    public function setShipToCountry(string $shipToCountry){
        $this->ship_to_country = $shipToCountry;
    }

    public function getOrderId() : int{
        return $this->order_id;
    }

    public function setOrderId(int $orderId){
        $this->order_id = $orderId;
    }

    public function getItemMainImageUrl() : string{
        return $this->item_main_image_url;
    }

    public function setItemMainImageUrl(string $itemMainImageUrl){
        $this->item_main_image_url = $itemMainImageUrl;
    }

    public function getPaidTime() : string{
        return $this->paid_time;
    }

    public function setPaidTime(string $paidTime){
        $this->paid_time = $paidTime;
    }

    public function getItemCount() : string{
        return $this->item_count;
    }

    public function setItemCount(string $itemCount){
        $this->item_count = $itemCount;
    }

    public function getItemId() : int{
        return $this->item_id;
    }

    public function setItemId(int $itemId){
        $this->item_id = $itemId;
    }

    public function getEffectStatus() : string{
        return $this->effect_status;
    }

    public function setEffectStatus(string $effectStatus){
        $this->effect_status = $effectStatus;
    }

    public function getEstimatedFinishedCommission() : string{
        return $this->estimated_finished_commission;
    }

    public function setEstimatedFinishedCommission(string $estimatedFinishedCommission){
        $this->estimated_finished_commission = $estimatedFinishedCommission;
    }

    public function getSubOrderId() : int{
        return $this->sub_order_id;
    }

    public function setSubOrderId(int $subOrderId){
        $this->sub_order_id = $subOrderId;
    }

    public function getEstimatedPaidCommission() : int{
        return $this->estimated_paid_commission;
    }

    public function setEstimatedPaidCommission(int $estimatedPaidCommission){
        $this->estimated_paid_commission = $estimatedPaidCommission;
    }

    public function getFinishedTime() : string{
        return $this->finished_time;
    }

    public function setFinishedTime(string $finishedTime){
        $this->finished_time = $finishedTime;
    }

    public function getPaidAmount() : int{
        return $this->paid_amount;
    }

    public function setPaidAmount(int $paidAmount){
        $this->paid_amount = $paidAmount;
    }

    public function getCategoryId() : int{
        return $this->category_id;
    }

    public function setCategoryId(int $categoryId){
        $this->category_id = $categoryId;
    }

    public function getIncentiveCommissionRate() : string{
        return $this->incentive_commission_rate;
    }

    public function setIncentiveCommissionRate(string $incentiveCommissionRate){
        $this->incentive_commission_rate = $incentiveCommissionRate;
    }


}

