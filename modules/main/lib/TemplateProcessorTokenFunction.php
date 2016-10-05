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


namespace modules\main\lib;

use modules\smarty_based_template_processor\lib\IPlugin;

class TemplateProcessorTokenFunction implements IPlugin
{
	public function getPluginType()
	{
		return 'function';
	}

	public function getPluginTag()
	{
		return 'CSRF_token';
	}

	public function getPluginCallback()
	{
		return array($this, 'getHtmlCode');
	}

	public function getHtmlCode($params, $templateProcessor)
	{
        return '<input type="hidden" name="secure_token" value="' . \App()->Token->getToken() . '">';
	}
}
 