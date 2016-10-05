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

class StructureExplorer
{
	var $filters = array();
	var $eventHandler;

	function addFilter($string)
	{
		
		$this->filters[] = $string;
	}

	function setEventHandler($callback)
	{
		$this->eventHandler = $callback;
	}
	
	function explore(&$data)
	{
		$this->_explore($data, null, $data);
	}
	
	function _explore(&$data, $key, &$parentData)
	{
		if($this->canRaise($data, $key, $parentData))
		{
			$this->raiseEvent($data, $key, $parentData);
		}
		if(is_array($data))
		{
			foreach(array_keys($data) as $key)
			{
				$this->_explore($data[$key], $key, $data);
			}
		}
	}
	
	function canRaise(&$value, $key, &$parentData)
	{
		foreach($this->filters as $filter)
		{
			if(!eval('return ' . $filter . ';'))
			{
				return false;
			}
		}
		return !is_null($this->eventHandler);
	}

	function raiseEvent(&$value, $key, &$parentData)
	{
		$value = call_user_func($this->eventHandler, $value, $key, $parentData);
	}

}

?>
