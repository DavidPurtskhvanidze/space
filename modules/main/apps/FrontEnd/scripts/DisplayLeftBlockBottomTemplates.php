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

class DisplayLeftBlockBottomTemplates extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Display Template at the end of the left block';
	protected $moduleName = 'main';
	protected $functionName = 'display_left_block_bottom_templates';

	public function respond()
	{
		$templateDisplayers = new \core\ExtensionPoint('modules\main\apps\FrontEnd\ILeftBlockBottomTemplateDisplayer');
		foreach ($templateDisplayers as $templateDisplayer)
		{
			$templateDisplayer->display();
		}
	}
}
