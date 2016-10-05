<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\apps\FrontEnd\scripts;

class ReportImproperContentHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Report Improper Content';
	protected $moduleName = 'miscellaneous';
	protected $functionName = 'report_improper_content';

	public function respond()
	{
		$action = \App()->ObjectMother->createReportImproperContentAction(\App()->Request->getRequest());
		if ($action->canPerform())
		{
			$action->perform();
		}
		else
		{
			$template_processor = \App()->getTemplateProcessor();
			$template_processor->assign("errors", $action->getErrors());
			$template_processor->display("errors.tpl");
		}
	}
}
