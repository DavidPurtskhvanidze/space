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

class UserGroupsHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\users\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'users';
	protected $functionName = 'user_groups';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		$user_groups_structure = \App()->UserGroupManager->createTemplateStructureForUserGroups();
		$template_processor->assign("user_groups", $user_groups_structure);
		$template_processor->display("user_groups.tpl");
	}

	public static function getOrder()
	{
		return 200;
	}

	public function getCaption()
	{
		return "User Groups";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getPageURLById('user_groups');
	}

	public function getHighlightUrls()
	{
		return array
		(
			\App()->PageRoute->getPageURLById('add_user_group'),
			\App()->PageRoute->getPageURLById('edit_user_group'),
			\App()->PageRoute->getPageURLById('delete_user_group'),
			\App()->PageRoute->getPageURLById('edit_user_profile'),
			\App()->PageRoute->getPageURLById('add_user_profile_field'),
			\App()->PageRoute->getPageURLById('edit_user_profile_field'),
			\App()->PageRoute->getPageURLById('edit_user_profile_field_edit_list'),
		);
	}
}
