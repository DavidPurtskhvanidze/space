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

class TemplateProcessorWYSIWYGPlugin implements \modules\smarty_based_template_processor\lib\IPlugin
{
	public function getPluginType()
	{
		return "block";
	}

	public function getPluginTag()
	{
		return "WYSIWYGEditor";
	}

	public function getPluginCallback()
	{
		return array($this, "WYSIWYGEditor");
	}

	function WYSIWYGEditor($params, $content, $templateProcessor, $repeat)
	{
		if (!$repeat)
		{
			$type = isset($params['type']) ? $params['type'] : '';
			$currentEditor = new \lib\WYSIWYG\WYSIWYGEditorProvider($type);
			$currentEditor->setType($type);
			return $currentEditor->getEditorHTML($content, $params);
		}
	}
}
