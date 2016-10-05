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

class DeleteUserProfileFieldHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'users';
	protected $functionName = 'delete_user_profile_field';

	public function respond()
	{
			$field_sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : null;

			if (is_null($field_sid))
			{
				echo 'The system  cannot proceed as User Group SID is not set';
				return;
			}

			$field_info = \App()->UserProfileFieldManager->getInfoBySID($field_sid);
			$user_group_sid = $field_info['user_group_sid'];
			$field = \App()->UserProfileFieldManager->createUserProfileField($field_info, $field_info['type']);
			\App()->UserProfileFieldManager->dropTableColumnForField($field);
			\App()->UserProfileFieldManager->deleteUserProfileFieldBySID($field_sid);
			throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('edit_user_profile') . '?user_group_sid=' . $user_group_sid);
	}
}
?>
