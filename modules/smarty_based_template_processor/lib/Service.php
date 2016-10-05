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

use core\ExtensionPoint;
use core\IService;
use modules\template_manager\lib\ThemeManager;

class Service implements IService
{
	/**
	 * @var TemplateProcessor
	 */
	private $templateProcessorPrototype;
	/**
	 * @var TemplateProvider
	 */
	private $templateProviderPrototype;

	private $serviceName = 'TemplateProcessor';

	public function init()
	{
		$this->buildPrototype();
	}

	private function buildPrototype()
	{
		$themeManager = new ThemeManager(\App()->SystemSettings['ApplicationID']);
		$theme = $themeManager->getCurrentSessionTheme();

		$template_processor = new TemplateProcessor();
		$template_processor->setTheme($theme);
		$template_processor->setThemeInheritanceBranch($themeManager->getThemeInheritanceBranch());
		$template_processor->init();
		$template_processor->setHtmlTagConverter(\App()->ObjectMother->createHTMLTagConverterInArray());

		$templateProcessorObjects = new ExtensionPoint('modules\smarty_based_template_processor\lib\IObject');
		foreach ($templateProcessorObjects as $object)
		{
			$template_processor->registerObject($object->getObjectName(), $object->getObjectImplementation());
		}

		$templateProcessorPlugins = new ExtensionPoint('modules\smarty_based_template_processor\lib\IPlugin');
		foreach ($templateProcessorPlugins as $plugin)
		{
			$template_processor->registerPlugin($plugin->getPluginType(), $plugin->getPluginTag(), $plugin->getPluginCallback());
		}

		$templateProcessorFilters = new ExtensionPoint('modules\smarty_based_template_processor\lib\IFilter');
		foreach ($templateProcessorFilters as $filter)
		{
			$template_processor->registerFilter($filter->getFilterType(), $filter->getFilterCallback());
		}

		\App()->GlobalTemplateVariable->setGlobalTemplateVariable('themeInheritanceBranch', $themeManager->getThemeInheritanceBranch());
		\App()->GlobalTemplateVariable->setGlobalTemplateVariable('current_theme', $theme->getName());

		$templateProvider = new TemplateProvider();

		$this->templateProcessorPrototype = $template_processor;
		$this->templateProviderPrototype = $templateProvider;
	}

	public function getFreshInstance($module = null)
	{
		$template_processor = clone $this->templateProcessorPrototype;
		$template_processor->setModuleName($module);
		$templateProvider = clone $this->templateProviderPrototype;
		$templateProvider->setTemplateProcessor($template_processor);
		$templateProvider->registerResources(); //todo:: зарегить ре�?ур�? при �?троении прототипа
		$template_processor->setTemplateProvider($templateProvider);
		$template_processor->assign(\App()->GlobalTemplateVariable->getGlobalTemplateVariables());
		return $template_processor;
	}
}


