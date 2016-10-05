<?php
/**
 *
 *    Module: third_party_login v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: third_party_login-7.3.0-1
 *    Tag: tags/7.3.0-1@18640, 2015-08-24 13:43:11
 *
 *    This file is part of the 'third_party_login' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\third_party_login\lib;
 
class ConfigurationWizardStep implements \modules\miscellaneous\IWizardSettingPage
{
	public function getCaption()
	{
		return "Facebook & Twitter";
	}

	public function getContent()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign('settings', \App()->SettingsFromDB->getSettings());
		return $templateProcessor->fetch('third_party_login^configuration_wizard_step.tpl');
	}

    public static function getOrder()
    {
        return 600;
    }
}
