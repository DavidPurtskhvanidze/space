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

class UserMenuHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'User Menu';
	protected $moduleName = 'users';
	protected $functionName = 'user_menu';
	protected $rawOutput = true;

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();

		if (\App()->UserManager->isUserLoggedIn())
		{
			$user_info = \App()->UserManager->getCurrentUserInfo();

			if (!empty($user_info))
			{
				$user_group_info = \App()->UserGroupManager->getUserGroupInfoBySID($user_info['user_group_sid']);
				$user_menu_template = !empty($user_group_info['user_menu_template']) ? $user_group_info['user_menu_template'] : 'user_menu.tpl';
				$template_processor->display($user_menu_template);
			}
		}
		else
		{
			$template_processor->display("guest_menu.tpl");
		}
	}
}
