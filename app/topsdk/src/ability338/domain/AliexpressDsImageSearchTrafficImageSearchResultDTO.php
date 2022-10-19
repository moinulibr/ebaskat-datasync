<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsImageSearchTrafficImageSearchResultDTO {

    /**
        products
     **/
    private $products;


    public function getProducts() : array{
        return $this->products;
    }

    public function setProducts(array $products){
        $this->products = $products;
    }


}

