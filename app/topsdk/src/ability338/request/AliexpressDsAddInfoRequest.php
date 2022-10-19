<?php
namespace Topsdk\Topapi\Ability338\Request;
use Topsdk\Topapi\TopUtil;
use Topsdk\Topapi\Ability338\Domain\AliexpressDsAddInfoDropShipperReq;

class AliexpressDsAddInfoRequest {

    /**
        Request object
     **/
    private $param0;


    public function getParam0() : AliexpressDsAddInfoDropShipperReq{
        return $this->param0;
    }

    public function setParam0(AliexpressDsAddInfoDropShipperReq $param0){
        $this->param0 = $param0;
    }


    public function getApiName() : string {
        return "aliexpress.ds.add.info";
    }

    public function toMap() : array{
        $requestParam = array();
        if (!TopUtil::checkEmpty($this->param0)) {
            $requestParam["param0"] = TopUtil::convertStruct($this->param0);
        }

        return $requestParam;
    }

    public function toFileParamMap() : array{
        $fileParam = array();
        return $fileParam;
    }

}

