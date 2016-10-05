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

class TreeField extends \lib\ORM\Types\Tree\Tree
{
	private $id;
	public function getId(){ return $this->id; }
	public function setId($id) { $this->id = $id; }

	public function addItem($item)
	{
		$this->nodes[$item->getId()] = $item;
		$parentId = $item->getParentID();
		if (!isset($this->nodes[$parentId])) throw new \Exception('Cannot add item to parent "' . $parentId . '" because it does not exist');
		/** @var $parent TreeFieldItem */
		$parent = $this->nodes[$parentId];
		$parent->addChild($item);
		$item->setParent($parent);
		$item['level'] = $parent['level'];
		$parent->updateOrder();
	}

}
