<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsProductGetAeItemSkuInfoDTO {

    /**
        SKU ID
     **/
    private $id;

    /**
        SKU inventory, the data format is true if stock is available, false if no stock is available; at least one sku record is available.
     **/
    private $sku_stock;

    /**
        SKU price. Value range: 0.01-100000; Unit: USD. Such as: 200.07, which means: 200 US dollars 7 points. Need to be in the correct price range.
     **/
    private $sku_price;

    /**
        SKU merchant code. Format: single-byte alphanumeric characters, length 20, excluding spaces greater than and less than signs. If the user only fills in the retail price (productprice) and product code, a complete SKU record needs to be generated and submitted, otherwise the product code cannot be saved. The system will think that only the retail price has been submitted, but there is no SKU, resulting in unsaved product editing.
     **/
    private $sku_code;

    /**
        The actual saleable inventory attribute of SKU is ipmSkuStock. The reasonable value range of this attribute value is 0~999999. If the product has SKU, please make sure that at least one SKU is in stock, that is, the value of ipmSkuStock is 1~999999. The range of the inventory value of the entire product latitude is 1~999999. If the skuStock attribute is set at the same time, the system will give priority to the ipmSkuStock attribute; if the ipmSkuStock attribute is not set, the system will set the inventory according to the skuStock attribute, true means 999, false means 0.
     **/
    private $ipm_sku_stock;

    /**
        The currency unit of the product. U.S. Dollar: USD, Ruble: RUB
     **/
    private $currency_code;

    /**
        SKU attribute object
     **/
    private $ae_sku_property_dtos;

    /**
        Commodity barcode
     **/
    private $barcode;

    /**
        SKU discount price
     **/
    private $offer_sale_price;

    /**
        SKU bulk discount price
     **/
    private $offer_bulk_sale_price;

    /**
        Minimum number of batches
     **/
    private $sku_bulk_order;

    /**
        SKU inventory
     **/
    private $sku_available_stock;


    public function getId() : string{
        return $this->id;
    }

    public function setId(string $id){
        $this->id = $id;
    }

    public function getSkuStock() : bool{
        return $this->sku_stock;
    }

    public function setSkuStock(bool $skuStock){
        $this->sku_stock = $skuStock;
    }

    public function getSkuPrice() : string{
        return $this->sku_price;
    }

    public function setSkuPrice(string $skuPrice){
        $this->sku_price = $skuPrice;
    }

    public function getSkuCode() : string{
        return $this->sku_code;
    }

    public function setSkuCode(string $skuCode){
        $this->sku_code = $skuCode;
    }

    public function getIpmSkuStock() : int{
        return $this->ipm_sku_stock;
    }

    public function setIpmSkuStock(int $ipmSkuStock){
        $this->ipm_sku_stock = $ipmSkuStock;
    }

    public function getCurrencyCode() : string{
        return $this->currency_code;
    }

    public function setCurrencyCode(string $currencyCode){
        $this->currency_code = $currencyCode;
    }

    public function getAeSkuPropertyDtos() : array{
        return $this->ae_sku_property_dtos;
    }

    public function setAeSkuPropertyDtos(array $aeSkuPropertyDtos){
        $this->ae_sku_property_dtos = $aeSkuPropertyDtos;
    }

    public function getBarcode() : string{
        return $this->barcode;
    }

    public function setBarcode(string $barcode){
        $this->barcode = $barcode;
    }

    public function getOfferSalePrice() : string{
        return $this->offer_sale_price;
    }

    public function setOfferSalePrice(string $offerSalePrice){
        $this->offer_sale_price = $offerSalePrice;
    }

    public function getOfferBulkSalePrice() : string{
        return $this->offer_bulk_sale_price;
    }

    public function setOfferBulkSalePrice(string $offerBulkSalePrice){
        $this->offer_bulk_sale_price = $offerBulkSalePrice;
    }

    public function getSkuBulkOrder() : int{
        return $this->sku_bulk_order;
    }

    public function setSkuBulkOrder(int $skuBulkOrder){
        $this->sku_bulk_order = $skuBulkOrder;
    }

    public function getSkuAvailableStock() : int{
        return $this->sku_available_stock;
    }

    public function setSkuAvailableStock(int $skuAvailableStock){
        $this->sku_available_stock = $skuAvailableStock;
    }


}

