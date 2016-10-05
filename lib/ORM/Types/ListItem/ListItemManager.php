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


namespace lib\ORM\Types\ListItem;


class ListItemManager
{
	var $table_prefix = null;

	function saveListItem(&$list_item, $order = 'end', $afterItemSid = null)
	{
		if ($check_item = $this->getListItemByValue($list_item->getFieldSID(), $list_item->getValue()))
		{
			if ($check_item->getSID() != $list_item->getSID())
				return false;
		}
		$item_sid = $list_item->getSID();
		if ( is_null($item_sid) ) {
			$item_rank = $this->getNewListItemRank($list_item->getFieldSID());
			if ($order == 'begin')
			{
				$sid = \App()->DB->query("INSERT INTO `".$this->table_prefix."_field_list` SET `field_sid` = ?n, `value` = ?s, `order` = 1, `rank` = ?n", $list_item->getFieldSID(), $list_item->getValue(), $item_rank);
				if ($sid)
					\App()->DB->query("UPDATE `{$this->table_prefix}_field_list` SET `order` = `order` + 1 WHERE `field_sid` = ?n AND `sid` <> ?n", $list_item->getFieldSID(), $sid);
				return $sid;
			}
			elseif($order == 'end')
			{
				$max_order = \App()->DB->getSingleValue("SELECT MAX(`order`) FROM ".$this->table_prefix."_field_list WHERE field_sid = ?n", $list_item->getFieldSID());
				$max_order = empty($max_order) ? 0 : $max_order;
				return \App()->DB->query("INSERT INTO `".$this->table_prefix."_field_list` SET `field_sid` = ?n, `value` = ?s, `order` = ?n, `rank` = ?n", $list_item->getFieldSID(), $list_item->getValue(), ++$max_order, $item_rank);
			}
			elseif($order == 'after')
			{
				$newOrder = \App()->DB->getSingleValue("SELECT `order` FROM `{$this->table_prefix}_field_list` WHERE `sid` = ?n", $afterItemSid);
				$sid = \App()->DB->query("INSERT INTO `".$this->table_prefix."_field_list` SET `field_sid` = ?n, `value` = ?s, `order` = ?n, `rank` = ?n", $list_item->getFieldSID(), $list_item->getValue(), ++$newOrder, $item_rank);
				if ($sid)
					\App()->DB->query("UPDATE `{$this->table_prefix}_field_list` SET `order` = `order` + 1 WHERE `field_sid` = ?n AND `sid` <> ?n AND `order` >= ?n", $list_item->getFieldSID(), $sid, $newOrder);
				return $sid;
			}
		} else {
			return \App()->DB->query("UPDATE `".$this->table_prefix."_field_list` SET `value` = ?s WHERE `sid` = ?n", $list_item->getValue(), $item_sid);
		}
	}

	function getHashedListItemsByFieldSID($listing_field_sid) {
		$items = \App()->DB->query("SELECT * FROM `" . $this->table_prefix . "_field_list` WHERE `field_sid` = ?n ORDER BY `order`",  $listing_field_sid);
		$list_items = array();
		foreach ($items as $item) {
			$list_items[$item['sid']] = $item['value'];
		}
		return $list_items;
	}

	function getHashedMultiListItemsByFieldSID($listing_field_sid) {
		$items = \App()->DB->query("SELECT * FROM `" . $this->table_prefix . "_field_list` WHERE `field_sid` = ?n ORDER BY `order`",  $listing_field_sid);
		$list_items = array();
		foreach ($items as $item) {
			$list_items[$item['sid']] = array('value' => $item['value'], 'rank' => $item['rank']);
		}
		return $list_items;
	}

	function deleteListItemBySID($listItemSid)
    {
    	$list_item = $this->getListItemBySID($listItemSid);
    	$field_info = \App()->ListingFieldManager->getInfoBySid($list_item->getFieldSID());
    	if($field_info['type'] == "multilist")
    	{
    		\App()->ListingManager->clearListingFieldRank($field_info['id'], $list_item->getRank());
    	}
	    $itemInfo = \App()->DB->getSingleRow("SELECT * FROM ".$this->table_prefix."_field_list WHERE sid = ?n", $listItemSid);
	    \App()->DB->query("DELETE FROM `" . $this->table_prefix . "_field_list` WHERE `sid` = ?n", $listItemSid);
        return \App()->DB->query("UPDATE `" . $this->table_prefix . "_field_list` SET `order` = `order` - 1 WHERE `field_sid` = ?n AND `order` > ?n", $itemInfo['field_sid'], $itemInfo['order']);
    }

    function deleteListItemsBySID(array $listItemSids)
    {
    	$list_item = $this->getListItemBySID(end($listItemSids));
    	$field_info = \App()->ListingFieldManager->getInfoBySid($list_item->getFieldSID());
    	if($field_info['type'] == "multilist")
    	{
    		foreach ($listItemSids as $value) 
    		{
    			$list_item = $this->getListItemBySID($value);
    			\App()->ListingManager->clearListingFieldRank($field_info['id'], $list_item->getRank());
    		}
    	}
        return \App()->DB->query("DELETE FROM `" . $this->table_prefix . "_field_list` WHERE `sid` IN ( ?l )", $listItemSids);
    }

	function deleteItemsByFieldSID($field_sid) {
		return \App()->DB->query("DELETE FROM `" . $this->table_prefix."_field_list` WHERE `field_sid` = ?n", $field_sid);
	}
	
	function moveUpItem($item_sid) {
		
		$item_info = \App()->DB->query("SELECT * FROM `" . $this->table_prefix."_field_list` WHERE `sid` = ?n", $item_sid);
		
		if (empty($item_info))	return false;
		
		$item_info = array_pop($item_info);
		
		$current_order = $item_info['order'];	$field_sid = $item_info['field_sid'];
		
		$up_order = \App()->DB->getSingleValue("SELECT MAX(`order`) FROM " . $this->table_prefix."_field_list WHERE field_sid = ?n AND `order` < ?n", $field_sid, $current_order);
		
		if (empty($up_order))	return false;
		
		\App()->DB->query("UPDATE `" . $this->table_prefix."_field_list` SET `order` = ?n WHERE `field_sid` = ?n AND `order` = ?n", $current_order, $field_sid, $up_order);
		
		\App()->DB->query("UPDATE `" . $this->table_prefix."_field_list` SET `order` = ?n WHERE `sid` = ?n", $up_order, $item_sid);
		
		return true;
		
	}
	
	function moveDownItem($item_sid) {
		
		$item_info = \App()->DB->query("SELECT * FROM `" . $this->table_prefix."_field_list` WHERE `sid` = ?n", $item_sid);
		
		if (empty($item_info))	return false;
		
		$item_info = array_pop($item_info);
		
		$current_order = $item_info['order'];	$field_sid = $item_info['field_sid'];
		
		$less_order = \App()->DB->getSingleValue("SELECT MIN(`order`) FROM " . $this->table_prefix."_field_list WHERE field_sid = ?n AND `order` > ?n", $field_sid, $current_order);
		
		if (empty($less_order))	return false;
		
		\App()->DB->query("UPDATE `" . $this->table_prefix."_field_list` SET `order` = ?n WHERE `field_sid` = ?n AND `order` = ?n", $current_order, $field_sid, $less_order);
		
		\App()->DB->query("UPDATE `" . $this->table_prefix."_field_list` SET `order` = ?n WHERE `sid` = ?n", $less_order, $item_sid);
		
		return true;
		
	}

    function reorderItems($sids_list) {

        foreach ($sids_list as $order => $item_sid) {

            \App()->DB->query("UPDATE `" . $this->table_prefix."_field_list` SET `order` = ?n WHERE `sid` = ?n", $order + 1, $item_sid);

        }

    }

    function recalculateItemsOrderByFieldSID($fieldSID)
    {
        $orderedItems = \App()->DB->query("SELECT * FROM `".$this->table_prefix."_field_list` WHERE `field_sid` = ?n ORDER BY `order`", $fieldSID);
        $order = 0;
        foreach ($orderedItems as $item)
            \App()->DB->query("UPDATE `".$this->table_prefix."_field_list` SET `order` = ?n WHERE `sid` = ?n", ++$order, $item['sid']);
    }

	function getListItemBySID($list_item_sid) {

		$item_info = \App()->DB->query("SELECT * FROM `".$this->table_prefix."_field_list` WHERE `sid` = ?n", $list_item_sid);

		if (!empty($item_info))
		{
			$item_info = array_pop($item_info);
			
			$list_item = new ListItem();			
			$list_item->setValue($item_info['value']);
			$list_item->setFieldSID($item_info['field_sid']);
			$list_item->setSID($list_item_sid);
			$list_item->setRank($item_info['rank']);
			
			return $list_item;
		}
		else
			return null;
	}
	
	function getListItemByValue($field_sid, $value)
	{
		$item_info = \App()->DB->query("SELECT * FROM `{$this->table_prefix}_field_list` WHERE `field_sid`=?n AND `value`=?s", $field_sid, $value);
		
		if (!empty($item_info))
		{
			$item_info = array_pop($item_info);
			
			$list_item = new ListItem();			
			$list_item->setValue($item_info['value']);
			$list_item->setFieldSID($item_info['field_sid']);
			$list_item->setSID($item_info['sid']);
			$list_item->setRank($item_info['rank']);

			return $list_item;
		}
		else
			return null;
	}
	
	function doesListItemExistByValue($fieldSid, $value)
	{
		$res = \App()->DB->getSingleValue("SELECT COUNT(*) FROM `{$this->table_prefix}_field_list` WHERE `field_sid`=?n AND `value`=?s", $fieldSid, $value);
		return $res > 0;
	}
	
	function addListItem($fieldSid, $value)
	{
		$max_order = \App()->DB->getSingleValue("SELECT MAX(`order`) FROM ".$this->table_prefix."_field_list WHERE field_sid = ?n", $fieldSid);
		$max_order = empty($max_order) ? 0 : $max_order;
		$item_rank = $this->getNewListItemRank($fieldSid);
		return \App()->DB->query("INSERT INTO `".$this->table_prefix."_field_list` SET `field_sid` = ?n, `value` = ?s, `order` = ?n, `rank` = ?n", $fieldSid, $value, ++$max_order, $item_rank);
	}

	function getNewListItemRank($field_sid)
	{
		$field_items = \App()->DB->query("SELECT * FROM `".$this->table_prefix."_field_list` WHERE `field_sid` = ?n ORDER BY `rank`", $field_sid);
		foreach ($field_items as $key => $field_item) 
		{
			if($field_item['rank'] != $key)
				return $key;
		}
		return count($field_items);
	}
}

?>
