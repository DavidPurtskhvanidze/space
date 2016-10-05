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

class EditListHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'users';
	protected $functionName = 'edit_list';

	private function getEditListController()
	{
		$controller = new \lib\ORM\Controllers\EditListController();
		$controller->setFieldManager(\App()->UserProfileFieldManager);
		$controller->setListItemManager(new \modules\users\lib\UserProfileField\UserProfileFieldListItemManager());
		$controller->setInputData($_REQUEST);
		return $controller;
	}
	
	public function respond()
	{
		$edit_list_controller = $this->getEditListController();

		if(!$edit_list_controller->isDataValid())
		{
			echo 'Invalid User Profile Field SID is specified';
		}
		else
		{
			$template_processor = \App()->getTemplateProcessor();
			switch ($edit_list_controller->getAction())
			{
				case 'add':
					if ($edit_list_controller->isValidValueSubmitted())
					{
						$order = \App()->Request->getValueOrDefault('order','end');
						$afterItemSid = \App()->Request['after_item_sid'];
						if (!$edit_list_controller->saveItem($order, $afterItemSid))
							$template_processor->assign("error", 'LIST_VALUE_ALREADY_EXISTS');

					}
					else
					{
						$template_processor->assign("error", 'LIST_VALUE_IS_EMPTY');
					}
					break;
				case 'delete': $edit_list_controller->deleteItem(); break;
                case 'delete_selected_items':
                    $valueSIDs = \App()->Request['values'];
                    $fieldSID = \App()->Request['field_sid'];
                    if (!empty($valueSIDs))
                    {
                        $edit_list_controller->deleteItemsBySID($valueSIDs);
                        $edit_list_controller->recalculateItemsOrderByFieldSID($fieldSID);
                        \App()->SuccessMessages->addMessage('SELECTED_LIST_VALUES_DELETED');
                    }
                    break;
				case 'move_up': $edit_list_controller->moveUpItem(); break;
				case 'move_down': $edit_list_controller->moveDownItem(); break;
				case 'sort':
					$object_replacer = \App()->ObjectMother->createUserProfileFieldListItemsReplacer(
							\App()->Request->getValueOrDefault('sortingOrder', null),
							\App()->Request['parentValue']
					);
					$object_replacer->update();
					die();
					break;
				case 'sort_ascending':
					$edit_list_controller->sortItemsAscending();
					break;

				case 'sort_descending':
					$edit_list_controller->sortItemsDescending();
					break;
			}
			$display_list_controller = $this->getDisplayListController();
			$display_list_controller->display("user_profile_list_editing.tpl");
		}
	}
	
	private function getDisplayListController()
	{
		$controller = new \modules\users\lib\UserProfileField\UserProfileDisplayListController();
		$controller->setFieldManager(\App()->UserProfileFieldManager);
		$controller->setListItemManager(new \modules\users\lib\UserProfileField\UserProfileFieldListItemManager());
		$controller->setTemplateProcessor(\App()->getTemplateProcessor());
		$controller->setUserGroupManager(\App()->UserGroupManager);
		$controller->setInputData($_REQUEST);
		return $controller;
	}
}
