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

class TreeBuilder
{
	function buildTree()
	{
		$objectMother = new \lib\ObjectMother();
		$this->tree = $objectMother->createTreeData();
		foreach (array_keys($this->data) as $key)
		{
			$item_data = $this->data[$key];
			$treeItem = $objectMother->createTreeItem($item_data['sid'], $item_data['parent']);
			$this->tree->addItem($treeItem);
		}
		$this->tree->setRelations();
	}
	
	function getTree()
	{
		return $this->tree;
	}
	
	function setData($data)
	{
		$this->data = $data;
	}
}

?>
