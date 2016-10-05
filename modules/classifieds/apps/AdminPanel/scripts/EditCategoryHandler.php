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

class EditCategoryHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\classifieds\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'edit_category';

	public function respond()
	{
		$categorySid = \App()->Request['sid'];
		$messages = \App()->Request['message'];

		if (!is_null($categorySid))
		{
			$templateProcessor = \App()->getTemplateProcessor();
			$categoryTree = \App()->CategoryTree;

			$node = $categoryTree->getNode($categorySid);
			$templateProcessor->assign("messages", $messages);
			$templateProcessor->assign("category", $node->toArray());
			$templateProcessor->assign("ancestors", array_reverse($categoryTree->getAncestorsInfo($categorySid)));
			$templateProcessor->assign("categories", \App()->CategoryManager->getChildrenTemplateStructure($categorySid));
			if (isset($_REQUEST['dest_cat_id']))
				$templateProcessor->assign('destination_category', \App()->CategoryManager->getInfoBySID($_REQUEST['dest_cat_id']));
			$templateProcessor->display('listing_subtypes.tpl');
		}
	}

	public static function getOrder()
	{
		return 100;
	}

	public function getCaption()
	{
		return "Categories";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getPageURLById('edit_category') . '?sid=0';
	}

	public function getHighlightUrls()
	{
		return array
		(
            \App()->PageRoute->getPageURLById('add_category'),
            \App()->PageRoute->getPageURLById('edit_category'),
            \App()->PageRoute->getPageURLById('delete_category'),

            \App()->PageRoute->getPageURLById('add_category_field'),
            \App()->PageRoute->getPageURLById('edit_category_field'),
            \App()->PageRoute->getPageURLById('delete_category_field'),

            \App()->PageRoute->getSystemPageURL($this->moduleName, 'category_fields'),
            \App()->PageRoute->getPageURLById('edit_listing_field_edit_list'),
            \App()->PageRoute->getPageURLById('edit_listing_field_edit_tree'),
            \App()->PageRoute->getPageURLById('import_tree_data'),
		);
	}
}
