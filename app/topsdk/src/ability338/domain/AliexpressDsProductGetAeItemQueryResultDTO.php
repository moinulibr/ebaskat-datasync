<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsProductGetAeItemQueryResultDTO {

    /**
        Basic commodity information
     **/
    private $ae_item_base_info_dto;

    /**
        SKU information
     **/
    private $ae_item_sku_info_dtos;

    /**
        Multimedia information
     **/
    private $ae_multimedia_info_dto;

    /**
        Package information
     **/
    private $package_info_dto;

    /**
        Logistics information
     **/
    private $logistics_info_dto;

    /**
        Attribute information
     **/
    private $ae_item_properties;

    /**
        Store Information
     **/
    private $ae_store_info;

    /**
        product id converter result
     **/
    private $product_id_converter_result;


    public function getAeItemBaseInfoDto() : AliexpressDsProductGetAeItemBaseInfoDTO{
        return $this->ae_item_base_info_dto;
    }

    public function setAeItemBaseInfoDto(AliexpressDsProductGetAeItemBaseInfoDTO $aeItemBaseInfoDto){
        $this->ae_item_base_info_dto = $aeItemBaseInfoDto;
    }

    public function getAeItemSkuInfoDtos() : array{
        return $this->ae_item_sku_info_dtos;
    }

    public function setAeItemSkuInfoDtos(array $aeItemSkuInfoDtos){
        $this->ae_item_sku_info_dtos = $aeItemSkuInfoDtos;
    }

    public function getAeMultimediaInfoDto() : AliexpressDsProductGetAeItemSkuInfoDTO{
        return $this->ae_multimedia_info_dto;
    }

    public function setAeMultimediaInfoDto(AliexpressDsProductGetAeItemSkuInfoDTO $aeMultimediaInfoDto){
        $this->ae_multimedia_info_dto = $aeMultimediaInfoDto;
    }

    public function getPackageInfoDto() : AliexpressDsProductGetAeMultimediaInfoDTO{
        return $this->package_info_dto;
    }

    public function setPackageInfoDto(AliexpressDsProductGetAeMultimediaInfoDTO $packageInfoDto){
        $this->package_info_dto = $packageInfoDto;
    }

    public function getLogisticsInfoDto() : AliexpressDsProductGetPackageInfoDTO{
        return $this->logistics_info_dto;
    }

    public function setLogisticsInfoDto(AliexpressDsProductGetPackageInfoDTO $logisticsInfoDto){
        $this->logistics_info_dto = $logisticsInfoDto;
    }

    public function getAeItemProperties() : array{
        return $this->ae_item_properties;
    }

    public function setAeItemProperties(array $aeItemProperties){
        $this->ae_item_properties = $aeItemProperties;
    }

    public function getAeStoreInfo() : AliexpressDsProductGetAeItemProperty{
        return $this->ae_store_info;
    }

    public function setAeStoreInfo(AliexpressDsProductGetAeItemProperty $aeStoreInfo){
        $this->ae_store_info = $aeStoreInfo;
    }

    public function getProductIdConverterResult() : AliexpressDsProductGetProductIdConverterResultDTO{
        return $this->product_id_converter_result;
    }

    public function setProductIdConverterResult(AliexpressDsProductGetProductIdConverterResultDTO $productIdConverterResult){
        $this->product_id_converter_result = $productIdConverterResult;
    }


}

