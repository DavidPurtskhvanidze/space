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


namespace modules\miscellaneous\lib\ConfigurationWizard;
 
class CustomThemeStep implements \modules\miscellaneous\IWizardSettingPage
{
	public function getCaption()
	{
		return "Custom Theme";
	}

	public function getContent()
	{
		$themeManager = new \modules\template_manager\lib\ThemeManager("FrontEnd");
		
		$themesTree = $themeManager->getThemesTree();
		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign("appId", "FrontEnd");
		$templateProcessor->assign("rootTheme", $themesTree->getItem(\App()->SystemSettings->getSettingForApp("FrontEnd", 'DefaultTheme')));
		return $templateProcessor->fetch('configuration_wizard/custom_theme.tpl');
	}

    public static function getOrder()
    {
        return 350;
    }
}
