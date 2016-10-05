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
 
class SystemSettingPage implements \modules\miscellaneous\ISystemSettingPage
{
	public function getId()
	{
		return "General";
	}

	public function getCaption()
	{
		return "General";
	}

	public function getContent()
	{
        $paymentMethods = new \core\ExtensionPoint('modules\payment_system\lib\IPaymentMethod');
        $paymentMethodInfo = array();
        foreach ($paymentMethods as $paymentMethod)
        {
            $paymentMethodInfo[get_class($paymentMethod)] = $paymentMethod->getCaption();
        }
		$templateProcessor = \App()->getTemplateProcessor();
        $templateProcessor->assign('paymentMethods', $paymentMethodInfo);
        $templateProcessor->assign('settings', \App()->SettingsFromDB->getSettings());
		$templateProcessor->assign("picturesDir", PATH_TO_ROOT . \App()->SystemSettings['PicturesDir']);
        return $templateProcessor->fetch('main^system_setting_page.tpl');
	}

    public static function getOrder()
    {
        return 10;
    }
}
