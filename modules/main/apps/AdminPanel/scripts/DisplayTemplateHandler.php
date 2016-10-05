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


namespace modules\main\apps\AdminPanel\scripts;

class DisplayTemplateHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $displayName = 'Display Template';
	protected $moduleName = 'main';
	protected $functionName = 'display_template';
	protected $parameters = array('template');

	public function respond()
	{
		$params = \App()->Request['template_params'];
		$params = ($params && is_array($params)) ? $params : array();
		
		$templateProcessor = \App()->getTemplateProcessor();
		foreach ($params as $paramName => $value)
		{
			$templateProcessor->assign($paramName, $value);
		}
		$templateProcessor->display(\App()->Request['template']);
	}
}
