<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\lib;

class Paging
{
	var $datasource;
	var $items;
	
	function getItemsForCurrentPage()
	{
		$page = $this->getCurrentPage();
		$itemsByPage = array_chunk($this->items, $this->getItemsPerPage(), true);
		$items = isset($itemsByPage[$page - 1]) ? $itemsByPage[$page - 1] : null;
		return $items;
	}
	
	function setItems(&$items)
	{
		$this->items = $items;
	}

	function setDatasource(&$datasource)
	{
		$this->datasource = $datasource;
	}

	function getCurrentPage()
	{
		return $this->datasource->getPage();
	}

	function getPagesNumber()
	{
		return ceil(count($this->items) / $this->datasource->getItemsPerPage());
	}

	function getItemsPerPage()
	{
		return $this->datasource->getItemsPerPage();
	}
}

?>
