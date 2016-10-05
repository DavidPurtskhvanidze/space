<?php
/**
 *
 *    iLister v.7.5.0, (c) WorksForWeb 2005 - 2016
 *
 *    Package: iLister-7.5.0
 *    Tag: modular/projects/classifieds/tags/7.5.0@19890, 2016-06-17 13:38:22
 *
 *    This file is a part of iLister software
 *    and is protected by international copyright laws
 *
 */


namespace lib\ORM\Types\Tree;

class TreeStructureCreator {
	
	private $tree_info;
	private $node_class;
	private $_HashOfObjects = array();

	function __construct($tree_info, $node_class)
	{
		$this->tree_info = $tree_info;
		$this->node_class = $node_class;	
	}
	
	public function getStructure()
	{
		$root_node = null;
		foreach ($this->tree_info as $node_info)
		{
			/** @var $node Node */
			$node = new $this->node_class;
			$node->setNodeInfo($node_info);
			$id = $node->getId();
			$this->_HashOfObjects[$id] = $node;
			if (is_null($node->getParentID())) $root_node = $node;
		}
		foreach ($this->_HashOfObjects as $node_id => $whatever)
		{
			$node = $this->_HashOfObjects[$node_id];
			$parent_id = $node->getParentID();
			if (is_null($parent_id)) continue;
			/** @var $parent Node */
			$parent = $this->_HashOfObjects[$parent_id];
			if (is_null($parent)) continue; // Branch torn off!
			$parent->addChild($node);
			$node->setParent($parent);
		}
		$result = empty($root_node) ? $this->_getEmptyStructure() : $root_node;
		return $result;
	}

	public function getNodeHash()
	{
		return $this->_HashOfObjects;
	}

	function _getEmptyStructure()
	{
		/** @var $r Node */
		$r = new $this->node_class();
		return $r;
	}
	
}
