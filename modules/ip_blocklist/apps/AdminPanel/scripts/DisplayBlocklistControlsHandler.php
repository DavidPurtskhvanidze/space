<?php
/**
 *
 *    Module: ip_blocklist v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: ip_blocklist-7.5.0-1
 *    Tag: tags/7.5.0-1@19789, 2016-06-17 13:19:41
 *
 *    This file is part of the 'ip_blocklist' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\ip_blocklist\apps\AdminPanel\scripts;

class DisplayBlocklistControlsHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'ip_blocklist';
	protected $functionName = 'display_blocklist_controls';

	public function respond()
	{
		$blockListControls = array();
		$actions = new \core\ExtensionPoint('modules\\ip_blocklist\\apps\\AdminPanel\\IBlocklistControl');
		foreach ($actions as $action)
		{
			$blockListControls[] = $action->getControlInfo();
		}
		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign('blockListControls', $blockListControls);
		$templateProcessor->display('display_blocklist_controls.tpl');
	}
}
