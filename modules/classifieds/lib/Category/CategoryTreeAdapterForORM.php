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


namespace modules\classifieds\lib\Category;

class CategoryTreeAdapterForORM {
	
	var $tree;

	function __construct($category_tree)
	{
		$this->tree = $category_tree;
	}
	
	function getDepth() {return '0';}
	
	function getTreeStructure() {	

// 		$tc = new TimeCalculator("_getGroupedByParentNodes");
		$nodes_info = $this->_getGroupedByParentNodes();
// 		$tc->getElapsedTime();
		return $this->_renameFields($nodes_info);
	}
	
	function _getGroupedByParentNodes() {
		$storage = array();
		$categoryManager = new CategoryManager();
		$root_node = $this->tree->getNode($categoryManager->getRootId());
		$this->_insertChildren($root_node, $storage);
		return $storage;
	}
	
	function _insertChildren(&$parent, &$storage) {

		$node = $parent; 
		$children = $node->getChildren();
		foreach ($children as $child)
		{
			$childInfo = $child->toArray();
			$storage[$parent->getId()][$childInfo['sid']] = $childInfo;
			$this->_insertChildren($child, $storage);
		}
	}
	
	function _renameFields($tree) {		
		foreach($tree as $parent_id => $nodes_by_parent)
			foreach($nodes_by_parent as $node_id => $node) {
				$tree[$parent_id][$node_id]['parent_sid'] = $node['parent'];
				unset($tree[$parent_id][$node_id]['parent']);
				unset($tree[$parent_id][$node_id]['categories']);	
			}
		
		return $tree;		
	}
	
}
?>
