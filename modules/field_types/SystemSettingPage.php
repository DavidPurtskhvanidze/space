<?php
/**
 *
 *    Module: field_types v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: field_types-7.5.0-1
 *    Tag: tags/7.5.0-1@19782, 2016-06-17 13:19:23
 *
 *    This file is part of the 'field_types' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\field_types;
 
class SystemSettingPage implements \modules\miscellaneous\lib\ISocialNetworkSettings
{
	public function getContent()
	{
        $templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign('settings', \App()->SettingsFromDB->getSettings());
        $token = \App()->DB->getSingleValue('SELECT `token` FROM `field_types_token`');
		$templateProcessor->assign('hasGoogleAccsessToken', !empty($token));
		return $templateProcessor->fetch('field_types^system_setting_page.tpl');
	}

	public function getOrder()
	{
		return 100;
	}
}
