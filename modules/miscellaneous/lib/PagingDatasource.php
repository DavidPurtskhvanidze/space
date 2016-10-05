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

class PagingDatasource
{
	var $requestData;
	var $sessionData;
	var $defaultData;
	
	function setRequestData(&$requestData)
	{
		$this->requestData = $requestData;
	}
	
	function setSessionData(&$sessionData)
	{
		$this->sessionData = $sessionData;
	}
	
	function setDefaultData(&$defaultData)
	{
		$this->defaultData = $defaultData;
	}
	
	function getItemsPerPage()
	{	
		return $this->get('items_per_page');
	}
	
	function getPage()
	{
		return $this->get('page');
	}
	
	function get($name)
	{
		$value = $this->requestData->get($name);
		if (is_null($value))
		{
			$value = $this->sessionData->get($name);
		}
		if (is_null($value))
		{
			$value = $this->defaultData->get($name);
		}
		$this->sessionData->set($name, $value);
		return $value;
	}
}



?>
