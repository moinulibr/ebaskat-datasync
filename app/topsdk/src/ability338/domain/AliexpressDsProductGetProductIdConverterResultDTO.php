<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsProductGetProductIdConverterResultDTO {

    /**
        main productId
     **/
    private $main_product_id;

    /**
        sub productId
     **/
    private $sub_product_id;


    public function getMainProductId() : int{
        return $this->main_product_id;
    }

    public function setMainProductId(int $mainProductId){
        $this->main_product_id = $mainProductId;
    }

    public function getSubProductId() : array{
        return $this->sub_product_id;
    }

    public function setSubProductId(array $subProductId){
        $this->sub_product_id = $subProductId;
    }


}

