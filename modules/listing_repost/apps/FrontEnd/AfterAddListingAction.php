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


namespace modules\listing_repost\apps\FrontEnd;
 
class AfterAddListingAction implements \modules\classifieds\apps\FrontEnd\IAfterAddListingAction
{
	private $listing;

	public function setListing($listing)
	{
		$this->listing = $listing;
	}

	public function perform()
	{
		$listingRepostStatusManager = new \modules\listing_repost\ListingRepostStatusManager();
		$currentUserSid = $this->listing->getUserSID();

		$listingTwitterRepostStatus = !isset($_REQUEST['doNotRepostToTwitter']) && \App()->UserSocialNetworkAccessDataManager->getListingRepostStatusForUser($currentUserSid, "Twitter");

		$listingRepostStatusManager->setTwitterRepostStatus($this->listing->getSID(), $listingTwitterRepostStatus);
	}
}
