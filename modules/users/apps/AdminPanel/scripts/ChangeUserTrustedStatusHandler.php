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

class ChangeUserTrustedStatusHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'users';
	protected $functionName = 'change_user_trusted_status';

	public function respond()
	{
		if (\App()->Request['action'] === 'make_user_untrusted')
		{
			\App()->UserManager->makeUsersUntrusted(\App()->Request['user_sids']);
			$messageCode = 'USER_MADE_UNTRUSTED';
		}
		elseif (\App()->Request['action'] === 'make_user_trusted')
		{
			\App()->UserManager->makeUsersTrusted(\App()->Request['user_sids']);
			$messageCode = 'USER_MADE_TRUSTED';
		}
		$usernames = array_map(array(\App()->UserManager, 'getUserNameByUserSID'), \App()->Request['user_sids']);
		\App()->SuccessMessages->addMessage($messageCode, array('usernames' => $usernames));
		throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Request['returnBackUri']);
	}
}
