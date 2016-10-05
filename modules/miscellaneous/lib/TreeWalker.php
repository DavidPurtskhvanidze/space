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

class TreeWalker
{
	function walkDown($treeItem)
	{
		if ($this->canRaise($treeItem))
		{
			$this->raiseEvent($treeItem);
		}
		
		$children = $treeItem->getChildren();
		foreach (array_keys($children) as $key)
		{
			$child = $children[$key];
			$this->walkDown($child);
		}
	}
	
	function walkChildren($treeItem)
	{
		$children = $treeItem->getChildren();
		foreach (array_keys($children) as $key)
		{
			$child = $children[$key];
			if ($this->canRaise($child))
			{
				$this->raiseEvent($child);
			}
		}
	}
	
	function walkUp($treeItem)
	{
		if ($this->canRaise($treeItem))
		{
			$this->raiseEvent($treeItem);
		}
		
		$parent = $treeItem->getParent();
		if (!is_null($parent))
		{
			$this->walkUp($parent);
		}
	}
	
	function canRaise()
	{
		return !is_null($this->handler);
	}
	
	function raiseEvent($data)
	{
		$this->handler->handle($data);
	}
	
	function setHandler($handler)
	{
		$this->handler = $handler;
	}
}

?>
