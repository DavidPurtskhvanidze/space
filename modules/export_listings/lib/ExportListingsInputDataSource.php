<?php
/**
 *
 *    Module: export_listings v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: export_listings-7.5.0-1
 *    Tag: tags/7.5.0-1@19779, 2016-06-17 13:19:16
 *
 *    This file is part of the 'export_listings' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\export_listings\lib;

class ExportListingsInputDataSource implements \lib\DataTransceiver\IInputDatasource
{
	private $collection;

	public function setCollection($collection)
	{
		$this->collection = $collection;
	}
	
	function getNext()
	{
		$res = $this->collection->current();
		$this->collection->next();
		return $res;
	}
	
	function isEmpty()
	{
		return !$this->collection->valid();
	}
}
