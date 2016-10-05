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


namespace modules\classifieds\lib\ListingField;

class ListingFieldMultiListItemManager extends ListingFieldListItemManager
{
	const MULTILIST_ITEMS_LIMIT = 64;

	public function getHashedMultiListItemsByFieldSIDRankAsKeys($listing_field_sid)
	{
		$items = \App()->DB->query("SELECT * FROM `" . $this->table_prefix . "_field_list` WHERE `field_sid` = ?n ORDER BY `order`", $listing_field_sid);
		$list_items = array();
		foreach ($items as $item)
		{
			$list_items[$item['rank']] = $item['value'];
		}
		return $list_items;
	}

	public function getValueAsArrayFromText($fieldSid, $text, $delimiter, $addNonExistentValues = false)
	{
		if (empty($text)) return array();
		$items = explode($delimiter, $text);
		$items = array_map('trim', $items);

		$listValuesInDB = $this->getHashedMultiListItemsByFieldSIDRankAsKeys($fieldSid);
		if ($addNonExistentValues)
		{
			$this->addListItems($fieldSid, array_udiff($items, $listValuesInDB, 'strcasecmp'));
			$listValuesInDB = $this->getHashedMultiListItemsByFieldSIDRankAsKeys($fieldSid);
		}
		return array_uintersect($listValuesInDB, $items, 'strcasecmp');
	}

	public function addNewListItem($listFieldSid, $value)
	{
		$controller = new \lib\ORM\Controllers\EditListController();
		$controller->setFieldManager(\App()->ListingFieldManager);
		$controller->setListItemManager($this);
		$controller->setInputData(array(
			'field_sid' => $listFieldSid,
			'list_item_value' => $value,
		));

		return $controller->saveItem();
	}

	private function addListItems($fieldSid, $values)
	{
		$listValuesCountInDB = count($this->getHashedMultiListItemsByFieldSIDRankAsKeys($fieldSid));
		$newValuesCount = count($values);

		if ($listValuesCountInDB + $newValuesCount > self::MULTILIST_ITEMS_LIMIT)
		{
			// need to log that not all values will be added
			$values = array_slice($values, 0, self::MULTILIST_ITEMS_LIMIT - $listValuesCountInDB);
		}

		foreach ($values as $newValue)
		{
			$this->addNewListItem($fieldSid, $newValue);
		}
	}
}
