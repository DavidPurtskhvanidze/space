<?php
/**
 *
 *    Module: third_party_auth_providers v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: third_party_auth_providers-7.5.0-1
 *    Tag: tags/7.5.0-1@19885, 2016-06-17 13:24:55
 *
 *    This file is part of the 'third_party_auth_providers' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\third_party_auth_providers\lib\OpenId;

class YahooProvider extends AbstractOpenIdProvider
{
	const OPEN_ID_URL = "http://www.yahoo.com/";

	function redirectToProvider($openIdUrl)
	{
		parent::redirectToProvider(self::OPEN_ID_URL);
	}
}
