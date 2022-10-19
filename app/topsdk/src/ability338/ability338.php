<?php
namespace Topsdk\Topapi\Ability338;

use Topsdk\Topapi\TopApiClient;
use Topsdk\Topapi\Ability338\Request\AliexpressDsRecommendFeedGetRequest;
use Topsdk\Topapi\Ability338\Request\AliexpressDsAddInfoRequest;
use Topsdk\Topapi\Ability338\Request\AliexpressDsProductGetRequest;
use Topsdk\Topapi\Ability338\Request\AliexpressDsImageSearchRequest;
use Topsdk\Topapi\Ability338\Request\AliexpressDsCommissionorderListbyindexRequest;
use Topsdk\Topapi\Ability338\Request\AliexpressDsTradeOrderGetRequest;
use Topsdk\Topapi\Ability338\Request\AliexpressDsMemberOrderdataSubmitRequest;

class Ability338 {

    public $client;

    function __construct(TopApiClient $client) {
        $this->client = $client;
    }


    /**
        获取推荐商品信息流接口
    **/
    public function aliexpressDsRecommendFeedGet(AliexpressDsRecommendFeedGetRequest $request,string $session) {
        return $this->client->executeWithSession("aliexpress.ds.recommend.feed.get", $request->toMap(), $request->toFileParamMap(), $session);
    }
    /**
        上报DS信息
    **/
    public function aliexpressDsAddInfo(AliexpressDsAddInfoRequest $request,string $session) {
        return $this->client->executeWithSession("aliexpress.ds.add.info", $request->toMap(), $request->toFileParamMap(), $session);
    }
    /**
        商品信息查询
    **/
    public function aliexpressDsProductGet(AliexpressDsProductGetRequest $request,string $session) {
        return $this->client->executeWithSession("aliexpress.ds.product.get", $request->toMap(), $request->toFileParamMap(), $session);
    }
    /**
        图片搜索
    **/
    public function aliexpressDsImageSearch(AliexpressDsImageSearchRequest $request) {
        return $this->client->execute("aliexpress.ds.image.search", $request->toMap(), $request->toFileParamMap());
    }
    /**
        联盟订单分页查询
    **/
    public function aliexpressDsCommissionorderListbyindex(AliexpressDsCommissionorderListbyindexRequest $request) {
        return $this->client->execute("aliexpress.ds.commissionorder.listbyindex", $request->toMap(), $request->toFileParamMap());
    }
    /**
        交易订单查询
    **/
    public function aliexpressDsTradeOrderGet(AliexpressDsTradeOrderGetRequest $request,string $session) {
        return $this->client->executeWithSession("aliexpress.ds.trade.order.get", $request->toMap(), $request->toFileParamMap(), $session);
    }
    /**
        dropshipper数据回流
    **/
    public function aliexpressDsMemberOrderdataSubmit(AliexpressDsMemberOrderdataSubmitRequest $request,string $session) {
        return $this->client->executeWithSession("aliexpress.ds.member.orderdata.submit", $request->toMap(), $request->toFileParamMap(), $session);
    }
}