<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsProductGetAeMultimediaInfoDTO {

    /**
        The length of the product
     **/
    private $package_length;

    /**
        Product height
     **/
    private $package_height;

    /**
        Product width
     **/
    private $package_width;

    /**
        The gross weight of the product
     **/
    private $gross_weight;

    /**
        Number of basic products for custom weighing
     **/
    private $base_unit;

    /**
        Type of packaging
     **/
    private $package_type;

    /**
        Unit of commodity
     **/
    private $product_unit;


    public function getPackageLength() : int{
        return $this->package_length;
    }

    public function setPackageLength(int $packageLength){
        $this->package_length = $packageLength;
    }

    public function getPackageHeight() : int{
        return $this->package_height;
    }

    public function setPackageHeight(int $packageHeight){
        $this->package_height = $packageHeight;
    }

    public function getPackageWidth() : int{
        return $this->package_width;
    }

    public function setPackageWidth(int $packageWidth){
        $this->package_width = $packageWidth;
    }

    public function getGrossWeight() : string{
        return $this->gross_weight;
    }

    public function setGrossWeight(string $grossWeight){
        $this->gross_weight = $grossWeight;
    }

    public function getBaseUnit() : int{
        return $this->base_unit;
    }

    public function setBaseUnit(int $baseUnit){
        $this->base_unit = $baseUnit;
    }

    public function getPackageType() : bool{
        return $this->package_type;
    }

    public function setPackageType(bool $packageType){
        $this->package_type = $packageType;
    }

    public function getProductUnit() : int{
        return $this->product_unit;
    }

    public function setProductUnit(int $productUnit){
        $this->product_unit = $productUnit;
    }


}

