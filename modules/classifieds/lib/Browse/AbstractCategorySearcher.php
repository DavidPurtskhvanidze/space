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


namespace modules\classifieds\lib\Browse;

class AbstractCategorySearcher
{
	var $field;
	var $category_sid = 0;
	var $sql = "SELECT ?s AS `caption`, count(*) as `count` FROM `classifieds_listings` WHERE `sid` IN (?l) GROUP BY `caption`";

	function __construct($field)
	{
		$this->field = $field;
	}

	function getFieldSID($field){
		$property = \App()->ListingManager->getPropertyByPropertyName($field, $this->getCategorySid());
		return $property->getSID();
	}

	function getItems($request_data){
		$items = $this->_get_Captions_with_Counts_Grouped_by_Captions($request_data);
		$decoratedItems = $this->_decorateItems($items, $request_data);
		return $decoratedItems;
	}
	
	function _get_Captions_with_Counts_Grouped_by_Captions($request_data)
	{
		$search = $this->getSearch($request_data);
		return $search->getNumberOfObjectsFoundGroupedBy($this->field['field']);
	}
	
	private function getSearch($request_data)
	{
				$search = new \lib\ORM\SearchEngine\Search();
		$search->setRequest($request_data);
		$search->setDB(\App()->DB);
		$search->setModelObject($this->getListing());
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
		return $search;
	}
	
	function setListing($listing)
	{
		$this->listing = $listing;
	}
	
	function &getListing()
	{
		return $this->listing;
	}
	
	function setCategorySid($category_sid)
	{
		$this->category_sid = $category_sid;
	}
	
	function getCategorySid($request_data = null)
	{
		return $this->category_sid; 
	}
}
