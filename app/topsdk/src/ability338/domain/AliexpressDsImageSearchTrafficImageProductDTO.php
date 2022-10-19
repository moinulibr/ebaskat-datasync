<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsImageSearchTrafficImageProductDTO {

    /**
        commodity thumbnail address list
     **/
    private $product_small_image_urls;

    /**
        original price
     **/
    private $original_price;

    /**
        original price currency
     **/
    private $original_price_currency;

    /**
        discount
     **/
    private $discount;

    /**
        latest volume
     **/
    private $lastest_volume;

    /**
        seller id
     **/
    private $seller_id;

    /**
        target sale price
     **/
    private $target_sale_price;

    /**
        evaluate rate
     **/
    private $evaluate_rate;

    /**
        target original price
     **/
    private $target_original_price;

    /**
        shop id
     **/
    private $shop_id;

    /**
        second level category name
     **/
    private $second_level_category_name;

    /**
        first level category id
     **/
    private $first_level_category_id;

    /**
        product vedio url
     **/
    private $product_video_url;

    /**
        product id
     **/
    private $product_id;

    /**
        sale price
     **/
    private $sale_price;

    /**
        target sale price currency
     **/
    private $target_sale_price_currency;

    /**
        second level category id
     **/
    private $second_level_category_id;

    /**
        shop url
     **/
    private $shop_url;

    /**
        product title
     **/
    private $product_title;

    /**
        product detail url
     **/
    private $product_detail_url;

    /**
        first level category name
     **/
    private $first_level_category_name;

    /**
        product main image url
     **/
    private $product_main_image_url;

    /**
        platform product type
     **/
    private $platform_product_type;

    /**
        target original price currency
     **/
    private $target_original_price_currency;

    /**
        sale price currency
     **/
    private $sale_price_currency;


    public function getProductSmallImageUrls() : array{
        return $this->product_small_image_urls;
    }

    public function setProductSmallImageUrls(array $productSmallImageUrls){
        $this->product_small_image_urls = $productSmallImageUrls;
    }

    public function getOriginalPrice() : string{
        return $this->original_price;
    }

    public function setOriginalPrice(string $originalPrice){
        $this->original_price = $originalPrice;
    }

    public function getOriginalPriceCurrency() : string{
        return $this->original_price_currency;
    }

    public function setOriginalPriceCurrency(string $originalPriceCurrency){
        $this->original_price_currency = $originalPriceCurrency;
    }

    public function getDiscount() : string{
        return $this->discount;
    }

    public function setDiscount(string $discount){
        $this->discount = $discount;
    }

    public function getLastestVolume() : string{
        return $this->lastest_volume;
    }

    public function setLastestVolume(string $lastestVolume){
        $this->lastest_volume = $lastestVolume;
    }

    public function getSellerId() : int{
        return $this->seller_id;
    }

    public function setSellerId(int $sellerId){
        $this->seller_id = $sellerId;
    }

    public function getTargetSalePrice() : string{
        return $this->target_sale_price;
    }

    public function setTargetSalePrice(string $targetSalePrice){
        $this->target_sale_price = $targetSalePrice;
    }

    public function getEvaluateRate() : string{
        return $this->evaluate_rate;
    }

    public function setEvaluateRate(string $evaluateRate){
        $this->evaluate_rate = $evaluateRate;
    }

    public function getTargetOriginalPrice() : string{
        return $this->target_original_price;
    }

    public function setTargetOriginalPrice(string $targetOriginalPrice){
        $this->target_original_price = $targetOriginalPrice;
    }

    public function getShopId() : int{
        return $this->shop_id;
    }

    public function setShopId(int $shopId){
        $this->shop_id = $shopId;
    }

    public function getSecondLevelCategoryName() : string{
        return $this->second_level_category_name;
    }

    public function setSecondLevelCategoryName(string $secondLevelCategoryName){
        $this->second_level_category_name = $secondLevelCategoryName;
    }

    public function getFirstLevelCategoryId() : string{
        return $this->first_level_category_id;
    }

    public function setFirstLevelCategoryId(string $firstLevelCategoryId){
        $this->first_level_category_id = $firstLevelCategoryId;
    }

    public function getProductVideoUrl() : string{
        return $this->product_video_url;
    }

    public function setProductVideoUrl(string $productVideoUrl){
        $this->product_video_url = $productVideoUrl;
    }

    public function getProductId() : string{
        return $this->product_id;
    }

    public function setProductId(string $productId){
        $this->product_id = $productId;
    }

    public function getSalePrice() : string{
        return $this->sale_price;
    }

    public function setSalePrice(string $salePrice){
        $this->sale_price = $salePrice;
    }

    public function getTargetSalePriceCurrency() : string{
        return $this->target_sale_price_currency;
    }

    public function setTargetSalePriceCurrency(string $targetSalePriceCurrency){
        $this->target_sale_price_currency = $targetSalePriceCurrency;
    }

    public function getSecondLevelCategoryId() : string{
        return $this->second_level_category_id;
    }

    public function setSecondLevelCategoryId(string $secondLevelCategoryId){
        $this->second_level_category_id = $secondLevelCategoryId;
    }

    public function getShopUrl() : string{
        return $this->shop_url;
    }

    public function setShopUrl(string $shopUrl){
        $this->shop_url = $shopUrl;
    }

    public function getProductTitle() : string{
        return $this->product_title;
    }

    public function setProductTitle(string $productTitle){
        $this->product_title = $productTitle;
    }

    public function getProductDetailUrl() : string{
        return $this->product_detail_url;
    }

    public function setProductDetailUrl(string $productDetailUrl){
        $this->product_detail_url = $productDetailUrl;
    }

    public function getFirstLevelCategoryName() : string{
        return $this->first_level_category_name;
    }

    public function setFirstLevelCategoryName(string $firstLevelCategoryName){
        $this->first_level_category_name = $firstLevelCategoryName;
    }

    public function getProductMainImageUrl() : string{
        return $this->product_main_image_url;
    }

    public function setProductMainImageUrl(string $productMainImageUrl){
        $this->product_main_image_url = $productMainImageUrl;
    }

    public function getPlatformProductType() : string{
        return $this->platform_product_type;
    }

    public function setPlatformProductType(string $platformProductType){
        $this->platform_product_type = $platformProductType;
    }

    public function getTargetOriginalPriceCurrency() : string{
        return $this->target_original_price_currency;
    }

    public function setTargetOriginalPriceCurrency(string $targetOriginalPriceCurrency){
        $this->target_original_price_currency = $targetOriginalPriceCurrency;
    }

    public function getSalePriceCurrency() : string{
        return $this->sale_price_currency;
    }

    public function setSalePriceCurrency(string $salePriceCurrency){
        $this->sale_price_currency = $salePriceCurrency;
    }


}

