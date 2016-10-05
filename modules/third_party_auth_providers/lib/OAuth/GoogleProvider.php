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

class GoogleProvider extends AbstractOAuthProvider
{
	const AUTHORIZE_URL = 'https://accounts.google.com/o/oauth2/auth';
	const ACCESS_TOKEN_URL = 'https://accounts.google.com/o/oauth2/token';
	const GRAPH_URL = "https://www.googleapis.com/oauth2/v1/tokeninfo";
    private $userInfo = null;
    private $accessToken = null;

	function redirectToProvider($openIdUrl)
	{
		$auth_url = self::AUTHORIZE_URL
			. "?client_id=" . $this->consumerKey
			. "&redirect_uri=" . urlencode($this->returnToUrl)
			. "&response_type=code"
			. "&scope=openid email"
			. "&access_type=offline"
			. "&state=" . urlencode($this->queryString);
		throw new \lib\Http\RedirectException($auth_url);
	}

	function getUserInfoFromResponse()
	{
        $this->defineUserInfoFromResponse();
		return $this->userInfo;
	}

	function getAccessToken($accessTokenUrl, $authorized_request_token)
	{
        if (!is_null($this->accessToken)) return $this->accessToken;
		$vars = array(
			"code" => $_REQUEST['code'],
			"client_id" => $this->consumerKey,
			"client_secret" => $this->consumerSecret,
			"redirect_uri" => $this->returnToUrl,
			"grant_type" => "authorization_code",
		);
		$access_token = $this->curl_request($accessTokenUrl, $vars, "POST");
		if (strpos($access_token, "access_token") === false)
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
        $this->accessToken = json_decode($access_token, true);
        return $this->accessToken;
	}

    private function defineUserInfoFromResponse()
    {
        if (is_null($this->userInfo))
        {
            $accessToken = $this->getAccessToken(self::ACCESS_TOKEN_URL, $_REQUEST);
            $user = json_decode($this->curl_request(self::GRAPH_URL, $accessToken, "POST"), true);
            if (!is_array($user) || !array_key_exists('user_id',$user))
            {
                throw new \modules\third_party_auth_providers\lib\Exception("AUTH_FAILED");
            }
            $this->userInfo = $this->normalizeUserInfo($user);
        }
    }

    protected function normalizeUserInfo($userInfo)
    {
        $result = array(
            'id' => $userInfo['user_id'],
        );

        if (!empty($userInfo['email']))
            $result['email'] = $userInfo['email'];

        return $result;
    }

}
