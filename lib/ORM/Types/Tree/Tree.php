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

class Tree implements \ArrayAccess, \Iterator
{
	/** @var Node */
	protected $root;
	protected $nodes;
	private $maxLevel = null;

	function init($tree_info, $node_class = '\lib\ORM\Types\Tree\Node')
	{
		$creator = new TreeStructureCreator($tree_info, $node_class);
		$this->root = $creator->getStructure();
		$this->nodes = $creator->getNodeHash();
		foreach($this->nodes as $node) $node->setTree($this);
	}

	function getMaxLevel()
	{
		if (is_null($this->maxLevel)) foreach($this->nodes as $node) if ($node['level'] > $this->maxLevel) $this->maxLevel = $node['level'];
		return $this->maxLevel;
	}

	function getChildren($sid)
	{
		$node = $this->getNode($sid);
		$children_info = array();
		if($node) foreach($node->getChildren() as $child) $children_info[] = $child->toArray();
		return $children_info;
	}

	/** @return Node */
	function getNode($sid) { return isset($this->nodes[$sid]) ? $this->nodes[$sid] : null; }

	function getAncestorsInfo($sid, $storage = array())
	{
		$node = $this->getNode($sid);
		$storage[$sid] = $node->toArray();
		if($node->hasParent())
			$storage = $this->getAncestorsInfo($node['parent'], $storage);
		return $storage;
	} 

	public function getBranch($sid)
	{
		$result = array();
		if (isset($this->nodes[$sid])) $this->nodes[$sid]->getBranch($result);
		return $result;
	}

	public function offsetExists($offset) { return isset($this->nodes[$offset]);}
	public function offsetGet($offset) { return $this->getNode($offset);}
	public function offsetSet($offset, $value){throw new \Exception("Tree's implementation of array access in immutable");}
	public function offsetUnset($offset) { throw new \Exception("Tree's implementation of array access in immutable");}

	public function current(){ return current($this->nodes);}
	public function key(){return key($this->nodes);}
	public function next(){next($this->nodes);}
	public function rewind(){reset($this->nodes);}
	public function valid(){ return current($this->nodes) === FALSE ? FALSE : TRUE;}
}
