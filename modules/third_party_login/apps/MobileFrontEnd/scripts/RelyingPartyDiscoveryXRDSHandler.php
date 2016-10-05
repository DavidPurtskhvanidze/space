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

class RelyingPartyDiscoveryXRDSHandler extends \apps\MobileFrontEnd\ContentHandlerBase
{
	protected $displayName = 'OpenID or OAuth Login';
	protected $moduleName = 'third_party_login';
	protected $functionName = 'relying_party_discovery_xrds';
	protected $rawOutput = true;

	public function respond()
	{
		\App()->ThirdPartyAuthManager->displayXRDS();
	}
}
