<?php
/**
 *
 *    Module: users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: users-7.5.0-1
 *    Tag: tags/7.5.0-1@19887, 2016-06-17 13:25:03
 *
 *    This file is part of the 'users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\users\apps\AdminPanel\scripts;

class EditTreeHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'users';
	protected $functionName = 'edit_tree';

	private $field_sid;
	private $field_info;
	private $node_sid;
	public $field_errors = array();
	public $errors = array();

	public function respond()
	{
		$this->field_sid = isset($_REQUEST['field_sid']) ? $_REQUEST['field_sid'] : null;
		$this->field_info = \App()->UserProfileFieldManager->getInfoBySID($this->field_sid);
		$this->field_info['levels_captions'] = explode(",", $this->field_info['levels_captions']);
		$this->node_sid = isset($_REQUEST['node_sid']) ? $_REQUEST['node_sid'] : 0;

		if (empty($this->field_info) && (empty($_REQUEST['action']) || $_REQUEST['action'] !== 'sort'))
		{
			$this->errors['INVALID_FIELD_SID'] = 1;
		}
		else
		{
			$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;
			if ($action == 'add') $this->addItem();
			elseif ($action == 'save') $this->saveItem();
			elseif ($action == 'delete') $this->deleteItem();
			elseif ($action == 'move_up') $this->moveItemUp();
			elseif ($action == 'move_down') $this->modeItemDown();
			elseif ($action == 'sort_ascending') $this->sortAscending();
			elseif ($action == 'sort_descending') $this->sortDescending();
			elseif ($action == 'delete_selected_items') $this->deleteSelectedItems();
            elseif ($action == 'sort_all_ascending') $this->sortAllAscending();
            elseif ($action == 'sort') $this->sortAll();

			if ( !is_null($action) && empty($this->field_errors) && empty($this->errors) ) $this->redirectToCurrentScreen();

			$tree_items = \App()->UserProfileFieldManager->getTreeValuesByParentSID($this->field_sid, $this->node_sid);
			$parent_sid = \App()->UserProfileFieldTreeManager->getParentSID($this->field_sid, $this->node_sid);
			$tree_parent_items = \App()->UserProfileFieldManager->getTreeValuesByParentSID($this->field_sid, $parent_sid);
		}

		$node_info = \App()->UserProfileFieldManager->getTreeItemInfoBySID($this->node_sid);
		$node_path = \App()->UserProfileFieldTreeManager->getTreeNodePath($this->node_sid);
		$node_path[0] = array('caption' => 'Root', 'sid' => 0);
		$node_info['node_path'] = $node_path;
		$user_group_info = \App()->UserGroupManager->getUserGroupInfoBySID($this->field_info['user_group_sid']);
		if (is_null($user_group_info))
			$user_group_info['sid'] = 0;

		$current_level = isset($node_info['level']) ? $node_info['level'] : 0;

		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign("field_sid", $this->field_sid);
		$template_processor->assign("node_sid", $this->node_sid);
		$template_processor->assign("field_info", $this->field_info);
		$template_processor->assign("tree_parent_items", $tree_parent_items);
		$template_processor->assign("tree_items", $tree_items);
		$template_processor->assign("node_info", $node_info);
		$template_processor->assign("current_level", $current_level);
		$template_processor->assign("type_info", $user_group_info);
		$template_processor->assign("errors", $this->errors );
		$template_processor->assign("field_errors", $this->field_errors);
		$template_processor->display("edit_tree.tpl");
	}

	private function addItem()
	{
		$tree_item_value = $_REQUEST['tree_item_value'];
		$order = $_REQUEST['order'];
		if ($tree_item_value == '')
		{
			$this->field_errors['Value'] = 'EMPTY_VALUE';
		}
		else
		{
			if ($order == 'begin') {
				\App()->UserProfileFieldManager->addTreeItemToBeginByParentSID($this->field_sid, $this->node_sid, $tree_item_value);
			}
			elseif ($order == 'end') \App()->UserProfileFieldManager->addTreeItemToEndByParentSID($this->field_sid, $this->node_sid, $tree_item_value);
			elseif ($order == 'after') \App()->UserProfileFieldManager->addTreeItemAfterByParentSID($this->field_sid, $this->node_sid, $tree_item_value, $_REQUEST['after_tree_item_sid']);
		}
	}

	private function saveItem()
	{
		$tree_item_value = $_REQUEST['tree_item_value'];
		if (empty($tree_item_value))
		{
			$this->field_errors['Value'] = 'EMPTY_VALUE';
		}
		else
		{
			\App()->UserProfileFieldManager->updateTreeItemBySID($this->node_sid, $tree_item_value);
			$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : null;
			/** @var $m UserProfileFieldTreeManager */
			$m = \App()->UserProfileFieldTreeManager;
			if ($order == 'begin')
			{
				$m->moveItemToBeginBySID($this->field_sid, $this->node_sid);
			}
			elseif ($order == 'end') $m->moveItemToEndBySID($this->field_sid, $this->node_sid);
			elseif ($order == 'after') $m->moveItemAfterBySID($this->field_sid, $this->node_sid, $_REQUEST['after_tree_item_sid']);
		}
	}

	private function deleteItem()
	{
		$item_sid = isset($_REQUEST['item_sid']) ? $_REQUEST['item_sid'] : null;
        \App()->UserProfileFieldManager->deleteTreeItemBySID($item_sid);
    }

	private function deleteSelectedItems()
	{
        $itemSIDs = \App()->Request['values'];
        $fieldSID = \App()->Request['field_sid'];
        $nodeSID = \App()->Request['node_sid'];
        if (!empty($itemSIDs) and !empty($fieldSID) and isset($nodeSID))
        {
            \App()->UserProfileFieldManager->deleteTreeItemsBySIDs($fieldSID, $itemSIDs, $nodeSID);
            \App()->SuccessMessages->addMessage('SELECTED_TREE_VALUES_DELETED');
        }
	}

	private function moveItemUp()
	{
		$item_sid = isset($_REQUEST['item_sid']) ? $_REQUEST['item_sid'] : null;
		\App()->UserProfileFieldManager->moveUpTreeItem($item_sid);
	}

	private function modeItemDown()
	{
		$item_sid = isset($_REQUEST['item_sid']) ? $_REQUEST['item_sid'] : null;
		\App()->UserProfileFieldManager->moveDownTreeItem($item_sid);
	}

	private function sortAscending()
	{
		\App()->UserProfileFieldManager->sortTreeItemsAscending($this->field_sid, $this->node_sid);
	}

	private function sortDescending()
	{
		\App()->UserProfileFieldManager->sortTreeItemsDescending($this->field_sid, $this->node_sid);
    }

    private function redirectToCurrentScreen()
    {
        $url = \App()->SystemSettings['SiteUrl'] . \App()->PageManager->getPageUri() . '?field_sid=' . $this->field_sid . '&node_sid=' . $this->node_sid;
        throw new \lib\Http\RedirectException($url);
    }

    private function sortAllAscending()
    {
        \App()->UserProfileFieldTreeManager->sortAllTreeItemsAscending();
    }

    private function sortAll()
	{
		$object_replacer = \App()->ObjectMother->createUserProfileFieldTreeItemsReplacer(
				\App()->Request->getValueOrDefault('sortingOrder', null),
				\App()->Request['parentValue'],
				\App()->Request['parentNodeValue']
		);
		$object_replacer->update();
		die();
	}
}
