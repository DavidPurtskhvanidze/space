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

class ChangePasswordHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Change Password';
	protected $moduleName = 'users';
	protected $functionName = 'change_password';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		$username = \App()->Request['username'];
		$verification_key = \App()->Request['verification_key'];
		$password_was_changed = false;

		$user_info = \App()->UserManager->getUserInfoByUserName($username);
		//http://sergarr/classifieds/application/system/users/change_password/?username=demo&verification_key=7ui0dacsqemnvj4pozkgbthyf5wr86l312x9
		if (empty($user_info))
		{
			\App()->ErrorMessages->addMessage('EMPTY_USERNAME');
		}
		elseif (empty($verification_key))
		{
			\App()->ErrorMessages->addMessage('EMPTY_VERIFICATION_KEY');
		}
		elseif ($user_info['verification_key'] != $verification_key)
		{
			\App()->ErrorMessages->addMessage('WRONG_VERIFICATION_KEY');
		}
		elseif ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if (!empty($_REQUEST['password']) && $_REQUEST['password'] == $_REQUEST['confirm_password'])
			{
				$password_was_changed = \App()->UserManager->changeUserPassword($user_info['sid'], $_REQUEST['password']);
			}
			else
			{
				\App()->ErrorMessages->addMessage('PASSWORD_NOT_CONFIRMED');
			}
		}

		if ($password_was_changed)
		{
			\App()->SuccessMessages->addMessage('PASSWORD_WAS_CHANGED');
		}

		$template_processor->assign('username', $username);
		$template_processor->assign('password_was_changed', $password_was_changed);
		$template_processor->assign('verification_key', $verification_key);
		$template_processor->display("change_password.tpl");

	}
}
