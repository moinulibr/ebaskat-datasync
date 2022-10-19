<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsTradeOrderGetAeChildOrderInfo {

    /**
        Item ID
     **/
    private $product_id;

    /**
        Item name
     **/
    private $product_name;

    /**
        Item price
     **/
    private $product_price;

    /**
        Item quantity
     **/
    private $product_count;


    public function getProductId() : int{
        return $this->product_id;
    }

    public function setProductId(int $productId){
        $this->product_id = $productId;
    }

    public function getProductName() : string{
        return $this->product_name;
    }

    public function setProductName(string $productName){
        $this->product_name = $productName;
    }

    public function getProductPrice() : AliexpressDsTradeOrderGetSimpleMoney{
        return $this->product_price;
    }

    public function setProductPrice(AliexpressDsTradeOrderGetSimpleMoney $productPrice){
        $this->product_price = $productPrice;
    }

    public function getProductCount() : int{
        return $this->product_count;
    }

    public function setProductCount(int $productCount){
        $this->product_count = $productCount;
    }


}

