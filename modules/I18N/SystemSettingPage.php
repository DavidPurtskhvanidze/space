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


namespace modules\I18N;
 
class SystemSettingPage implements \modules\miscellaneous\ISystemSettingPage
{
	public function getId()
	{
		return "LanguageAndRegionalSettings";
	}

	public function getCaption()
	{
		return "Language and Regional Settings";
	}

	public function getContent()
	{
        $i18n = \App()->I18N;
		$templateProcessor = \App()->getTemplateProcessor();
        $templateProcessor->assign("i18n_domains", $i18n->getDomainsData());
        $templateProcessor->assign("i18n_languages", $i18n->getActiveLanguagesData());
		$templateProcessor->assign('settings', \App()->SettingsFromDB->getSettings());
		return $templateProcessor->fetch('I18N^system_setting_page.tpl');
	}

    public static function getOrder()
    {
        return 20;
    }
}
