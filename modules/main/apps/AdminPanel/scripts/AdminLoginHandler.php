<?php
/**
 *
 *    Module: main v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: main-7.5.0-1
 *    Tag: tags/7.5.0-1@19796, 2016-06-17 13:19:59
 *
 *    This file is part of the 'main' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\main\apps\AdminPanel\scripts;

class AdminLoginHandler extends \apps\AdminPanel\ContentHandlerBase implements \core\IStartupScript
{
	protected $moduleName = 'main';
	protected $functionName = 'admin_login';
    protected $isPermissionRequired = false;

	public function respond()
	{
		$adminManager = new \modules\main\lib\TwoFactorAdminManager();

		if(!$adminManager->admin_authed())
		{
			if (\modules\main\lib\LoginFailures::getInstance()->isBanned())
			{
				throw new \lib\Http\UnauthorizedException($this->getBanPage());
				exit();
			}
			if(\App()->Request['showsplash'] === 'true')
			{
				header("Content-type:text/html;charset=utf-8");
				include(\App()->SystemSettings['AdminSplashScreenUrl']);
				exit;
			}

			if (\App()->Request['action'] == "login" && $adminManager->admin_login(\App()->Request['admin_username'], \App()->Request['admin_password']))
			{
				$redirectUri = \App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI();
				if (!empty($_SERVER['QUERY_STRING'])) $redirectUri .= '?' . $_SERVER['QUERY_STRING'];
				throw new \lib\Http\RedirectException($redirectUri);
			}
			elseif (\App()->Request['action'] == "recover_password")
			{
				if ($adminManager->sendPasswordRecoveryEmail(\App()->Request['admin_username']))
				{
					\App()->SuccessMessages->addMessage("CHANGE_PASSWORD_EMAIL_SENT");
					$this->showPasswordRecoverPage(true);
				}
				else
				{
					$this->showPasswordRecoverPage();
				}
			}
			elseif (\App()->Request['action'] == "change_password")
			{
				if ($adminManager->getAdminVerificationKey(\App()->Request['username']) != \App()->Request['verification_key'])
				{
					\App()->ErrorMessages->addMessage("WRONG_VERIFICATION_KEY");
					$this->showChangePasswordForm(false);
				}
				elseif (!is_null(\App()->Request['password']) &&
					$adminManager->changeAdminPassword(\App()->Request['username'], \App()->Request['password'], \App()->Request['confirm_password']))
				{
					\App()->SuccessMessages->addMessage("ADMIN_PASSWORD_CHANGED");
					$this->showChangePasswordForm(false);
				}
				else
				{
					$this->showChangePasswordForm();
				}
			}
			else
			{
				throw new \lib\Http\UnauthorizedException($this->getAdminAuthPage());
			}
			exit;
		}
	}

	private function getBanPage()
	{
		$template_processor = \App()->getTemplateProcessor();
		\App()->ErrorMessages->addMessage("IP_BANNED", array('ip' => \modules\main\lib\LoginFailures::getInstance()->getIp()));
		\App()->ErrorMessages->addMessage("BAN_REASON", array('limit' => \modules\main\lib\LoginFailures::getInstance()->getLimit()));
		\App()->ErrorMessages->addMessage("BAN_EXPIRES", array('banExpires' => \modules\main\lib\LoginFailures::getInstance()->getBanExpiresTime()));
		return $template_processor->fetch('auth_banned.tpl');
	}

	private function getAdminAuthPage()
	{
		if (\modules\main\lib\LoginFailures::getInstance()->isBanned())
		{
			throw new \lib\Http\UnauthorizedException($this->getBanPage());
			exit();
		}

		$template_processor = \App()->getTemplateProcessor();
		return $template_processor->fetch('auth.tpl');
	}

	private function showPasswordRecoverPage($actionComplete = false)
	{
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign('actionComplete', $actionComplete);
		$template_processor->display('password_recover.tpl');
	}

	private function showChangePasswordForm($displayForm = true)
	{
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign('username', \App()->Request['username']);
		$template_processor->assign('verification_key', \App()->Request['verification_key']);
		$template_processor->assign('displayForm', $displayForm);
		$template_processor->display('change_password.tpl');
	}
}
