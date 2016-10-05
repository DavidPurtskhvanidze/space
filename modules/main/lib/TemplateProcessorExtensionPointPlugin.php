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

class TemplateProcessorExtensionPointPlugin implements \modules\smarty_based_template_processor\lib\IPlugin
{
	public function getPluginType()
	{
		return "function";
	}

	public function getPluginTag()
	{
		return "extension_point";
	}

	public function getPluginCallback()
	{
		return array($this, "module");
	}

	public function module($params, $templateProcessor)
	{
		$name = isset($params['name']) ? $params['name'] : '';
		unset($params['name']);

        if (empty($name))
        {
            return '<!-- Extension Point is not specified in call to {module ..} -->';
        }
        $this->parseParameters($params);

        $content = "";
        $widgets = new \core\ExtensionPoint($name);
        foreach ($widgets as $widget)
        {
            $content .= $widget->fetch($params, $templateProcessor);
        }
        return $content;
	}

	private function parseParameters(&$params)
	{
		if (isset($params['QUERY_STRING']))
		{
			$parameters = array();
			parse_str($params['QUERY_STRING'], $parameters);
			$params = array_merge($parameters, $params);
			unset($params['QUERY_STRING']);
		}
	}

}
