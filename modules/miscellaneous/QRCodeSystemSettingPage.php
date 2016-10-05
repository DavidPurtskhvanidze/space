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


namespace modules\miscellaneous;
 
class QRCodeSystemSettingPage implements \modules\miscellaneous\ISystemSettingPage
{
	public function getId()
	{
		return "QRCode";
	}

	public function getCaption()
	{
		return "QR Code";
	}

	public function getContent()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign('settings', \App()->SettingsFromDB->getSettings());
		return $templateProcessor->fetch('qr_code_system_setting_page.tpl');
	}

    public static function getOrder()
    {
        return 100;
    }
}