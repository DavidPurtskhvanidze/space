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


namespace modules\classifieds\apps\FrontEnd;

use modules\miscellaneous\apps\AdminPanel\IAdminNotification;

class NotifyAdminAfterAddListing implements IAfterAddListingAction, IAdminNotification
{
	private $listing;

	public function setListing($listing)
	{
		$this->listing = $listing;
	}

	public function perform()
	{
		if (\App()->SettingsFromDB->getSettingByName($this->getId()))
		{
			$userInfo = \App()->UserManager->getUserInfoBySID($this->listing->getUserSID());
			return \App()->EmailService->sendToAdmin('email_template:admin_add_listing_email', array('listing' => \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($this->listing), 'user' => $userInfo));
		}
		return false;
	}

	public function getId()
	{
		return 'notify_on_listing_added';
	}

	public function getCaption()
	{
		return 'Notify when User Added a Listing';
	}
}
