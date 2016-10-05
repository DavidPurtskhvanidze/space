<?php
/**
 *
 *    Module: smarty_based_template_processor v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: smarty_based_template_processor-7.5.0-1
 *    Tag: tags/7.5.0-1@19835, 2016-06-17 13:21:56
 *
 *    This file is part of the 'smarty_based_template_processor' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\smarty_based_template_processor\lib;

class TemplateProcessor extends \Smarty
{
	private $htmlTagConverter;
	private $moduleName;
	private $theme;
	private $themeInheritanceBranch;
	private $templateProvider;
	private $addTemplateStartEndComments = true;

	function init()
	{
		$this->error_reporting = E_ALL & ~E_NOTICE;
		$this->compile_dir = \App()->FileSystem->getWritableCacheDir('templates/' . \App()->SystemSettings['ApplicationID'] . "/" . $this->theme->getName()) . "/";
	}
	
	function setIfAddTemplateStartEndComments($flag)
	{
		$this->addTemplateStartEndComments = $flag;
	}
	function ifAddTemplateStartEndComments()
	{
		return $this->addTemplateStartEndComments;
	}

	function filterThenAssign($tpl_var, $value = null)
	{
		if (is_array($tpl_var))
		{
			$this->htmlTagConverter->explore($tpl_var);
		}
		if (!is_null($value))
		{
			$this->htmlTagConverter->explore($value);
		}
		parent::assign($tpl_var, $value);
	}

	public function filterValueToAssign($value)
	{
		if (!is_null($value))
		{
			$this->htmlTagConverter->explore($value);
		}
		return $value;
	}

	public function __clone()
	{
		parent::__clone();
		unset($this->Register);
		unset($this->Filter);
	}
	public function fetch($template = null, $cache_id = null, $compile_id = null, $parent = null, $display = false, $merge_tpl_vars = true, $no_output_filter = false)
	{
        if (is_null($compile_id)) $this->compile_id = $this->moduleName;
		return parent::fetch($template, $cache_id, $compile_id, $parent, $display, $merge_tpl_vars, $no_output_filter);
	}

	public function templateExists($template)
	{
		try
		{
			$this->templateProvider->getTemplate($template);
		}
		catch (TemplateNotFoundException $e)
		{
			return false;
		}
		return true;
	}

	public function setModuleName($moduleName)
	{
		$this->moduleName = $moduleName;
	}

	public function getModuleName()
	{
		return $this->moduleName;
	}

	public function setTheme($theme)
	{
		$this->theme = $theme;
	}

	public function getTheme()
	{
		return $this->theme;
	}

	public function setThemeInheritanceBranch($themeInheritanceBranch)
	{
		$this->themeInheritanceBranch = $themeInheritanceBranch;
	}

	public function getThemeInheritanceBranch()
	{
		return $this->themeInheritanceBranch;
	}

	public function setHtmlTagConverter($htmlTagConverter)
	{
		$this->htmlTagConverter = $htmlTagConverter;
	}

	public function setTemplateProvider($templateProvider)
	{
		$this->templateProvider = $templateProvider;
	}
}
