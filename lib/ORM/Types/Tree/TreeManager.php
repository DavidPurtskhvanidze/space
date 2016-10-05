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

class TreeManager
{
	protected $table_prefix = null;

	public function init(){}

	private $fieldTrees = array();
	private $rawData = array();

	private function getRawDataForField($sid)
	{
		if (!isset($this->rawData[$sid])) $this->rawData[$sid] = \App()->DB->query("SELECT *, `sid` AS id FROM `{$this->table_prefix}_field_tree` WHERE `field_sid` = ?n ORDER BY `order`", $sid);
		return $this->rawData[$sid];
	}

	/** @return TreeField */
	private function getFieldTreeByFieldSid($sid)
	{
		if (!isset($this->fieldTrees[$sid]))
		{
			$tree_values = $this->getRawDataForField($sid);
			$tree_values[]= array('sid'=> 0, 'caption'=>'root', 'parent_sid' => null, 'level'=>0);
			$treeField = new TreeField();
			$treeField->setId($sid);
			$treeField->init($tree_values,'\lib\ORM\Types\Tree\TreeFieldItem');
			$this->fieldTrees[$sid] = $treeField;
		}
		return $this->fieldTrees[$sid];
	}

	function getTreeValuesBySID($field_sid)
	{
        $result = array();
		foreach ($this->getRawDataForField($field_sid) as $tree_value) $result[$tree_value['parent_sid']][$tree_value['sid']] = $tree_value;
		return $result;
	}

	function getSIDByCaption($field_sid, $parent_sids, $caption)
	{
		$treeField = $this->getFieldTreeByFieldSid($field_sid);
		$children = $treeField[end($parent_sids)]->getChildren();
		foreach($children as $item) if (strcasecmp($item['caption'], $caption) === 0) return $item['sid'];
		return -1;
	}

	/**
	 * @param  $field_sid
	 * @param  $parent_sid
	 * @return array
	 * This function return id=>caption pairs for children only, not all descendants
	 */
	function getTreeValuesByParentSID($field_sid, $parent_sid)
	{
		$result = array();
		foreach ($this->getRawDataForField($field_sid) as $value) if ($value['parent_sid'] == $parent_sid) $result[$value['sid']] = $value['caption'];
		return $result;
	}

	/**
	 * @param  $field_sid
	 * @param  $parent_sid
	 * @return array
	 * This function returns ids (sid) for all descendants including the $parent_sid
	 */
	function getBranchIdsByParentSID($field_sid, $parent_sid)
	{
		$result = array();
		foreach ($this->getFieldTreeByFieldSid($field_sid)->getBranch($parent_sid) as $node) $result[] = $node->getId();
		return $result;
	}


	function addTreeItemToEndByParentSID($field_sid, $parent_sid, $tree_item_value)
	{
		/** @var $field TreeField */
		$field = $this->getFieldTreeByFieldSid($field_sid);
		$max_order = 0;
		foreach ($field[$parent_sid]->getChildren() as $item) if ($max_order < $item['order']) $max_order = $item['order'];
		$item = new TreeFieldItem(array('parent_sid'=> $parent_sid, 'caption' => $tree_item_value, 'order'=> $max_order + 1, 'level' => $field[$parent_sid]['level']+1));
		$item->setTree($field);
		$this->insertItem($field_sid, $item);
		$field->addItem($item);
		$this->resetDataForField($field_sid);
		return $item->getId();
	}

	private function insertItem($fields_sid, TreeFieldItem $item)
	{
		$id = \App()->DB->query("INSERT INTO `{$this->table_prefix}_field_tree`( `field_sid`, `parent_sid`, `level`, `caption`, `order`) VALUES (?n, ?n, ?n, ?s, ?n)", $fields_sid, $item['parent_sid'], $item['level'], $item['caption'], $item['order']);
		$item['sid'] = $id;
		return $id;
	}

	private function updateItem($item)
	{
		return \App()->DB->query("UPDATE `{$this->table_prefix}_field_tree` SET `field_sid` = ?n, `parent_sid` = ?n, `level` = ?n, `caption` = ?s, `order` = ?n WHERE `sid` = ?n", $item->getTree()->getId(), $item['parent_sid'], $item['level'], $item['caption'], $item['order'], $item['sid']);
	}

	function addTreeItemToBeginByParentSID($field_sid, $parent_sid, $tree_item_value)
	{
		$field = $this->getFieldTreeByFieldSid($field_sid);
		foreach ($field[$parent_sid]->getChildren() as $item) $item['order'] = $item['order'] + 1;
		$item = new TreeFieldItem(array('parent_sid'=> $parent_sid, 'caption' => $tree_item_value, 'order'=> 1, 'level' => $field[$parent_sid]['level']+1));
		\App()->DB->query("UPDATE `{$this->table_prefix}_field_tree` SET `order` = `order` + 1 WHERE `parent_sid` = ?n", $parent_sid);
		$this->insertItem($field_sid, $item);
		$field->addItem($item);
		$this->resetDataForField($field_sid);
	}

	function addTreeItemAfterByParentSID($field_sid, $parent_sid, $caption, $afterSid)
	{
		$field = $this->getFieldTreeByFieldSid($field_sid);
		$new_order = null;
		foreach ($field[$parent_sid]->getChildren() as $item)
		{
			if ($item['sid'] == $afterSid){ $new_order = $item['order'] + 1;}
		}
		$item = new TreeFieldItem(array('parent_sid'=> $parent_sid, 'caption' => $caption, 'order'=> $new_order, 'level' => $field[$parent_sid]['level']+1));
		\App()->DB->query("UPDATE `{$this->table_prefix}_field_tree` SET `order` = `order` + 1 WHERE `field_sid` = ?n AND `parent_sid` = ?n AND `order` >= ?n", $field_sid, $parent_sid, $new_order);
		$this->insertItem($field_sid,$item);
		$field->addItem($item);
		$this->resetDataForField($field_sid);
	}

	function deleteTreeItemBySID($item_sid)
	{
		$itemInfo = $this->getTreeItemInfoBySID($item_sid);
		$field = $this->getFieldTreeByFieldSid($itemInfo['field_sid']);
		$sids = array();
		foreach($field->getBranch($item_sid) as $item) $sids[] = $item['sid'];
		\App()->DB->query("DELETE FROM `{$this->table_prefix}_field_tree` WHERE `sid` in (?l)", $sids);
		\App()->DB->query("UPDATE `{$this->table_prefix}_field_tree` SET `order` = `order` - 1 WHERE `parent_sid` = ?n AND `order` > ?n", $itemInfo['parent_sid'], $itemInfo['order']);
		$this->resetDataForField($itemInfo['field_sid']);
	}

	function deleteTreeItemsBySIDs($fieldSID, array $itemSIDs, $nodeSID)
	{
		$field = $this->getFieldTreeByFieldSid($fieldSID);
		$sids = array();
        foreach ($itemSIDs as $itemSID)
            foreach($field->getBranch($itemSID) as $item) $sids[] = $item['sid'];
		\App()->DB->query("DELETE FROM `{$this->table_prefix}_field_tree` WHERE `sid` in (?l)", $sids);
        $this->reorderTreeItemsByNodeSID($nodeSID);
	}


	function moveUpTreeItem($item_sid)
	{
		$item_info = \App()->DB->query("SELECT * FROM `{$this->table_prefix}_field_tree` WHERE `sid` = ?n", $item_sid);
		if (empty($item_info)) return false;
		$item_info = array_pop($item_info);
		$field = $this->getFieldTreeByFieldSid($item_info['field_sid']);
		/** @var $item TreeFieldItem */ $item = $field[$item_info['sid']];
		$item->getParent()->rebuildOrder();
		$previous = null;
		foreach($item->getParent()->getChildren() as $sibling) if ($sibling['order'] == $item['order'] - 1 ) $previous = $sibling;
		if ($previous)
		{
			$previous['order'] = $previous['order'] + 1;
			$item['order'] = $item['order'] - 1;
		}
		foreach($item->getParent()->getChildren() as $sibling) $this->updateItem($sibling);
		$this->resetDataForField($item_info['field_sid']);
		return true;
	}

	function moveDownTreeItem($item_sid)
	{
		$item_info = \App()->DB->query("SELECT * FROM `{$this->table_prefix}_field_tree` WHERE `sid` = ?n", $item_sid);
		if (empty($item_info)) return false;
		$item_info = array_pop($item_info);
		$field = $this->getFieldTreeByFieldSid($item_info['field_sid']);
		/** @var $item TreeFieldItem */ $item = $field[$item_info['sid']];
		$item->getParent()->rebuildOrder();
		$next = null;
		foreach($item->getParent()->getChildren() as $sibling) if ($sibling['order'] == $item['order'] + 1 ) $next = $sibling;
		if (is_null($next)) return false;
		$next['order'] = $next['order'] - 1;
		$item['order'] = $item['order'] + 1;
		foreach($item->getParent()->getChildren() as $sibling) $this->updateItem($sibling);
		$this->resetDataForField($item_info['field_sid']);
		return true;
	}

	function reorderTreeItems($field_sid, $sidList, $parent_sid)
	{
		$field = $this->getFieldTreeByFieldSid($field_sid);
		foreach ($sidList as $index => $sid)
		{
			$field[$sid]['order']= $index + 1;
			$this->updateItem($field[$sid]);
		}
		$this->resetDataForField($field_sid);
	}

    function reorderTreeItemsByNodeSID($nodeSID)
    {
        $orderedItems = \App()->DB->query("SELECT *, `sid` AS id FROM `{$this->table_prefix}_field_tree` WHERE `parent_sid` = ?n ORDER BY `order`", $nodeSID);
        $order = 0;
        foreach ($orderedItems as $item)
            \App()->DB->query("UPDATE `{$this->table_prefix}_field_tree` SET `order` = ?n WHERE `sid` = ?n", ++$order, $item['sid']);
    }

	function getTreeItemInfoBySID($item_sid)
	{
		$item_info = \App()->DB->query("SELECT *, `sid` AS id FROM `{$this->table_prefix}_field_tree` WHERE `sid` = ?n", $item_sid);
		$item_info = empty($item_info) ? null : array_pop($item_info);
		return $item_info;
	}

	function updateTreeItemBySID($item_sid, $tree_item_value)
	{
		return \App()->DB->query("UPDATE `{$this->table_prefix}_field_tree` SET `caption` = ?s WHERE `sid` = ?n", $tree_item_value, $item_sid);
	}

	function getTreeNodePath($node_sid)
	{
		$node_info = $this->getTreeItemInfoBySID($node_sid);
		if (empty($node_info)) return null;
		$field = $this->getFieldTreeByFieldSid($node_info['field_sid']);
		return $field[$node_sid]->getIdsOfParents();
	}

	function getParentSID($field_sid, $item_sid)
	{
		return $this->getFieldTreeByFieldSid($field_sid)->getNode($item_sid)->getParentID();
	}

	function getChildrenSIDBySID($fieldSid, $itemSid)
	{
		$result = array();
		foreach ($this->getFieldTreeByFieldSid($fieldSid)->getBranch($itemSid) as $node) $result[] = $node->getId();
		return $result;
	}

	function getTreeDepthBySID($field_sid)
	{
		return $this->getFieldTreeByFieldSid($field_sid)->getMaxLevel();
	}

	function importTreeItem($field_sid, $imported_row)
	{
		if (!is_array($imported_row)) return false;

		$field = $this->getFieldTreeByFieldSid($field_sid);
		$parent = $field->getNode(0);
		$inserted = false;
		foreach ($imported_row as $item_caption)
		{
			if (empty($item_caption)) break;
			$found = null;
			foreach($parent->getChildren() as $item)
			{
				if ( $item['caption'] == $item_caption )
				{
					$found = $item;
					break;
				}
			}
			if ($found)
			{
				$parent = $found;
				continue;
			}
			$id = $this->addTreeItemToEndByParentSID($field_sid, $parent['sid'], $item_caption);
			if ($id > 0)
			{
				$inserted = true;
				$field = $this->getFieldTreeByFieldSid($field_sid);
				$parent = $field[$id];
			}

		}
		return $inserted;
	}

	function moveItemToBeginBySID($field_sid,$item_sid)
	{
		$field = $this->getFieldTreeByFieldSid($field_sid);
		/** @var $item TreeFieldItem */ $item = $field[$item_sid];
		if (is_null($item)) return false;
		$item['order'] = 0;
		$item->getParent()->updateOrder();
		$item->getParent()->rebuildOrder();
		foreach($item->getParent()->getChildren() as $sibling) $this->updateItem($sibling);
		return true;
	}

	function moveItemToEndBySID($field_sid,$item_sid)
	{
		$field = $this->getFieldTreeByFieldSid($field_sid);
		/** @var $item TreeFieldItem */ $item = $field[$item_sid];
		if (is_null($item)) return false;
		$children = $item->getParent()->getChildren();
		$item['order'] = count($children) + 1;
		$item->getParent()->updateOrder();
		$item->getParent()->rebuildOrder();
		foreach($children as $sibling) $this->updateItem($sibling);
		return true;
	}

	function moveItemAfterBySID($field_sid, $item_sid, $after_tree_item_sid)
	{
		$field = $this->getFieldTreeByFieldSid($field_sid);
		/** @var $itemToMove TreeFieldItem */ $itemToMove = $field[$item_sid];
		/** @var targetPositionItem TreeFieldItem */ $targetPositionItem = $field[$after_tree_item_sid];
		if (is_null($itemToMove)) return false;
		if (is_null($targetPositionItem)) return false;
		$children = $itemToMove->getParent()->getChildren();
		foreach($children as $sibling) if ($sibling['order'] > $targetPositionItem['order']) $sibling['order'] = $sibling['order'] + 1;
		$itemToMove['order'] = $targetPositionItem['order'] +1;
		$itemToMove->getParent()->updateOrder();
		$itemToMove->getParent()->rebuildOrder();
		foreach($children as $sibling) $this->updateItem($sibling);
		return true;
	}

	function deleteTreeEntriesForFieldWithSid($listing_field_sid)
	{
		\App()->DB->query("DELETE FROM `{$this->table_prefix}_field_tree` WHERE `field_sid` = ?n", $listing_field_sid);
		$this->resetDataForField($listing_field_sid);
	}

	private function resetDataForField($listing_field_sid)
	{
		unset($this->fieldTrees[$listing_field_sid]);
		unset($this->rawData[$listing_field_sid]);
		\App()->DB->resetCacheForquery("SELECT *, sid AS id FROM `{$this->table_prefix}_field_tree` WHERE field_sid = ?n ORDER BY `order`", $listing_field_sid);
	}

    function sortAllTreeItemsAscending()
    {
        $parentSIDs = \App()->DB->getColumnValues($this->table_prefix . '_field_tree', 'parent_sid');
        foreach ($parentSIDs as $parentSID) {
            $children = \App()->DB->query("SELECT * FROM `{$this->table_prefix}_field_tree` WHERE `parent_sid` = ?n ORDER BY `caption` ASC", $parentSID['parent_sid']);
            $order = 0;
            foreach ($children as $child) {
                \App()->DB->query("UPDATE `{$this->table_prefix}_field_tree` SET `order` = ?n WHERE `sid` = ?n", ++$order, $child['sid']);
            }
        }
    }
}
