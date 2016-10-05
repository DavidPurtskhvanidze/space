<?php
/**
 *
 *    Module: main v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: main-7.5.0-1
 *    Tag: tags/7.5.0-1@19796, 2016-06-17 13:19:59
 *
 *    This file is part of the 'main' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\main\apps\MobileFrontEnd\scripts;

class DisplayBeforeHeaderTemplates extends \apps\MobileFrontEnd\ContentHandlerBase
{
	protected $displayName = 'Display Templates Before Header';
	protected $moduleName = 'main';
	protected $functionName = 'display_before_header_templates';

	public function respond()
	{
		$templateDisplayers = new \core\ExtensionPoint('modules\\main\\apps\\MobileFrontEnd\\IBeforeHeaderTemplateDisplayer');
		foreach ($templateDisplayers as $templateDisplayer)
		{
			$templateDisplayer->display();
		}
	}
}
