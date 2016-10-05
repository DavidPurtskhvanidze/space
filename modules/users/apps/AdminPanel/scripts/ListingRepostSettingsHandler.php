<?php
/**
 *
 *    Module: users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: users-7.5.0-1
 *    Tag: tags/7.5.0-1@19887, 2016-06-17 13:25:03
 *
 *    This file is part of the 'users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\users\apps\AdminPanel\scripts;

class ListingRepostSettingsHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'users';
	protected $functionName = 'listing_repost_settings';
	protected $displayName = 'Listing repost settings';

	private $error = null;

	public function respond()
	{
		$requestReflector = \App()->ObjectMother->createRequestReflector();
		$currentUserSid = 0; //for admin userSid is 0

		try
		{
			if ($requestReflector->get('action') == 'disable')
			{
				\App()->UserSocialNetworkAccessDataManager->disableListingRepostStatusForUser($currentUserSid, $_REQUEST['provider']);
			}
            elseif($requestReflector->get('action') == 'refresh')
            {
                \App()->UserSocialNetworkAccessDataManager->deleteAccessDataForUser($currentUserSid, $_REQUEST['provider']);
                throw new \lib\Http\RedirectException(
                    \App()->PageRoute->getSystemPagePath($this->moduleName, $this->functionName)
                    . '?provider=' . $_REQUEST['provider'] . '&action=enable');
            }
			elseif (isset($_REQUEST['provider']))
			{
				if (!is_null(\App()->UserSocialNetworkAccessDataManager->getAccessDataForUser($currentUserSid, $_REQUEST['provider'])))
				{
					\App()->UserSocialNetworkAccessDataManager->enableListingRepostStatusForUser($currentUserSid, $_REQUEST['provider']);
				}
				else
				{
					\App()->ThirdPartyAuthManager->redirectToProvider($_REQUEST['provider'], "?provider_id={$_REQUEST['provider']}");
					return;
				}
			}
			else
			{
				$providerId = $requestReflector->get('provider_id');
				$accessData = \App()->ThirdPartyAuthManager->getAccessDataFromResponse($providerId, "?provider_id={$providerId}");
				$data = array
				(
					'user_sid' => $currentUserSid,
					'provider_id' => $providerId,
					'enabled' => true,
					'access_token' => $accessData,
				);
				$userSocialNetworkAccessData = \App()->UserSocialNetworkAccessDataManager->createUserSocialNetworkAccessData($data);
				\App()->UserSocialNetworkAccessDataManager->saveObject($userSocialNetworkAccessData);
			}
		}
		catch (Exception $e)
		{
			$this->error = $e->getMessage();
		}

		if(isset($_REQUEST['step']))
		{
			$redirectUrl = \App()->PageRoute->getSystemPagePath('miscellaneous', 'configuration_wizard') . '?repeat=1&current_step=' . $_REQUEST['step'];
			if ($this->error) $redirectUrl .= "&error={$this->error}";
		}
		else
		{
			$redirectUrl = \App()->PageRoute->getPagePathById("settings");
			if ($this->error) $redirectUrl .= "?error={$this->error}";
		}
		throw new \lib\Http\RedirectException($redirectUrl);
	}
}
