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

class AddCategoryHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'add_category';

	public function respond()
	{
		$category = \App()->CategoryManager->getCategory($_REQUEST);
		$add_category_form = new \lib\Forms\Form($category);
		$form_is_submitted = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add');
		if ($form_is_submitted && $add_category_form->isDataValid())
		{
			\App()->CategoryManager->saveCategory($category);
			\App()->ListingFieldManager->copyFieldsOrderFromParent($category);
			throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('edit_category') . '?sid=' . $category->getSID());
		}
		else
		{
			$template_processor = \App()->getTemplateProcessor();
			$add_category_form->registerTags($template_processor);
			$template_processor->assign("form_fields", $add_category_form->getFormFieldsInfo());
			$template_processor->assign("ancestors", array_reverse(\App()->CategoryTree->getAncestorsInfo($_REQUEST['parent'])));
			$template_processor->display("add_category.tpl");
		}
	}
}
