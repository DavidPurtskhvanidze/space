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


namespace modules\main\apps\FrontEnd\scripts;

class DisplayBodyTopTemplatesHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Display Template at the Top of the Body Tag';
	protected $moduleName = 'main';
	protected $functionName = 'display_body_top_templates';

	public function respond()
	{
		$bodyTopTemplateDisplayers = new \core\ExtensionPoint('modules\main\apps\FrontEnd\IBodyTopTemplateDisplayer');
		foreach ($bodyTopTemplateDisplayers as $bodyTopTemplateDisplayer)
		{
			$bodyTopTemplateDisplayer->display();
		}
	}
}
