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


namespace modules\users\apps\MobileFrontEnd\scripts;

// version 5 wrapper header

class LogoutHandler extends \apps\MobileFrontEnd\ContentHandlerBase
{
	protected $displayName = 'Login';
	protected $moduleName = 'users';
	protected $functionName = 'logout';

	public function respond()
	{
		
// end of version 5 wrapper header


\App()->UserManager->logout();
throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl']);

//  version 5 wrapper footer

	}
}
// end of version 5 wrapper footer
?>
