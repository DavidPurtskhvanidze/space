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


namespace modules\users\apps\FrontEnd;

class NotifyAdminAfterRegisterUser implements \modules\users\apps\FrontEnd\IAfterRegisterUserAction, \modules\miscellaneous\apps\AdminPanel\IAdminNotification
{
	private $user;

	public function setUser($user)
	{
		$this->user = $user;
	}

	public function perform()
	{
		if (\App()->SettingsFromDB->getSettingByName($this->getId()))
		{
			return \App()->EmailService->sendToAdmin('email_template:admin_user_registration_email', array('user' => \App()->UserManager->getUserInfoBySID($this->user->getSID())));
		}
		return false;
	}

	public function getId()
	{
		return 'notify_on_user_registration';
	}

	public function getCaption()
	{
		return 'Notify when User has Registered';
	}
}
