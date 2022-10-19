<?php
namespace Topsdk\Topapi\Ability338\Request;
use Topsdk\Topapi\TopUtil;

class AliexpressDsTradeOrderGetRequest {

    /**
        AE order id
     **/
    private $orderId;


    public function getOrderId() : int{
        return $this->orderId;
    }

    public function setOrderId(int $orderId){
        $this->orderId = $orderId;
    }


    public function getApiName() : string {
        return "aliexpress.ds.trade.order.get";
    }

    public function toMap() : array{
        $requestParam = array();
        if (!TopUtil::checkEmpty($this->orderId)) {
            $requestParam["order_id"] = TopUtil::convertBasic($this->orderId);
        }

        return $requestParam;
    }

    public function toFileParamMap() : array{
        $fileParam = array();
        return $fileParam;
    }

}

