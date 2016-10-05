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


namespace modules\users\apps\AdminPanel;

class NotifyUserAfterChangeUserGroup implements IAfterUserGroupChangeAction
{
	private $userSids;

	public function setUserSids($userSids)
	{
		$this->userSids = $userSids;
	}

	public function perform()
	{
		$userSids = $this->userSids;
		foreach ($userSids as $userSid) {
			$userInfo = \App()->UserManager->getUserInfoBySID($userSid);
			$userGroupInfo = \App()->UserGroupManager->getUserGroupInfoBySID($userInfo['user_group_sid']);

			$userSiteUrl = \App()->SystemSettings->getSettingForApp('FrontEnd', 'SiteUrl');

			\App()->EmailService->send($userInfo['email'], 'email_template:user_group_change_email', array(
				'user' => $userInfo,
				'userGroupInfo' => $userGroupInfo,
				'userSiteUrl' => $userSiteUrl,
			));
		}
	}
}
