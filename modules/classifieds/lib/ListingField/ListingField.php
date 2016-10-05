<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\lib\ListingField;

class ListingField extends \lib\ORM\Object
{
	var $category_sid;
	var $field_type;
	var $order;
	
	public function incorporateData($data)
	{
		$this->details->incorporateData($data);
		$this->field_type = isset($data['type']) ? $data['type'] : null;
		$this->order = isset($data['order']) ? $data['order'] : null;
	}

	function setCategorySID($category_sid)
	{
		$this->category_sid = $category_sid;
	}
	
	function getOrder()
	{
		return $this->order;
	}
	
	function getCategorySID()
	{
		return $this->category_sid;
	}
	
	function getFieldType()
	{
		return $this->field_type;
	}
	
	public function equals($that)
	{
		if ( $this->getFieldType() != $that->getFieldType() ) return false;
		foreach(array_keys($this->details->getProperties()) as $index) if ( $this->getPropertyValue($index) != $that->getPropertyValue($index) ) return false;
		return true;
	}
}
