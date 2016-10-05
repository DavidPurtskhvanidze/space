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

/**
 * Модификатор преобразует �?троки из вида snake_case в camelCase �?о �?трочной первой буквой
 */
class TemplateProcessorUnderscoreToCssClassNamePlugin implements \modules\smarty_based_template_processor\lib\IPlugin
{
	public function getPluginType()
	{
		return "modifier";
	}

	public function getPluginTag()
	{
		return "to_css_class_name";
	}

	public function getPluginCallback()
	{
		return array($this, "toCssClassName");
	}

	public function toCssClassName($value)
	{
        $words = explode('_', strtolower($value));

        $camelCase = '';
        foreach ($words as $word) {
            $camelCase .= ucfirst(trim($word));
        }

        return lcfirst($camelCase);
	}
}
