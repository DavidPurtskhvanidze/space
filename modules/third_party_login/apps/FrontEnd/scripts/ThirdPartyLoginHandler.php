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


namespace modules\third_party_login\apps\FrontEnd\scripts;

class ThirdPartyLoginHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'OpenID or OAuth Login';
	protected $moduleName = 'third_party_login';
	protected $functionName = 'openid_oauth_login';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();

		if (\App()->UserManager->isUserLoggedIn())
		{
			$templateProcessor->display("already_logged_in.tpl");
		}
		else
		{
			try
			{
				if (isset($_REQUEST['provider']))
				{
					$queryString = $this->getQueryString($_REQUEST['provider']);
					if (isset($_REQUEST['openIdUrl']))
					{
						\App()->ThirdPartyAuthManager->redirectToProvider($_REQUEST['provider'], $queryString, $_REQUEST['openIdUrl']);
					}
					else
					{
						\App()->ThirdPartyAuthManager->redirectToProvider($_REQUEST['provider'], $queryString);
					}
				}
				else if (isset($_REQUEST['state']))
				{
					throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('user_openid_oauth_login') . $_REQUEST['state'] . "&code=" . $_REQUEST['code']);
				}
				else
				{
					$provider_id = isset($_REQUEST['provider_id']) ? $_REQUEST['provider_id'] : null;
					$queryString = $this->getQueryString($provider_id);
                    $userInfo = \App()->ThirdPartyAuthManager->getUserInfoFromResponse($provider_id, $queryString);

                    $identity = $provider_id . $userInfo['id'];

                    if (!$user = \App()->ThirdPartyAuthManager->getUserByIdentity($identity))
					{
						$user = \App()->ThirdPartyAuthManager->registerUserByIdentity($identity, $userInfo);
					}
					\App()->ThirdPartyAuthManager->authUserByIdentity($user);


					$redirectAfterLoginAction = \App()->ObjectMother->createRedirectAfterLoginAction(\App()->Request['HTTP_REFERER'], \App()->Request['QUERY_STRING'], \App()->Request['current_page_uri']);
					$redirectAfterLoginAction->perform();
				}
			}
			catch (\modules\third_party_auth_providers\lib\Exception $e)
			{
				$error = $e->getMessage();
				throw new \lib\Http\RedirectException(\App()->PageRoute->getPageURLById('user_login') . "?errorMessage={$error}");
			}
		}
	}

	private function getQueryString($provider_id)
	{
		$queryString = "?provider_id={$provider_id}";
		if (!empty($_REQUEST['HTTP_REFERER'])) $queryString .= "&HTTP_REFERER=" . urlencode($_REQUEST['HTTP_REFERER']);
		if (!empty($_REQUEST['QUERY_STRING'])) $queryString .= "&QUERY_STRING=" . urlencode($_REQUEST['QUERY_STRING']);
		if (!empty($_REQUEST['current_page_uri'])) $queryString .= "&current_page_uri=" . urlencode($_REQUEST['current_page_uri']);
		return $queryString;
	}
}
