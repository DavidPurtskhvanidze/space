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

class TreeData
{
    /**
     * @var TreeItem[]
     */
    var $tree = array();

    /**
     * @param TreeItem $item
     */
    function addItem($item)
	{
		$this->tree[$item->getID()] = $item;
	}
	
	function setRelations()
	{
		foreach ($this->tree as $treeItem)
		{
			if (isset($this->tree[$treeItem->getParentID()]))
			{
				$parentItem = $this->tree[$treeItem->getParentID()];
				$treeItem->setParent($parentItem);
				$parentItem->addChild($treeItem);
			}
		}
	}
	
	function addScalarExtraParameters($data, $key_name, $value_name, $extra_name)
	{
		foreach ($data as $extra_data)
		{
			$treeItem = $this->tree[$extra_data[$key_name]];
			$treeItem->addScalarExtraParameter($extra_name, $extra_data[$value_name]);
		}
	}
	
	function addArrayExtraParameters($data, $key_name, $value_name, $extra_name)
	{
		foreach ($data as $extra_data)
		{
			$treeItem = $this->tree[$extra_data[$key_name]];
			$treeItem->addArrayExtraParameter($extra_name, $extra_data[$value_name]);
		}
	}
	
	function getItem($id)
	{
		$item = null;
		if (isset($this->tree[$id]))
		{
			$item = $this->tree[$id];
		}
		return $item;
	}
	
	function getItems()
	{
		return $this->tree;
	}

    public function unsetExtraParameter($extraParameterKey)
    {
        foreach ($this->tree as $treeItem)
        {
            $treeItem->unsetExtraParameter($extraParameterKey);
        }
    }
}
