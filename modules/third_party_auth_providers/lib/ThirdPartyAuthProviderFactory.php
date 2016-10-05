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


namespace modules\third_party_auth_providers\lib;

class ThirdPartyAuthProviderFactory
{
	function getProvider($providerId)
	{
		// Realm URL must not cause any redirects. That is why we set url with trailing slash.
		// Otherwise, system will redirect to the page with trailing slash and the XRDS discovery will fail.
		$trustRootUrl = \App()->SystemSettings['SiteUrl'] . '/';
		$returnToUrl = \App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI();

		switch ($providerId)
		{
			case 'Google':
				$provider = new OAuth\GoogleProvider();
				$provider->setConsumerKey(\App()->SettingsFromDB->getSettingByName('google_client_id'));
				$provider->setConsumerSecret(\App()->SettingsFromDB->getSettingByName('google_client_secret'));
				break;
			case 'MySpace':
				$provider = new OpenId\MySpaceProvider();
				break;
			case 'Facebook':
				$provider = new OAuth\FacebookProvider();
				$provider->setConsumerKey(\App()->SettingsFromDB->getSettingByName('facebook_app_id'));
				$provider->setConsumerSecret(\App()->SettingsFromDB->getSettingByName('facebook_app_secret'));
				break;
			case 'Twitter':
				$provider = new OAuth\TwitterProvider();
				$provider->setConsumerKey(\App()->SettingsFromDB->getSettingByName('twitter_consumer_key'));
				$provider->setConsumerSecret(\App()->SettingsFromDB->getSettingByName('twitter_consumer_secret'));
				break;
			case 'Yahoo':
				$provider = new OpenId\YahooProvider();
				break;
			default:
				$provider = new OpenId\OpenIdProvider();
		}
		$provider->setTempDir(\App()->FileSystem->getWritableCacheDir("ThirdPartyAuthProvider"));
		$provider->setTrustRootUrl($trustRootUrl);
		$provider->setReturnToUrl($returnToUrl);
		return $provider;
	}
}
