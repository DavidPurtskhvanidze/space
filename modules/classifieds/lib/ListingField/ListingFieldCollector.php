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

class ListingFieldCollector
{
	var $collection = array();
	
	function getCollection()
	{
		return $this->collection;
	}
	
	function reset()
	{
		$this->collection = array();
	}
	
	function handle(&$data)
	{
		$this->collection = array_merge($this->collection, (array) $data->getExtraParameter('listing_field_id'));
	}
}

?>
