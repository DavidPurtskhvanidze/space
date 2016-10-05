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

class AdminLogoutHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'main';
	protected $functionName = 'admin_logout';
    protected $isPermissionRequired = false;

	public function respond()
	{
		$adminManager = new \modules\main\lib\AdminManager();

		if ($adminManager->admin_authed())
		{
			$adminManager->admin_log_out();
			throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl']);
		}
	}
}
