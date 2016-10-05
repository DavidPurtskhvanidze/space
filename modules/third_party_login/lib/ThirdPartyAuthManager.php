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


namespace modules\third_party_login\lib;

class ThirdPartyAuthManager implements \core\IService
{
	private $factory;
	private $dbManager;

	public function init()
	{
		$this->factory = new \modules\third_party_auth_providers\lib\ThirdPartyAuthProviderFactory();
		$this->dbManager = new ThirdPartyAuthDBManager();
	}

	function redirectToProvider($providerId, $queryString = null, $openIdUrl = null)
	{
		$provider = $this->factory->getProvider($providerId);
		$provider->setQueryString($queryString);
		$provider->redirectToProvider($openIdUrl);
	}

	function getUserIdentityFromResponse($providerId, $queryString)
	{
		$provider = $this->factory->getProvider($providerId);
		$provider->setQueryString($queryString);
		return $provider->getUserIdentityFromResponse();
	}

    function getUserInfoFromResponse($providerId, $queryString)
    {
        $provider = $this->factory->getProvider($providerId);
        $provider->setQueryString($queryString);
        return $provider->getUserInfoFromResponse();
    }

	function getUserByIdentity($identity)
	{
		$user_info = $this->dbManager->getUserInfoByIdentity($identity);
		return $user_info ? \App()->UserManager->createUser($user_info, $user_info['user_group_sid']) : null;
	}

	function registerUserByIdentity($identity, $userInfo)
	{
        $userInfo['username'] = $this->generateUniqueNameForThirdPartyUser();
        $userInfo['user_group_sid'] = \App()->SettingsFromDB->getSettingByName('third_party_auth_user_group_sid');
        $userInfo['active'] = true;

        if (!empty($userInfo['email']))
        {
            if (!filter_var($userInfo['email']))
            {
                unset($userInfo['email']);
            }

            $emailExits = (bool) \App()->UserManager->getUserInfoByEmail($userInfo['email']);
            if($emailExits)
                throw new \modules\third_party_auth_providers\lib\Exception('EMAIL_EXITS');

        }

		$user = \App()->UserManager->createUser($userInfo, $userInfo['user_group_sid']);

		$user->addProperty(
			array(
				'id'		=> 'third_party_id',
				'type'		=> 'unique_string',
				'table_name' => 'users_users',
				'is_system'=> true,
				'value' => $identity,
				));
		\App()->UserManager->saveUser($user);

		return $user;
	}

	function authUserByIdentity($user)
	{
		$user_info = \App()->UserManager->getUserInfoBySID($user->getSID());
		if (!$user_info['active'])
		{
			throw new \modules\third_party_auth_providers\lib\Exception('USER_NOT_ACTIVE');
		}
		\App()->UserManager->setSessionForUser($user_info);
	}

	function getAccessDataFromResponse($providerId, $queryString)
	{
		$provider = $this->factory->getProvider($providerId);
		$provider->setQueryString($queryString);
		return $provider->getAccessDataFromResponse();
	}

	private function generateUniqueNameForThirdPartyUser($charCount = 8)
	{
		$wordChars = array(0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','q','k','l','m','n','o','p','r','s','t','w','y','u','x','z');
		$range = count($wordChars);

		$randomWord = '';
		while ($charCount > 0)
		{
			$I = rand(0, $range-1);
			$randomWord .= $wordChars[$I];
			--$charCount;
		}

		$generated_username = 'ext_' . $randomWord;

		return \App()->UserManager->getUserInfoByUserName($generated_username) ? $this->generateUniqueNameForThirdPartyUser() : $generated_username;
	}

	public function getGoogleSetupStatus()
	{
		return \App()->SettingsFromDB->getSettingByName('google_client_id') != "" && \App()->SettingsFromDB->getSettingByName('google_client_secret') != "";
	}
	public function getFacebookSetupStatus()
	{
		return \App()->SettingsFromDB->getSettingByName('facebook_app_id') != "" && \App()->SettingsFromDB->getSettingByName('facebook_app_secret') != "";
	}
	public function getTwitterSetupStatus()
	{
		return \App()->SettingsFromDB->getSettingByName('twitter_consumer_key') != "" && \App()->SettingsFromDB->getSettingByName('twitter_consumer_secret') != "";
	}

	/**
	 * Sets meta tag for Discovering OpenID Relying Parties.
	 * See http://openid.net/specs/openid-authentication-2_0.html#rp_discovery
	 */
	public function defineXRDSMetaTag()
	{
		// Adds XRDS meta code inside the HEAD tags on Realm page. In our case Realm page is the main page

		if (\App()->Navigator->getURI() != '/') return;

		$metaTags = \App()->GlobalTemplateVariable->getMetaTags();
		if (!empty($metaTags)) $metaTags .= "\n";

		$url = \App()->PageRoute->getSystemPageURL('third_party_login', 'relying_party_discovery_xrds');
		$metaTags .=  sprintf('<meta http-equiv="X-XRDS-Location" content="%s"/>', $url);

		\App()->GlobalTemplateVariable->setMetaTags($metaTags);
	}

	public function displayXRDS()
	{
		header('Content-Type: application/xrds+xml');
		$xml = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<xrds:XRDS
    xmlns:xrds="xri://$xrds"
    xmlns:openid="http://openid.net/xmlns/1.0"
    xmlns="xri://$xrd*($v*2.0)">
    <XRD>
        <Service priority="1">
            <Type>http://specs.openid.net/auth/2.0/return_to</Type>
            <URI>%return_to%</URI>
        </Service>
    </XRD>
</xrds:XRDS>
EOT;
		echo str_replace('%return_to%', \App()->PageRoute->getPagePathById('user_openid_oauth_login'), $xml);
	}
}
