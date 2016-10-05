<?php
/**
 *
 *    Module: users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: users-7.5.0-1
 *    Tag: tags/7.5.0-1@19887, 2016-06-17 13:25:03
 *
 *    This file is part of the 'users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\users\apps\FrontEnd\scripts;

class UserNotificationsHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'User Notifications';
	protected $moduleName = 'users';
	protected $functionName = 'user_notifications';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();

		if (\App()->UserManager->isUserLoggedIn())
		{
			$userSid = \App()->UserManager->getCurrentUserSID();
			$userInfo = \App()->UserManager->getCurrentUserInfo();
			$userNotifications = new \core\ExtensionPoint('modules\users\apps\FrontEnd\IUserNotification');

			if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'save')
			{
				foreach ($userNotifications as $userNotification)
				{
					$userNotification->setValue($userSid, \App()->Request[$userNotification->getId()]);
				}
				\App()->SuccessMessages->addMessage('NOTIFICATION_SETTING_SAVED');
			}

			$notificationSettings = array();
			foreach ($userNotifications as $userNotification)
			{
				$notificationSettings[] = array
				(
					'name' => $userNotification->getId(),
					'caption' => $userNotification->getCaption(),
					'value' => $userNotification->getValue($userSid),
				);
			}

			$templateProcessor->assign("notificationSettings", $notificationSettings);
			$templateProcessor->assign("noUserEmail", is_null($userInfo['email']));
			$templateProcessor->assign("showForm", true);
		}
		else
		{
			\App()->ErrorMessages->addMessage('USER_NOT_LOGGED_IN');
		}

		$templateProcessor->display("user_notifications.tpl");
	}
}
