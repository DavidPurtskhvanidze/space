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

class EditCategoryFieldHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'edit_category_field';

	public function respond()
	{

		$template_processor = \App()->getTemplateProcessor();
		$listing_field_sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : null;
		
		if (!is_null($listing_field_sid)) 
		{
			$listing_field_info = \App()->ListingFieldManager->getInfoBySID($listing_field_sid);
			$originalField = \App()->ListingFieldManager->createListingField($listing_field_info, $listing_field_info['category_sid']);
			$listing_field_info = array_merge($listing_field_info, $_REQUEST);
			$listing_field = \App()->ListingFieldManager->createListingField($listing_field_info, $listing_field_info['category_sid']);
			$listing_field->setSID($listing_field_sid);
			$listing_field->deleteProperty('category_sid');
			$edit_form = new \lib\Forms\Form($listing_field);
			$edit_form->makeDisabled("type");
			$form_submitted = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'save_info');

			if ($form_submitted && $edit_form->isDataValid($listing_field->getCategorySID()))
			{
				\App()->ListingFieldManager->saveListingField($listing_field);
				if (!$originalField->equals($listing_field))
				{
					\App()->ListingFieldManager->updateColumnForField($originalField,$listing_field);
				}
				throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'category_fields') . '?sid=' . $listing_field->getCategorySID());
				
			}
			else
			{
				$edit_form->registerTags($template_processor);
				$template_processor->assign("form_fields", $edit_form->getFormFieldsInfo());
				$template_processor->assign("category_sid", $listing_field->getCategorySID());
				$template_processor->assign("field_type", $listing_field->getFieldType());
				$template_processor->assign("field_sid", $listing_field->getSID());
				$category_info = \App()->CategoryManager->getInfoBySID($listing_field->getCategorySID());
				$template_processor->assign("category_info", $category_info);
				$template_processor->assign("listing_field_info", $listing_field_info);
				$category_tree = \App()->CategoryTree;
				$template_processor->assign("ancestors", array_reverse($category_tree->getAncestorsInfo($listing_field->getCategorySID())));
				$template_processor->display("edit_category_field.tpl");
			}
		}
	}
}
