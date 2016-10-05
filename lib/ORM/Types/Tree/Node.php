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

class Node implements \ArrayAccess, \Iterator
{
	protected  $_Id_Field_Name = 'sid';
	protected  $_Parent_Field_Name = 'parent';

	/** @var array string */
	protected $info;
	function setNodeInfo($info){ $this->info = $info; }
	function getNodeInfo(){ return $this->info; }

	/** @var array Node */
	protected $children;

	/** @var Node */
	protected $parent;
	function setParent($parent){$this->parent = $parent;}
	function getParent(){return $this->parent;}

	/** @var Tree */
	protected $tree;
	public function getTree() { return $this->tree; }
	public function setTree($tree) { $this->tree = $tree; }

	function __construct($info = array())
	{
		$this->info = $info;
		$this->children = array();
		$this->null = null;
	}

	function getId(){ return $this->info[$this->_Id_Field_Name]; }
	function getParentID(){ return $this->info[$this->_Parent_Field_Name]; }

	function addChild(&$child){	$this->children[] = $child;	}

	function addChildren($children_info)
	{
		foreach($children_info as $child_info) if($this->_isChild($child_info)) $this->_addChild($child_info);
	}
	
	function getChildren(){ return $this->children; }
	function hasChildren() { return !empty($this->children); }
	function hasParent() { return !is_null($this->info[$this->_Parent_Field_Name]);	}
	
	public function getBranch(&$accumulator)
	{
		$accumulator[] = $this;
		foreach($this->children as $item) $item->getBranch($accumulator);
	}

	function getNode($sid)
	{
		if($this->info[$this->_Id_Field_Name] == $sid) return $this;
		return $this->_getNodeFromChildren($sid);
	}
	
	function _getNodeFromChildren($sid)
	{
		$children = $this->children;
		foreach(array_keys($children) as $index)
		{
			$child = $children[$index];
			$tmp = $child->getNode($sid);
			if($tmp) return $tmp;
		}
		return null;
	}
	
	function _isChild($child_info) { return $child_info[$this->_Parent_Field_Name] == $this->info[$this->_Id_Field_Name]; }
	
	function _addChild($child_info)
	{
		$current_class_name = get_class($this);
		$this->children[] = new $current_class_name($child_info);
	}

	public function offsetExists($offset){ return isset($this->info[$offset]);}
	public function offsetGet($offset){ return $this->info[$offset];}
	public function offsetSet($offset, $value){$this->info[$offset] = $value;}
	public function offsetUnset($offset){unset($this->info[$offset]);}

	public function current(){ return current($this->info);}
	public function key(){return key($this->info);}
	public function next(){next($this->info);}
	public function rewind(){reset($this->info);}
	public function valid(){ return current($this->info) === FALSE ? FALSE : TRUE;}
}
