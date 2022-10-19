<?php
namespace Topsdk\Topapi\Ability338\Request;
use Topsdk\Topapi\TopUtil;

class AliexpressDsCommissionorderListbyindexRequest {

    /**
        record count of each page, 1 - 50
     **/
    private $pageSize;

    /**
        Query index start value: if not passed, You can only check the first page
     **/
    private $startQueryIndexId;

    /**
        page number
     **/
    private $pageNo;

    /**
        Start time, PST time
     **/
    private $startTime;

    /**
        End time, PST time
     **/
    private $endTime;

    /**
        Order status: Payment Completed(Buyer paid successfully), Buyer Confirmed Receipt(This status only change when :Buyer confirms receipt and settlement task begins which is manually executed by our operation team)
     **/
    private $status;


    public function getPageSize() : int{
        return $this->pageSize;
    }

    public function setPageSize(int $pageSize){
        $this->pageSize = $pageSize;
    }

    public function getStartQueryIndexId() : string{
        return $this->startQueryIndexId;
    }

    public function setStartQueryIndexId(string $startQueryIndexId){
        $this->startQueryIndexId = $startQueryIndexId;
    }

    public function getPageNo() : int{
        return $this->pageNo;
    }

    public function setPageNo(int $pageNo){
        $this->pageNo = $pageNo;
    }

    public function getStartTime() : string{
        return $this->startTime;
    }

    public function setStartTime(string $startTime){
        $this->startTime = $startTime;
    }

    public function getEndTime() : string{
        return $this->endTime;
    }

    public function setEndTime(string $endTime){
        $this->endTime = $endTime;
    }

    public function getStatus() : string{
        return $this->status;
    }

    public function setStatus(string $status){
        $this->status = $status;
    }


    public function getApiName() : string {
        return "aliexpress.ds.commissionorder.listbyindex";
    }

    public function toMap() : array{
        $requestParam = array();
        if (!TopUtil::checkEmpty($this->pageSize)) {
            $requestParam["page_size"] = TopUtil::convertBasic($this->pageSize);
        }

        if (!TopUtil::checkEmpty($this->startQueryIndexId)) {
            $requestParam["start_query_index_id"] = TopUtil::convertBasic($this->startQueryIndexId);
        }

        if (!TopUtil::checkEmpty($this->pageNo)) {
            $requestParam["page_no"] = TopUtil::convertBasic($this->pageNo);
        }

        if (!TopUtil::checkEmpty($this->startTime)) {
            $requestParam["start_time"] = TopUtil::convertBasic($this->startTime);
        }

        if (!TopUtil::checkEmpty($this->endTime)) {
            $requestParam["end_time"] = TopUtil::convertBasic($this->endTime);
        }

        if (!TopUtil::checkEmpty($this->status)) {
            $requestParam["status"] = TopUtil::convertBasic($this->status);
        }

        return $requestParam;
    }

    public function toFileParamMap() : array{
        $fileParam = array();
        return $fileParam;
    }

}

