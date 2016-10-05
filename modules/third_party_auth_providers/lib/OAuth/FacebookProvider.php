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

class FacebookProvider extends AbstractOAuthProvider
{
	const AUTHORIZE_URL = 'https://www.facebook.com/dialog/oauth';
	const ACCESS_TOKEN_URL = 'https://graph.facebook.com/oauth/access_token';
	const GRAPH_URL = "https://graph.facebook.com/me";
    const FIELDS = 'first_name, last_name, email, name';
    private $userInfo = null;
    private $parsedAccessToken = null;

	function redirectToProvider($openIdUrl)
	{
		$auth_url = self::AUTHORIZE_URL . "?client_id=" . $this->consumerKey . "&redirect_uri=" . urlencode($this->returnToUrl . $this->queryString) . "&scope=publish_actions,email,public_profile";
		throw new \lib\Http\RedirectException($auth_url);
	}

    protected function normalizeUserInfo($userInfo)
    {
        $result = array(
            'id' => $userInfo['id'],
            'FirstName' => $userInfo['first_name'],
            'LastName' => $userInfo['last_name'],
            );

        if (!empty($userInfo['email']))
            $result['email'] =  $userInfo['email'];

        return $result;
    }

    private function defineUserInfoFromResponse()
    {
        if (is_null($this->userInfo))
        {
            $accessToken = $this->getAccessToken();
            $userInfo = $this->getUserInfoFromAccessToken($accessToken);
            $this->userInfo = $this->normalizeUserInfo($userInfo);
        }
    }

    function getUserInfoFromResponse()
    {
        $this->defineUserInfoFromResponse();
        return $this->userInfo;

    }

	function getAccessDataFromResponse()
	{
		$accessToken = $this->getAccessToken();
        $userId = $this->getUserIdFromAccessToken();
        $accessToken = $this->getLongLiveAccessToken($accessToken['access_token']);

		$accessData = array(
			"access_token" => $accessToken['access_token'],
			"user_id" => $userId,
		);

		return $accessData;
	}

	function getAccessToken($accessTokenUrl, $authorized_request_token)
	{
        if (! is_null($this->parsedAccessToken)) return $this->parsedAccessToken;

		$token_url = self::ACCESS_TOKEN_URL;
		$vars = array(
			"client_id" => $this->consumerKey,
			"redirect_uri" => $this->returnToUrl . $this->queryString,
			"client_secret" => $this->consumerSecret,
			"code" => $_REQUEST['code'],
		);
		$access_token = $this->curl_request($token_url, $vars, "GET");

		if (strpos($access_token, "access_token=") === false)
		{
			$error = json_decode($access_token, true);

			if (array_key_exists('error', $error) && $error['error']['message'] == "Error validating client secret.")
			{
				throw new \modules\third_party_auth_providers\lib\Exception("INVALID_KEY_OR_SECRET");
			}
			else
			{
				throw new \modules\third_party_auth_providers\lib\Exception("AUTH_FAILED");
			}
		}

		parse_str($access_token, $this->parsedAccessToken);

		return $this->parsedAccessToken;
	}

    private function getLongLiveAccessToken($shortTimeAccessToken)
    {
        $token_url = self::ACCESS_TOKEN_URL;
        $vars = array(
            "client_id" => $this->consumerKey,
            "grant_type" => 'fb_exchange_token',
            "client_secret" => $this->consumerSecret,
            "fb_exchange_token" => $shortTimeAccessToken,
        );
        $access_token = $this->curl_request($token_url, $vars, "GET");

        if (strpos($access_token, "access_token=") === false)
        {
            $error = json_decode($access_token, true);

            if (array_key_exists('error', $error) && $error['error']['message'] == "Error validating client secret.")
            {
                throw new \modules\third_party_auth_providers\lib\Exception("INVALID_KEY_OR_SECRET");
            }
            else
            {
                throw new \modules\third_party_auth_providers\lib\Exception("AUTH_FAILED");
            }
        }
        parse_str($access_token, $parsedAccessToken);
        return $parsedAccessToken;
    }

	function getUserIdFromAccessToken()
	{
        $this->defineUserInfoFromResponse();
		return $this->userInfo['id'];
	}

    function getUserInfoFromAccessToken($accessToken)
    {
        $data = $accessToken;
        $data['fields'] = self::FIELDS;
        $user = json_decode($this->curl_request(self::GRAPH_URL, $data, "GET"), true);
        if (!is_array($user) || !array_key_exists('id',$user))
        {
            throw new \modules\third_party_auth_providers\lib\Exception("AUTH_FAILED");
        }
        return $user;
    }

	function postMessage($message, $accessData)
	{
		$vars = array(
			"access_token" => $accessData['access_token'],
			"message" => $message,
		);
		$this->curl_request("https://graph.facebook.com/{$accessData['user_id']}/feed", $vars);
	}
}
