<?php
/**
 *
 *    Module: template_manager v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: template_manager-7.5.0-1
 *    Tag: tags/7.5.0-1@19839, 2016-06-17 13:22:09
 *
 *    This file is part of the 'template_manager' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\template_manager\lib;

class TemplatesListCollector
{
	private $moduleName;
	private $themeManager;
	private $templateList = array();
	private $currentTheme;

	public function handle($theme)
	{
		$this->templateList = array_unique(array_merge($this->templateList, $this->getThemeTemplateList($theme)));
		sort($this->templateList);
	}

	private function getThemeTemplateList($themeTreeItem)
	{
		$templateList = array();

		$theme = $this->themeManager->createTheme($themeTreeItem->getId());
		$moduleThemeDirPath = $theme->getPathToModuleTemplatesDir($this->moduleName);

		if (!is_dir($moduleThemeDirPath)) return array();

		$moduleThemeDirPath = realpath($moduleThemeDirPath);
		$dirIterator = new \RegexIterator(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($moduleThemeDirPath)), "/\\.tpl$/");
		foreach ($dirIterator as $fileInfo)
		{
			$templateList[] = str_replace($moduleThemeDirPath . DIRECTORY_SEPARATOR, "", $fileInfo->getRealPath());
		}

		return $templateList;
	}

	public function getTemplateList()
	{
		$currentThemeTemplateList = $this->getThemeTemplateList($this->currentTheme);
		$templateList = array();
		
		foreach ($this->templateList as $templateName)
		{
			$isInherited = array_search($templateName, $currentThemeTemplateList) === false;
			$templateList[$templateName] = $isInherited;
		}
		return $templateList;
	}

	public function setModuleName($moduleName)
	{
		$this->moduleName = $moduleName;
	}

	public function getModuleName()
	{
		return $this->moduleName;
	}

	public function setCurrentTheme($currentTheme)
	{
		$this->currentTheme = $currentTheme;
	}

	public function setThemeManager($themeManager)
	{
		$this->themeManager = $themeManager;
	}
}
