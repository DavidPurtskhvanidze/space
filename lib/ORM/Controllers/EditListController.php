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


namespace lib\ORM\Controllers;


class EditListController extends ListController
{
	private $input_data;

	function setInputData($input_data)
	{
		$this->input_data = $input_data;
		parent::setInputData($this->input_data);
	}

	function saveItem($order = 'end', $afterItemSid = null)
	{
        $list_item = new \lib\ORM\Types\ListItem\ListItem();
		$list_item->setFieldSID($this->field_sid);
		$list_item->setValue($this->input_data['list_item_value']);
		return $this->ListItemManager->saveListItem($list_item, $order, $afterItemSid);
	}

	function deleteItem() {
    	$item_sid = isset($this->input_data['item_sid']) ? $this->input_data['item_sid'] : null;
		return $this->ListItemManager->deleteListItemBySID($item_sid);
	}

	function deleteItemsBySID($valuesSIDs) {
        return $this->ListItemManager->deleteListItemsBySID($valuesSIDs);
    }

	function recalculateItemsOrderByFieldSID($fieldSID) {
        return $this->ListItemManager->recalculateItemsOrderByFieldSID($fieldSID);
    }

	function moveUpItem() {
		$item_sid = isset($this->input_data['item_sid']) ? $this->input_data['item_sid'] : null;
		return $this->ListItemManager->moveUpItem($item_sid);
	}
	
	function moveDownItem() {
    	$item_sid = isset($this->input_data['item_sid']) ? $this->input_data['item_sid'] : null;
		return $this->ListItemManager->moveDownItem($item_sid);
	}

    function sortItemsAscending() {
        $field_sid = isset($this->input_data['field_sid']) ? $this->input_data['field_sid'] : null;
        $list_items = $this->ListItemManager->getHashedListItemsByFieldSID($field_sid);
        asort($list_items);
        return $this->ListItemManager->reorderItems(array_keys($list_items));
    }

    function sortItemsDescending() {
        $field_sid = isset($this->input_data['field_sid']) ? $this->input_data['field_sid'] : null;
        $list_items = $this->ListItemManager->getHashedListItemsByFieldSID($field_sid);
        arsort($list_items);
        return $this->ListItemManager->reorderItems(array_keys($list_items));
    }

	function isValidValueSubmitted() {
		if(!(isset($this->input_data['list_item_value']) && $this->input_data['list_item_value'] != ''))
		{
			\App()->ErrorMessages->addMessage('LIST_VALUE_IS_EMPTY');
			return false;
		}
		$field_info = \App()->ListingFieldManager->getInfoBySid($this->input_data['field_sid']);
		if($field_info['type'] == "multilist" && count($this->ListItemManager->getHashedMultiListItemsByFieldSID($field_info['sid'])) >= 64)
		{
			\App()->ErrorMessages->addMessage('MULTILIST_SIZE_EXCEEDED', array('limit' => 64));
			return false;
		}
		return true;
	}

	function getAction() {
		return isset($this->input_data['action']) ? $this->input_data['action'] : null;
	}
	public function getListItem()
	{
		return $this->ListItemManager->getListItemBySID($this->input_data['item_sid']);
	}

	public function isDataValid()
	{
		return (!is_null($this->field_sid) && !is_null($this->field) || ($this->getAction() == 'sort'));
	}
}
?>
