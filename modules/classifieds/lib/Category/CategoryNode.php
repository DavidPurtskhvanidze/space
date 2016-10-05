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

class CategoryNode extends \lib\ORM\Types\Tree\Node
{
	function getListingQuantity($field = 'active_listing_number')
	{
		$result = $this->info[$field];
		if($this->hasChildren())
		{
			foreach($this->getChildren() as $child)
			{
				$result += $child->getListingQuantity($field);
			}
		}
		return $result;
	}

	function setListingQuantity() {
		$this->info['active_listing_number'] = $this->getListingQuantity();
		$this->info['listing_number'] = $this->getListingQuantity('listing_number');
		foreach($this->children as $child_id => $useless)
			$this->children[$child_id]->setListingQuantity();
	}

	function getChildrenSIDCollection($storage = array()) {
		$storage[] = $this->info['sid'];
		if($this->children)
			foreach($this->children as $child)
				$storage = $child->getChildrenSIDCollection($storage);
		return $storage;
	}

	function toArray($children_alias = 'categories')
	{
		$result = $this->info;
		$result['N_children'] = count($this->getChildren());
		$result['N_offsprings'] = 0;
		foreach($this->getChildren() as $child)
		{
			$child_array = $child->toArray($children_alias);
			$result['N_offsprings'] += $child_array['N_children'] + 1;
			$result[$children_alias][] = $child_array;
		}
	return $result;
	}

	function getChildById($id)
	{
		foreach($this->children as $child) if ( $child->info['id'] === $id ) return $child;
		return null;
	}

	function setPath($basePath)
	{
		$this->info['path'] = is_null($this->info['parent']) ? '/' : $basePath . $this->info['id'] . '/';
		foreach($this->children as $child_id => $useless)
		{
			$this->children[$child_id]->setPath($this->info['path']);
		}
	}

	function setLevel($level)
	{
		$this->info['level'] = $level;
		foreach($this->children as $child_id => $useless) $this->children[$child_id]->setLevel($level+1);
	}
} // enf of class
