<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsProductGetAeSkuPropertyDTO {

    /**
        Attribute ID
     **/
    private $sku_property_id;

    /**
        Attribute name
     **/
    private $sku_property_name;

    /**
        Attribute value
     **/
    private $sku_property_value;

    /**
        Custom id
     **/
    private $property_value_id;

    /**
        Custom name
     **/
    private $property_value_definition_name;

    /**
        SKU pictures
     **/
    private $sku_image;


    public function getSkuPropertyId() : int{
        return $this->sku_property_id;
    }

    public function setSkuPropertyId(int $skuPropertyId){
        $this->sku_property_id = $skuPropertyId;
    }

    public function getSkuPropertyName() : string{
        return $this->sku_property_name;
    }

    public function setSkuPropertyName(string $skuPropertyName){
        $this->sku_property_name = $skuPropertyName;
    }

    public function getSkuPropertyValue() : string{
        return $this->sku_property_value;
    }

    public function setSkuPropertyValue(string $skuPropertyValue){
        $this->sku_property_value = $skuPropertyValue;
    }

    public function getPropertyValueId() : int{
        return $this->property_value_id;
    }

    public function setPropertyValueId(int $propertyValueId){
        $this->property_value_id = $propertyValueId;
    }

    public function getPropertyValueDefinitionName() : string{
        return $this->property_value_definition_name;
    }

    public function setPropertyValueDefinitionName(string $propertyValueDefinitionName){
        $this->property_value_definition_name = $propertyValueDefinitionName;
    }

    public function getSkuImage() : string{
        return $this->sku_image;
    }

    public function setSkuImage(string $skuImage){
        $this->sku_image = $skuImage;
    }


}

