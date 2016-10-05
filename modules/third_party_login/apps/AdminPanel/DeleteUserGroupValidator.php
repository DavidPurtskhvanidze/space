<?php
/**
 *
 *    Module: third_party_login v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: third_party_login-7.3.0-1
 *    Tag: tags/7.3.0-1@18640, 2015-08-24 13:43:11
 *
 *    This file is part of the 'third_party_login' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\third_party_login\apps\AdminPanel;

class DeleteUserGroupValidator implements \modules\users\apps\AdminPanel\IDeleteUserGroupValidator
{
	private $userGroupSid;

	public function setUserGroupSid($userGroupSid)
	{
		$this->userGroupSid = $userGroupSid;
	}

	public function isValid()
	{
		if (\App()->SettingsFromDB->getSettingByName('third_party_auth_user_group_sid') == $this->userGroupSid)
		{
			\App()->ErrorMessages->addMessage('DEFAULT_USER_GROUP_FOR_THIRD_PARTY_LOGIN', array('userGroupName' => \App()->UserGroupManager->getUserGroupNameBySID($this->userGroupSid)), 'third_party_login');
			return false;
		}
		return true;
	}
}
