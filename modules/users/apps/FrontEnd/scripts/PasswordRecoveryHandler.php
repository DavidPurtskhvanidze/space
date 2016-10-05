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

class PasswordRecoveryHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Password Recovery';
	protected $moduleName = 'users';
	protected $functionName = 'password_recovery';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		$message_was_sent = false;

		if (!empty($_REQUEST['username']))
		{
			$user_sid = \App()->UserManager->getUserSIDbyUsername($_REQUEST['username']);

			if (!empty($user_sid))
			{
				$message_was_sent = \App()->UserManager->sendUserPasswordChangeLetter($user_sid);
			}
			else
			{
				\App()->ErrorMessages->addMessage('WRONG_USERNAME');
			}
		}

		if (!$message_was_sent)
		{
			$template_processor->assign('username', \App()->Request['username']);
			$template_processor->display('password_recovery.tpl');
		}
		else
		{
			$template_processor->display('password_change_email_successfully_sent.tpl');
		}
	}
}
