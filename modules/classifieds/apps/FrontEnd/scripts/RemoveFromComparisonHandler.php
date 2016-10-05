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

class RemoveFromComparisonHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Remove from Comparison';
	protected $moduleName = 'classifieds';
	protected $functionName = 'remove_from_comparison';
	protected $rawOutput = true;

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		$action = \App()->ObjectMother->createRemoveListingFromComparisonAction($_REQUEST);
		if ($action->canPerform())
		{
			$action->perform();
			if (isset($_REQUEST['HTTP_REFERER']) && !empty($_REQUEST['HTTP_REFERER']))
			{
				throw new \lib\Http\RedirectException($_REQUEST['HTTP_REFERER']);
			}
		}
		else
		{
			header($_SERVER['SERVER_PROTOCOL'] . ' 409 Conflict');
			$template_processor->assign('renderTemplate', false);
		}
		$template_processor->assign('listingCount', \App()->ObjectMother->createListingComparisonTable()->getListingCount());
		$template_processor->display('remove_from_comparison.tpl');
	}
}
