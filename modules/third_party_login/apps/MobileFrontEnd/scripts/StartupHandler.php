<?php
/**
 *
 *    Module: third_party_login v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: third_party_login-7.3.0-1
 *    Tag: tags/7.3.0-1@18640, 2015-08-24 13:43:11
 *
 *    This file is part of the 'third_party_login' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\third_party_login\apps\MobileFrontEnd\scripts;

class StartupHandler extends \apps\MobileFrontEnd\ContentHandlerBase implements \core\IStartupScript
{
	protected $displayName = 'Startup';
	protected $moduleName = 'third_party_login';
	protected $functionName = 'startup';

	public function respond()
	{
		\App()->ThirdPartyAuthManager->defineXRDSMetaTag();
	}
}
