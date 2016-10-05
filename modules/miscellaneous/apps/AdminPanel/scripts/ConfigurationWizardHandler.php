<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\apps\AdminPanel\scripts;

use core\ExtensionPoint;
use lib\Http\RedirectException;
use modules\template_manager\lib\ThemeManager;

class ConfigurationWizardHandler extends SettingsHandler
{
	protected $moduleName = 'miscellaneous';
	protected $functionName = 'configuration_wizard';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();

		$watermarkUploadStatus = null;
		$current_step = \App()->Request->getValueOrDefault('current_step', 0);

		switch (\App()->Request['action'])
		{
			case "save":

                $this->handlePictureUpload();

				unset($_REQUEST['action']);
				\App()->SettingsFromDB->updateSettings($_REQUEST);

				$customSettings = \App()->CustomSettings->getSettingsInfo();
				foreach ($customSettings as $item) 
				{
					if(isset($_REQUEST[$item['id']]))
					{
						\App()->CustomSettings->updateSetting($item['sid'], $item['id'], $_REQUEST[$item['id']]);
					}
				}
				break;
			case "new_theme":

				$themeManager = new ThemeManager("FrontEnd");

				if (\App()->Request['new_theme'] == "")
				{
					break;
				}
				if($themeManager->doesThemeExist(\App()->Request['new_theme']))
				{
					\App()->ErrorMessages->addMessage('THEME_ALREADY_EXISTS');
					break;
				}
				$newThemeName = \App()->Request['new_theme'];
				$baseThemeName = \App()->Request['base_theme'];
				$newTheme = $themeManager->addTheme($newThemeName, $baseThemeName);
				throw new RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI() . "?application_id=FrontEnd&action=install_theme&theme=$newThemeName&module_name={$newTheme->getModuleName()}&current_step={$current_step}");
			case "make_current":
				$themeManager = new ThemeManager("FrontEnd");
				$themeName = \App()->Request['theme'];
				$themeManager->makeThemeCurrent($themeName);
				throw new RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI() . "?application_id=FrontEnd&current_step={$current_step}");
			case "install_theme":
				$themeName = \App()->Request['theme'];
				$moduleName = \App()->Request['module_name'];
				\App()->ModuleManager->installModules(array($moduleName));
				\App()->SuccessMessages->fetchMessages();
				throw new RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI() . "?application_id=FrontEnd&action=make_current&theme=$themeName&current_step={$current_step}");
			break;
		}

		$settings = \App()->SettingsFromDB->getSettings();

		$template_processor->assign("settings", $settings);
		$template_processor->assign("watermarkUploadStatus", $watermarkUploadStatus);
		$template_processor->assign("picturesDir", PATH_TO_ROOT . \App()->SystemSettings['PicturesDir']);


		$pages = new ExtensionPoint('modules\miscellaneous\IWizardSettingPage');
		if(!\App()->ErrorMessages->isEmpty())
		{
			\App()->Request['repeat'] = 1;
		}
		if(!\App()->Request->getValueOrDefault('repeat'))
		{
			$current_step++;
		}
		$template_processor->assign("current_step", $current_step);
        $template_processor->assign("pages", $pages);
		$template_processor->display("configuration_wizard.tpl");
	}

	public function getCaption()
	{
		return "Configuration Wizard";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getSystemPageURL($this->moduleName, $this->functionName);
	}

	public static function getOrder()
	{
		return 250;
	}
}
