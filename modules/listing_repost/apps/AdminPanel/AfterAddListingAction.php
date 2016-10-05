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


namespace modules\listing_repost\apps\AdminPanel;
 
class AfterAddListingAction implements \modules\classifieds\apps\AdminPanel\IAfterAddListingAction
{
	private $listing;

	public function setListing($listing)
	{
		$this->listing = $listing;
	}

	public function perform()
	{
		$listingRepostStatusManager = new \modules\listing_repost\ListingRepostStatusManager();

		$listingFacebookRepostStatus = !isset($_REQUEST['doNotRepostToFacebook']) && \App()->UserSocialNetworkAccessDataManager->getListingRepostStatusForUser(0, "Facebook");
		$listingTwitterRepostStatus = !isset($_REQUEST['doNotRepostToTwitter']) && \App()->UserSocialNetworkAccessDataManager->getListingRepostStatusForUser(0, "Twitter");

		$listingRepostStatusManager->setFacebookRepostStatus($this->listing->getSID(), $listingFacebookRepostStatus);
		$listingRepostStatusManager->setTwitterRepostStatus($this->listing->getSID(), $listingTwitterRepostStatus);
	}
}
