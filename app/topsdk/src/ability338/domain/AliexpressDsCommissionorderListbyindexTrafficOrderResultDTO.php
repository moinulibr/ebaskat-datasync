<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsCommissionorderListbyindexTrafficOrderResultDTO {

    /**
        max query index start value: if not passed, You can only check the first page
     **/
    private $max_query_index_id;

    /**
        current record count
     **/
    private $current_record_count;

    /**
        orders object list
     **/
    private $orders;

    /**
        min query index start value: if not passed, You can only check the first page
     **/
    private $min_query_index_id;

    /**
        current page number
     **/
    private $current_page_no;


    public function getMaxQueryIndexId() : string{
        return $this->max_query_index_id;
    }

    public function setMaxQueryIndexId(string $maxQueryIndexId){
        $this->max_query_index_id = $maxQueryIndexId;
    }

    public function getCurrentRecordCount() : int{
        return $this->current_record_count;
    }

    public function setCurrentRecordCount(int $currentRecordCount){
        $this->current_record_count = $currentRecordCount;
    }

    public function getOrders() : array{
        return $this->orders;
    }

    public function setOrders(array $orders){
        $this->orders = $orders;
    }

    public function getMinQueryIndexId() : string{
        return $this->min_query_index_id;
    }

    public function setMinQueryIndexId(string $minQueryIndexId){
        $this->min_query_index_id = $minQueryIndexId;
    }

    public function getCurrentPageNo() : int{
        return $this->current_page_no;
    }

    public function setCurrentPageNo(int $currentPageNo){
        $this->current_page_no = $currentPageNo;
    }


}

