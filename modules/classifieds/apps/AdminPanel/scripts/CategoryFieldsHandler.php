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

class CategoryFieldsHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'category_fields';

	public function respond()
	{
		$categorySid = \App()->Request['sid'];

		$inheritanceBranchCategorySids = \App()->CategoryManager->getCategoryParentTreeBySID($categorySid);

		$request['category_sid']['in'] = $inheritanceBranchCategorySids;
		$fields = \App()->ListingFieldManager->getListingFieldsByRequest($request, $categorySid);

		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign("ancestors", array_reverse(\App()->CategoryTree->getAncestorsInfo($categorySid)) );
		$template_processor->assign("category_sid", $categorySid);
		$template_processor->assign("listing_fields", $fields);
	    $template_processor->assign("messages", \App()->Request['message']);
		$template_processor->display("category_fields.tpl");
	}
}
