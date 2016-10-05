<?php
/**
 *
 *    Module: listing_repost v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_repost-7.5.0-1
 *    Tag: tags/7.5.0-1@19795, 2016-06-17 13:19:57
 *
 *    This file is part of the 'listing_repost' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_repost\apps\FrontEnd\scripts;

class ListingRepostSettingsHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Listing repost settings';
	protected $moduleName = 'listing_repost';
	protected $functionName = 'listing_repost_settings';

	private $error = null;

	public function respond()
	{
		$requestReflector = \App()->ObjectMother->createRequestReflector();
		$currentUserSid = \App()->UserManager->getCurrentUserSid();

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
		catch (\modules\third_party_auth_providers\lib\Exception $e)
		{
			$this->error = $e->getMessage();
		}

		$redirectUrl = \App()->PageRoute->getPagePathById('user_profile');
		if ($this->error) $redirectUrl .= "?error={$this->error}";
		throw new \lib\Http\RedirectException($redirectUrl);
	}
}
