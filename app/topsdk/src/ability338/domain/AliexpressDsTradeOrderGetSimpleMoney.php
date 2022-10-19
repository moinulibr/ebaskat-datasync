<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsTradeOrderGetSimpleMoney {

    /**
        Amount
     **/
    private $amount;

    /**
        Currency
     **/
    private $currency_code;


    public function getAmount() : string{
        return $this->amount;
    }

    public function setAmount(string $amount){
        $this->amount = $amount;
    }

    public function getCurrencyCode() : string{
        return $this->currency_code;
    }

    public function setCurrencyCode(string $currencyCode){
        $this->currency_code = $currencyCode;
    }


}

