<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsProductGetLogisticsInfoDTO {

    /**
        Attribute ID
     **/
    private $attr_name_id;

    /**
        Attribute name
     **/
    private $attr_name;

    /**
        Attribute ID
     **/
    private $attr_value_id;

    /**
        Attribute value
     **/
    private $attr_value;

    /**
        Interval attribute start value
     **/
    private $attr_value_start;

    /**
        End value of interval attribute
     **/
    private $attr_value_end;

    /**
        Attribute unit
     **/
    private $attr_value_unit;


    public function getAttrNameId() : int{
        return $this->attr_name_id;
    }

    public function setAttrNameId(int $attrNameId){
        $this->attr_name_id = $attrNameId;
    }

    public function getAttrName() : string{
        return $this->attr_name;
    }

    public function setAttrName(string $attrName){
        $this->attr_name = $attrName;
    }

    public function getAttrValueId() : int{
        return $this->attr_value_id;
    }

    public function setAttrValueId(int $attrValueId){
        $this->attr_value_id = $attrValueId;
    }

    public function getAttrValue() : string{
        return $this->attr_value;
    }

    public function setAttrValue(string $attrValue){
        $this->attr_value = $attrValue;
    }

    public function getAttrValueStart() : string{
        return $this->attr_value_start;
    }

    public function setAttrValueStart(string $attrValueStart){
        $this->attr_value_start = $attrValueStart;
    }

    public function getAttrValueEnd() : string{
        return $this->attr_value_end;
    }

    public function setAttrValueEnd(string $attrValueEnd){
        $this->attr_value_end = $attrValueEnd;
    }

    public function getAttrValueUnit() : string{
        return $this->attr_value_unit;
    }

    public function setAttrValueUnit(string $attrValueUnit){
        $this->attr_value_unit = $attrValueUnit;
    }


}

