<?php
/**
 *
 *    Module: I18N v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: I18N-7.5.0-1
 *    Tag: tags/7.5.0-1@19784, 2016-06-17 13:19:28
 *
 *    This file is part of the 'I18N' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\I18N\lib;

class TemplateProcessorPlugin implements \modules\smarty_based_template_processor\lib\IFilter, \modules\smarty_based_template_processor\lib\IPlugin, \modules\smarty_based_template_processor\lib\IObject
{
	public function getFilterType()
	{
		return "pre";
	}

	public function getFilterCallback()
	{
		return array(\App()->I18N, 'replace_translation_alias');
	}

	public function getObjectName()
	{
		return "i18n";
	}

	public function getObjectImplementation()
	{
		return \App()->I18N;
	}

	public function getPluginType()
	{
		return "block";
	}

	public function getPluginTag()
	{
		return "tr";
	}

	public function getPluginCallback()
	{
		return array(\App()->I18N, "translate");
	}
}
