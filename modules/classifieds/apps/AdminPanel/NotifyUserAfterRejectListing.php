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

class NotifyUserAfterRejectListing implements \modules\classifieds\apps\AdminPanel\IAfterRejectListingAction
{
	private $listingSid;

	public function setListingSid($listingSid)
	{
		$this->listingSid = $listingSid;
	}

	public function perform()
	{
		$listing = \App()->ListingManager->getObjectBySID($this->listingSid);
		$emailAddress = is_null($listing->getPropertyValue('user')) ? \App()->SettingsFromDB->getSettingByName('notification_email') : $listing->getPropertyValue('user')->getPropertyValue('email');
		$siteUrl = \App()->SystemSettings->getSettingForApp('FrontEnd', 'SiteUrl');

		return \App()->EmailService->send($emailAddress, 'email_template:listing_rejected', array(
			'listing' => \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($listing),
			'user_site_url' => $siteUrl,
		));
	}
}
