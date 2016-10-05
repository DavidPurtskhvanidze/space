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

class TreeFieldItem extends \lib\ORM\Types\Tree\Node
{
	public function __construct($info = array())
	{
		$this->_Id_Field_Name = 'sid';
		$this->_Parent_Field_Name = 'parent_sid';
		parent::__construct($info);
	}

	public function updateOrder()
	{
		usort($this->children, array($this, 'cmpOrder'));
	}

	public function cmpOrder($a, $b)
	{
		return $a['order'] - $b['order'];
	}

	public function rebuildOrder()
	{
		$order = 1;
		foreach($this->children as $child) $child['order'] = $order++;
		$this->updateOrder();
	}

	public function getIdsOfParents()
	{
		$path = array();
		for ($node = $this; !is_null($node); $node = $node->getParent()) $path[$node->getId()] = array('sid' => $node->getId(), 'caption' => $node['caption']);
		return array_reverse($path);
	}
}
