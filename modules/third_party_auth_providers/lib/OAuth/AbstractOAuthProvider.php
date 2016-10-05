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

require_once "OAuth/OAuth.php";

class AbstractOAuthProvider implements \modules\third_party_auth_providers\lib\ThirdPartyAuthProviderInterface
{
	protected $consumerKey;
	protected $consumerSecret;
	protected $returnToUrl;
	protected $queryString;

	function setConsumerKey($consumerKey)
	{
		$this->consumerKey = $consumerKey;
	}

    protected function normalizeUserInfo($userInfo)
    {

    }

	function setConsumerSecret($consumerSecret)
	{
		$this->consumerSecret = $consumerSecret;
	}

	function setReturnToUrl($returnToUrl)
	{
		$this->returnToUrl = $returnToUrl;
	}

	function setQueryString($queryString)
	{
		$this->queryString = $queryString;
	}

	function setTrustRootUrl($trustRootUrl)
	{
	}

	function setTempDir($tempDir)
	{
	}

	function redirectToProvider($openIdUrl)
	{
	}

	function getUserInfoFromResponse()
	{
	}

	function getAccessDataFromResponse()
	{
	}

	protected function getRequestToken($requestTokenUrl)
	{
		$sig_method = new \OAuthSignatureMethod_HMAC_SHA1();
		$requestTokenConsumer = new \OAuthConsumer($this->consumerKey, $this->consumerSecret, NULL);

		$req_req = \OAuthRequest::from_consumer_and_token($requestTokenConsumer, NULL, "GET", $requestTokenUrl, array('oauth_callback' => $this->returnToUrl . $this->queryString));
		$req_req->sign_request($sig_method, $requestTokenConsumer, NULL);

		$response = $this->curl_request($req_req->get_normalized_http_url(), $req_req->get_parameters(), "GET");
		parse_str($response, $response);

		if (!is_array($response) || (!array_key_exists('oauth_token', $response) || !array_key_exists('oauth_token_secret', $response))) {
			throw new \modules\third_party_auth_providers\lib\Exception("INVALID_KEY_OR_SECRET");
		}
		\App()->Session->getContainer("ThirdPartyAuth")->setValue("oauth_token_secret", $response["oauth_token_secret"]);

		return $response;
	}

	function getAccessToken($accessTokenUrl, $authorized_request_token)
	{
		if (!is_array($authorized_request_token) || (!array_key_exists('oauth_token', $authorized_request_token) || !array_key_exists('oauth_verifier', $authorized_request_token))) {
			throw new \modules\third_party_auth_providers\lib\Exception("AUTH_FAILED");
		}

		$sig_method = new \OAuthSignatureMethod_HMAC_SHA1();
		$accessTokenConsumer = new \OAuthConsumer($authorized_request_token['oauth_token'], \App()->Session->getContainer("ThirdPartyAuth")->getValue("oauth_token_secret"));

		$test_consumer = new \OAuthConsumer($this->consumerKey, $this->consumerSecret, NULL);

		$acc_req = \OAuthRequest::from_consumer_and_token($test_consumer, $accessTokenConsumer, "GET", $accessTokenUrl, array("oauth_verifier" => $authorized_request_token["oauth_verifier"]));
		$acc_req->sign_request($sig_method, $test_consumer, $accessTokenConsumer);

		$authorized_request_token = $this->curl_request($acc_req->get_normalized_http_url(), $acc_req->get_parameters(), "GET");
		parse_str($authorized_request_token, $authorized_request_token);

		if (!is_array($authorized_request_token) || !array_key_exists('user_id', $authorized_request_token)) {
			throw new \modules\third_party_auth_providers\lib\Exception("AUTH_FAILED");
		}

		return $authorized_request_token;
	}

	protected function curl_request($url, $vars = array(), $method="POST")
	{
		$requestVars = http_build_query($vars);

		$chanel = curl_init();
		if ($method == "POST")
		{
			curl_setopt($chanel, CURLOPT_URL, $url);
			curl_setopt($chanel, CURLOPT_POST, true);
			curl_setopt($chanel, CURLOPT_POSTFIELDS, $requestVars);
		}
		else
		{
			if (!empty($requestVars)) $requestVars = "?" . $requestVars;
			curl_setopt($chanel, CURLOPT_URL, $url . $requestVars);
		}
		curl_setopt($chanel, CURLOPT_HEADER, false);
		curl_setopt($chanel, CURLOPT_AUTOREFERER, true);
		curl_setopt($chanel, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($chanel, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($chanel, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($chanel, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)");
		$response = curl_exec($chanel);
		curl_close($chanel);

		return $response;
	}
}
