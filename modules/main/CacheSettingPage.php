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


namespace modules\main;
 
class CacheSettingPage implements \modules\miscellaneous\ISystemSettingPage
{
	public function getId()
	{
		return "Cache";
	}

	public function getCaption()
	{
		return "Cache";
	}

	public function getContent()
	{
        $templateProcessor = \App()->getTemplateProcessor();
        $templateProcessor->assign('settings', \App()->SettingsFromDB->getSettings());
        return $templateProcessor->fetch('main^cache_setting_page.tpl');
	}

    public static function getOrder()
    {
        return 20;
    }
}
