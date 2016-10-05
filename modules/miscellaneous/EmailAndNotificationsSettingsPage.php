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

class EmailAndNotificationsSettingsPage implements ISystemSettingPage
{
	public function getId()
	{
		return 'EmailAndNotifications';
	}

	public function getCaption()
	{
		return 'Email And Notifications';
	}

	public function getContent()
	{
        //Email Settings
		$emailSettings = array();
		$adminSettings = new \core\ExtensionPoint('modules\miscellaneous\apps\AdminPanel\IAdminEmailSettings');
		foreach ($adminSettings as $adminSetting)
		{
			$emailSettings[] = array
			(
				'name' => $adminSetting->getId(),
				'caption' => $adminSetting->getCaption(),
			);
		}

        //Notification Settings
        $notificationSettings = array();
        $adminNotifications = new \core\ExtensionPoint('modules\miscellaneous\apps\AdminPanel\IAdminNotification');
        foreach ($adminNotifications as $adminNotification)
        {
            $notificationSettings[] = array
            (
                'name' => $adminNotification->getId(),
                'caption' => $adminNotification->getCaption(),
            );
        }

		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign('emailSettings', $emailSettings);
        $templateProcessor->assign('notificationSettings', $notificationSettings);
		$templateProcessor->assign('settings', \App()->SettingsFromDB->getSettings());
		$templateProcessor->display('email_and_notifications_settings.tpl');
	}

    public static function getOrder()
    {
        return 50;
    }
}
