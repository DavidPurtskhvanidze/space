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


namespace modules\template_manager;

class CopyTemplatesToCustomThemesOnEnable implements \modules\module_manager\apps\AdminPanel\IAfterModulesEnable
{
	public function perform($modules)
	{
		$apps = \App()->getAppIDs();
		foreach($apps as $appId)
		{
			$this->performForApp($modules, $appId);
		}
	}

	private function performForApp($modules, $appId)
	{
		$customThemesModules = new \core\ExtensionPoint('apps\ICustomTheme');
		$customThemesModules->filter("apps\\{$appId}\\IThemeModule");
		if (iterator_count($customThemesModules) == 0) return;

		/**
		 * @var $templateProviders \apps\IModuleTemplateProvider[]
		 */
		$templateProviders = \App()->TemplateProvidersManager->getModuleTemplateProviders($appId);

		$tm = new \modules\template_manager\lib\ThemeManager($appId);

		foreach ($templateProviders as $provider)
		{
			if (in_array($provider->getModuleName(), $modules))
			{
				foreach ($customThemesModules as $themeModule)
				{
					$customTheme = $tm->createTheme($themeModule->getCaption());
					$branch = new \modules\template_manager\lib\ThemeInheritanceBranch($customTheme->getParentTheme());
					foreach ($branch as $t)
					{
						$src = $t->getPathToModuleTemplatesDir($provider->getModuleName());
						$dest = $customTheme->getPathToThemeDir() . $provider->getModuleName();
						\App()->FileSystem->copyDirContents($src, $dest, true);
					}
				}
			}
		}
	}
}
