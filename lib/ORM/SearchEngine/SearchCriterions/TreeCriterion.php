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


namespace lib\ORM\SearchEngine\SearchCriterions;

class TreeCriterion extends SearchCriterion
{
	private function _getBranchSids()
	{
		$values = $this->value;
		$tree_item_sid = null;
		while(empty($tree_item_sid))
		{
			if (empty($values)) break;
			$tree_item_sid = array_pop($values);
		}

		if (empty($tree_item_sid) && $tree_item_sid != 0) return 1;
		$sids = array();
		if(!is_null($this->property))
		{
			$children_sids = $this->property->type->getBranch($tree_item_sid);
			$sids = $children_sids;
		}
		$sids[] = $tree_item_sid;
		return $sids;
	}

	function getSystemSQL()
	{
		if(!$this->isValid()) return null;
		$sids = $this->_getBranchSids();
		return "{$this->property->getFullColumnName()} IN (".join(", ", $sids).")";
	}

	function isValid()
	{
		$value = current($this->value);
		return (!empty($value) || is_numeric($value));
	}

	function getValue()
	{
		return $this->value;
	}
}
