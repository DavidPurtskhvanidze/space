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


namespace modules\classifieds\lib\Browse;

class CategorySearcher_List extends AbstractCategorySearcher
{
	private $ListingFieldListItemManager;

	public function setListingFieldListItemManager($ListingFieldListItemManager)
	{
		$this->ListingFieldListItemManager = $ListingFieldListItemManager;
	}
	
	function _decorateItems($items)
	{
		$counts = $this->_getCountsByItems($items);
		$values = $this->ListingFieldListItemManager->getHashedListItemsByFieldSID($this->field['sid']);
		$listData = Array();
		foreach ($values as $id => $value)
		{
			$count = isset($counts[$id]) ? $counts[$id] : 0;
			$listData[] = array('caption' => $value, 'count' => $count);
		}
		return $listData;
	}

	function _getCountsByItems($items){
		$res = Array();
		foreach($items as $item)
			$res[$item['caption']] = $item['count'];
		return $res;
	}

}
