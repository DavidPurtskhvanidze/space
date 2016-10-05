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

define('Auth_OpenID_RAND_SOURCE', null);
require_once 'Auth/OpenID/FileStore.php';
require_once 'Auth/OpenID/Consumer.php';

class AbstractOpenIdProvider implements \modules\third_party_auth_providers\lib\ThirdPartyAuthProviderInterface
{
	private $trustRootUrl;
	private $returnToUrl;
	private $queryString;
	private $tempDir;

	function setTempDir($tempDir)
	{
		$this->tempDir = $tempDir;
	}

	function setTrustRootUrl($trustRootUrl)
	{
		$this->trustRootUrl = $trustRootUrl;
	}

	function setReturnToUrl($returnToUrl)
	{
		$this->returnToUrl = $returnToUrl;
	}

	function setQueryString($queryString)
	{
		$this->queryString = $queryString;
	}

	function redirectToProvider($openIdUrl)
	{
		$consumer = $this->getConsumer();
		$auth_request = $consumer->begin($openIdUrl);
		if (!$auth_request) {
			throw new \modules\third_party_auth_providers\lib\Exception("INCORRECT_OPEN_ID");
		}

		if ($auth_request->shouldSendRedirect()) {
			$redirect_url = $auth_request->redirectURL($this->trustRootUrl, $this->returnToUrl . $this->queryString);
			if (\Auth_OpenID::isFailure($redirect_url)) {
				throw new \modules\third_party_auth_providers\lib\Exception("CANNOT_REDIRECT_TO_SERVER");
			}
			else {
				header("Location: " . $redirect_url);
			}
		}
		else
		{
			$form_id = 'openid_message';
			$form_html = $auth_request->formMarkup($this->trustRootUrl, $this->returnToUrl . $this->queryString, false, array('id' => $form_id));
			$s = " style=\"display:none\" ";
			$form_html = str_replace("<form", "<form $s", $form_html);

			if (\Auth_OpenID::isFailure($form_html)) {
				throw new \modules\third_party_auth_providers\lib\Exception("CANNOT_REDIRECT_TO_SERVER");
			}
			else
			{
				$page_contents = array(
					"<html><body onload='document.getElementById(\"" . $form_id . "\").submit()'>",
					$form_html, "</body></html>");
				print implode("\n", $page_contents);
			}
		}
	}

	function getUserInfoFromResponse()
	{
		$consumer = $this->getConsumer();

		$return_to = $this->returnToUrl;
		$return_to .= "?" . $_SERVER['QUERY_STRING'];

		$response = $consumer->complete($return_to);

		if ($response->status == Auth_OpenID_CANCEL)
		{
			throw new \modules\third_party_auth_providers\lib\Exception("AUTH_FAILED");
		}
		else if ($response->status == Auth_OpenID_FAILURE)
		{
			throw new \modules\third_party_auth_providers\lib\Exception("AUTH_FAILED");
		}
		else if ($response->status == Auth_OpenID_SUCCESS)
		{
			$openid = $response->getDisplayIdentifier();
			$esc_identity = htmlspecialchars($openid, ENT_QUOTES);
			return array('id' => $esc_identity);
		}
	}

	private function getConsumer()
	{
		$store_path = $this->tempDir;

		if (!file_exists($store_path) && !mkdir($store_path)) {
			throw new \modules\third_party_auth_providers\lib\Exception("CANNOT_CREATE_TEMP_DIR");
		}

		$store = new \Auth_OpenID_FileStore($store_path);
		return new \Auth_OpenID_Consumer($store);
	}
}
