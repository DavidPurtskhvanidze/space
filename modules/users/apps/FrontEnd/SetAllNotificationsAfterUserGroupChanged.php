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

class SetAllNotificationsAfterUserGroupChanged implements \modules\users\apps\FrontEnd\IAfterUserGroupChangedAction
{
	/**
	 * Original user object
	 * @var \modules\users\lib\User\User
	 */
	private $originalUser;
	/**
	 * Updated user object
	 * @var \modules\users\lib\User\User
	 */
	private $updatedUser;
	
	public function setOriginalUser($user)
	{
		$this->originalUser = $user;
	}

	public function setUpdatedUser($user)
	{
		$this->updatedUser = $user;
	}
	
	public function perform()
	{
		$prevEmailValue = $this->originalUser->getPropertyValue('email');
		$currEmailValue = $this->updatedUser->getPropertyValue('email');
		if (empty($prevEmailValue) && !empty($currEmailValue))
		{
			$userNotifications = new \core\ExtensionPoint('modules\users\apps\FrontEnd\IUserNotification');
			foreach ($userNotifications as $userNotification)
			{
				$userNotification->setValue($this->updatedUser->getSID(), 1);
			}
			\App()->SuccessMessages->addMessage('ALL_NOTIFICATIONS_ENABLED');
		}
	}
}
