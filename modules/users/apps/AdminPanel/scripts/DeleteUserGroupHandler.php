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


namespace modules\users\apps\AdminPanel\scripts;

class DeleteUserGroupHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'users';
	protected $functionName = 'delete_user_group';

	public function respond()
	{
		$error = null;
		$userGroupSid = \App()->Request['sid'];

		if (!is_null($userGroupSid))
		{
			$canPerform = true;
			$validators = new \core\ExtensionPoint('modules\users\apps\AdminPanel\IDeleteUserGroupValidator');
			foreach ($validators as $validator)
			{
				$validator->setUserGroupSid($userGroupSid);
				$canPerform &= $validator->isValid();
			}
			if ($canPerform)
			{
				\App()->UserGroupManager->deleteUserGroupBySID($userGroupSid);
			}
		}
		else
		{
			\App()->ErrorMessages->addMessage('EMPTY_USER_GROUP_SID');
		}
		throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('user_groups'));
	}
}
