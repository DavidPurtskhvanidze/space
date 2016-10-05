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


namespace modules\classifieds\apps\FrontEnd\scripts;

class AddToComparisonHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Add to Comparison';
	protected $moduleName = 'classifieds';
	protected $functionName = 'add_to_comparison';
	protected $rawOutput = true;

	public function respond()
	{
		$errors = array();
		$action = \App()->ObjectMother->createAddListingToComparisonAction($_REQUEST);
		if ($action->canPerform())
		{
			$action->perform();
		}
		else
		{
			header($_SERVER['SERVER_PROTOCOL'] . ' 409 Conflict');
			$errors = $action->getErrors();
		}
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign('errors', $errors);
		$template_processor->assign('listingCount', \App()->ObjectMother->createListingComparisonTable()->getListingCount());
		$template_processor->display('add_to_comparison.tpl');

	}
}
