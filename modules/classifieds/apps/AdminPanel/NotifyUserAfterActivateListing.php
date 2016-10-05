<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\apps\AdminPanel;

class NotifyUserAfterActivateListing extends \modules\users\apps\FrontEnd\AbstractUserNotification implements \modules\classifieds\apps\AdminPanel\IAfterActivateListingAction
{
	private $listingSid;

	public function setListingSid($listingSid)
	{
		$this->listingSid = $listingSid;
	}

	public function perform()
	{
		$userSid = \App()->ListingManager->getUserSIDByListingSID($this->listingSid);
		if ($this->getValue($userSid))
		{
			$userInfo = \App()->UserManager->getUserInfoBySID($userSid);
			$listing = \App()->ListingManager->getObjectBySID($this->listingSid);
			$listingToArrayAdapter = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($listing);

			$siteUrl = \App()->SystemSettings->getSettingForApp('FrontEnd', 'SiteUrl');

			return \App()->EmailService->send($userInfo['email'], 'email_template:listing_activation',array(
				'listing_sid' => $this->listingSid,
				'user_site_url' => $siteUrl,
				'user' => $userInfo,
				'listing' => $listingToArrayAdapter
			));
		}
		return false;
	}

	public function getId()
	{
		return 'listing_activation';
	}

	public function getCaption()
	{
		return 'Notify on Listing Activation';
	}
}
