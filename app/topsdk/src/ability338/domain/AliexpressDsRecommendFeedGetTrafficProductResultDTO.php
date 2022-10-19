<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsRecommendFeedGetTrafficProductResultDTO {

    /**
        total record count
     **/
    private $total_record_count;

    /**
        current record count
     **/
    private $current_record_count;

    /**
        is finished
     **/
    private $is_finished;

    /**
        total page number
     **/
    private $total_page_no;

    /**
        count page number
     **/
    private $current_page_no;

    /**
        products
     **/
    private $products;


    public function getTotalRecordCount() : int{
        return $this->total_record_count;
    }

    public function setTotalRecordCount(int $totalRecordCount){
        $this->total_record_count = $totalRecordCount;
    }

    public function getCurrentRecordCount() : int{
        return $this->current_record_count;
    }

    public function setCurrentRecordCount(int $currentRecordCount){
        $this->current_record_count = $currentRecordCount;
    }

    public function getIsFinished() : bool{
        return $this->is_finished;
    }

    public function setIsFinished(bool $isFinished){
        $this->is_finished = $isFinished;
    }

    public function getTotalPageNo() : int{
        return $this->total_page_no;
    }

    public function setTotalPageNo(int $totalPageNo){
        $this->total_page_no = $totalPageNo;
    }

    public function getCurrentPageNo() : int{
        return $this->current_page_no;
    }

    public function setCurrentPageNo(int $currentPageNo){
        $this->current_page_no = $currentPageNo;
    }

    public function getProducts() : array{
        return $this->products;
    }

    public function setProducts(array $products){
        $this->products = $products;
    }


}

