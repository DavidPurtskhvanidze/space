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


namespace modules\third_party_auth_providers\lib\OAuth;

require_once "OAuth/twitteroauth.php";

class TwitterProvider extends AbstractOAuthProvider
{
	const REQUEST_TOKEN_URL = 'https://api.twitter.com/oauth/request_token';
	const AUTHORIZE_URL = 'https://api.twitter.com/oauth/authorize';
	const ACCESS_TOKEN_URL = 'https://api.twitter.com/oauth/access_token';

	function redirectToProvider($openIdUrl)
	{
		$request_token = $this->getRequestToken(self::REQUEST_TOKEN_URL);
		$auth_url = self::AUTHORIZE_URL . "?oauth_token=" . $request_token['oauth_token'];
		throw new \lib\Http\RedirectException($auth_url);
	}

    protected function normalizeUserInfo($userInfo)
    {
        return array(
            'id' => $userInfo['user_id'],
        );
    }

	function getUserInfoFromResponse()
	{
		$info = $this->getAccessToken(self::ACCESS_TOKEN_URL, $_REQUEST);
        return $this->normalizeUserInfo($info);
	}

	function getAccessDataFromResponse()
	{
		$accessToken = $this->getAccessToken(self::ACCESS_TOKEN_URL, $_REQUEST);

		$accessData = array(
			"oauth_token" => $accessToken['oauth_token'],
			"oauth_token_secret" => $accessToken['oauth_token_secret'],
		);

		return $accessData;
	}

	function postMessage($message, $accessData)
	{
		$tweet = new \TwitterOAuth($this->consumerKey, $this->consumerSecret, $accessData['oauth_token'], $accessData['oauth_token_secret']);
		$tweet->post('statuses/update', array('status' => $message));
	}
}
