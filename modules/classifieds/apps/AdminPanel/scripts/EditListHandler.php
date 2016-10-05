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


namespace modules\classifieds\apps\AdminPanel\scripts;

class EditListHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'edit_list';

	private function getEditListController()
	{
		$controller = new \lib\ORM\Controllers\EditListController();
		$controller->setFieldManager(\App()->ListingFieldManager);
		$controller->setListItemManager(new \modules\classifieds\lib\ListingField\ListingFieldListItemManager());
		$controller->setInputData($_REQUEST);
		return $controller;
	}

	public function respond()
	{
		$edit_list_controller = $this->getEditListController();
		$extra_info = array();
		if (!$edit_list_controller->isDataValid())
		{
			echo 'Invalid Category Field SID is specified';
		}
		else
		{
			switch ($edit_list_controller->getAction())
			{
				case 'add':
					if ($edit_list_controller->isValidValueSubmitted())
					{
						$order = \App()->Request->getValueOrDefault('order','end');
						$afterItemSid = \App()->Request['after_item_sid'];
						if (!$edit_list_controller->saveItem($order, $afterItemSid)) \App()->ErrorMessages->addMessage('LIST_VALUE_ALREADY_EXISTS');
					}
					break;

				case 'delete':
					$listItem = $edit_list_controller->getListItem();
					\App()->ListingManager->onDeleteListItem($listItem);
					$edit_list_controller->deleteItem();
					break;

				case 'delete_all_items':
                    $fieldSID =  isset($_REQUEST['field_sid']) ? $_REQUEST['field_sid'] : null;
					\App()->ListingManager->onDeleteAllListItemsByFieldSID($fieldSID);
					$edit_list_controller->deleteAllItems();
                    \App()->SuccessMessages->addMessage('ALL_LIST_VALUES_DELETED');
					break;

                case 'delete_selected_items':
                    $valueSIDs = \App()->Request['values'];
                    $fieldSID = \App()->Request['field_sid'];
                    if (!empty($valueSIDs))
                    {
                        \App()->ListingManager->onDeleteSelectedListItemsBySID($valueSIDs, $fieldSID);
                        $edit_list_controller->deleteItemsBySID($valueSIDs);
                        $edit_list_controller->recalculateItemsOrderByFieldSID($fieldSID);
                        \App()->SuccessMessages->addMessage('SELECTED_LIST_VALUES_DELETED');
                    }
                    break;

                case 'move_up':
					$edit_list_controller->moveUpItem();
					break;

				case 'move_down':
					$edit_list_controller->moveDownItem();
					break;

				case 'sort':
					$object_replacer = \App()->ObjectMother->createListingFieldListItemsReplacer(
							\App()->Request->getValueOrDefault('item', null),
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
			$category_tree = \App()->CategoryTree;
			$extra_info["ancestors"] = array_reverse($category_tree->getAncestorsInfo($display_list_controller->getTypeSID()));
			$display_list_controller->display("listing_list_editing.tpl", $extra_info);
		}
	}

	private function getDisplayListController()
	{
		$controller = new \modules\classifieds\lib\ListingField\ListingDisplayListController();
		$controller->setFieldManager(\App()->ListingFieldManager);
		$controller->setListItemManager(new \modules\classifieds\lib\ListingField\ListingFieldListItemManager());
		$controller->setTemplateProcessor(\App()->getTemplateProcessor());
		$controller->setCategoryManager(\App()->CategoryManager);
		$controller->setInputData($_REQUEST);
		return $controller;
	}
}
?>
