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

class AddCategoryFieldHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'add_category_field';

	public function respond()
	{
		$category_sid = isset($_REQUEST['category_sid']) ? $_REQUEST['category_sid'] : null;
		if (!is_null($category_sid))
		{
			$listing_field = \App()->ListingFieldManager->createListingField($_REQUEST, $category_sid);
			$listing_field->deleteProperty('category_sid');
			$add_listing_field_form = new \lib\Forms\Form($listing_field);
			$form_is_submitted = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add');
			if ($form_is_submitted && $add_listing_field_form->isDataValid($category_sid))
			{
				\App()->ListingFieldManager->addColumnToListingTableForField($listing_field);
				\App()->ListingFieldManager->saveListingField($listing_field);
				\App()->ListingFieldManager->addListingFieldToOrderTable($listing_field);
				throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'category_fields') . "?sid=$category_sid");
			}
			else
			{
				$template_processor = \App()->getTemplateProcessor();
				$template_processor->assign("category_sid", $category_sid);
				$template_processor->assign("category_info", \App()->CategoryManager->getInfoBySID($category_sid));
				$add_listing_field_form->registerTags($template_processor);
				$template_processor->assign("form_fields", $add_listing_field_form->getFormFieldsInfo());
				$category_tree = \App()->CategoryTree;
				$template_processor->assign("ancestors", array_reverse($category_tree->getAncestorsInfo($category_sid)));
				$template_processor->display("add_category_field.tpl");
			}

		}
		else
		{
			echo 'The system cannot proceed as Category SID is not set';
		}
	}
}
