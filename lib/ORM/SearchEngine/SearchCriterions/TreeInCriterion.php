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

class TreeInCriterion extends SearchCriterion
{
	private function _getBranchSids()
	{

        $values = $this->value;
        $tree_item_sids = array();
        foreach ($values as $groupedTreeItemSeed)
        {

            if (count($groupedTreeItemSeed) == 1)
            { //Е�?ли только один �?лемент, то ищут по родителю
                $parentSID = array_pop($groupedTreeItemSeed);
                if (!empty($parentSID)) $tree_item_sids[] = $parentSID;
            }
            else{//Ищут по чулдренам

                array_shift($groupedTreeItemSeed);
                $tree_item_sids = array_merge($tree_item_sids, $groupedTreeItemSeed);
            }
        }
		if (empty($tree_item_sids)) return 1;
		$sids = array();
		if(!is_null($this->property))
		{
            foreach ($tree_item_sids as $treeItemSID){
                $childrenSIDs = $this->property->type->getBranch($treeItemSID);
                $sids = array_merge($sids, $childrenSIDs);
            }

		}
		$sids = array_merge($sids, $tree_item_sids);

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
        if (!is_array($this->value))// Должен быть ма�?�?ив
            return false;
        $family = current($this->value);
        //Ма�?�?ив, который �?одержит ма�?�?ив, который �?одержит в �?ебе integer-ы
        return (isset($family[0]) and !empty($family[0]) and is_numeric($family[0]));
    }

    function getValue()
	{
		return $this->value;
	}
}
