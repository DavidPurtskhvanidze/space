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


namespace modules\listing_repost;
 
class OnListingFirstActivationAction implements \modules\classifieds\IOnListingFirstActivationAction
{
	private $listingSid;

	public function setListingSid($listingSid)
	{
		$this->listingSid = $listingSid;
	}

	public function perform()
	{
		$listingRepostStatusManager = new ListingRepostStatusManager();
		$listingRepostActionFactory = new lib\ListingRepostActionFactory();
		$listing = \App()->ListingManager->getObjectBySID($this->listingSid);

		//repost for user account
		if ($listingRepostStatusManager->getTwitterRepostStatus($this->listingSid) && \App()->UserSocialNetworkAccessDataManager->getListingRepostStatusForUser($listing->getUserSID(), "Twitter"))
		{
			$listingRepostActionFactory->createPostListingMessageToTwitterAction($listing)->perform();
		}

		//repost for admin account
		if ($listing->getUserSID() == 0)
		{
			//from admin panel
			if ($listingRepostStatusManager->getFacebookRepostStatus($this->listingSid)
				&& \App()->UserSocialNetworkAccessDataManager->getListingRepostStatusForUser(0, "Facebook"))
			{
				$listingRepostActionFactory->createPostListingMessageToAdminFacebookAction($listing)->perform();
			}
			if ($listingRepostStatusManager->getTwitterRepostStatus($this->listingSid)
				&& \App()->UserSocialNetworkAccessDataManager->getListingRepostStatusForUser(0, "Twitter"))
			{
				$listingRepostActionFactory->createPostListingMessageToAdminTwitterAction($listing)->perform();
			}
		}
		else
		{
			//from front end
			if ( \App()->UserSocialNetworkAccessDataManager->getListingRepostStatusForUser(0, "Facebook"))
			{
				$listingRepostActionFactory->createPostListingMessageToAdminFacebookAction($listing)->perform();
			}
			if (\App()->UserSocialNetworkAccessDataManager->getListingRepostStatusForUser(0, "Twitter"))
			{
				$listingRepostActionFactory->createPostListingMessageToAdminTwitterAction($listing)->perform();
			}
		}
	}

}
