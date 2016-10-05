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


namespace modules\classifieds\lib\Listing;

class RestoreListingsOnSubscriptionAction
{
	/**
	 * @var \modules\users\lib\User\User
	 */
	private $user;
	
	function setUser($user)
	{
		$this->user = $user;
	}
	function perform()
	{
		$listingsToRestore = $this->getListingsToRestore();
		array_walk($listingsToRestore, array(\App()->ListingManager, 'userActivateListingBySID'));
	}
	function getListingsToRestore()
	{
		return \App()->ListingManager->getInactiveNotExpiredListingsIdByUserSid($this->user->getSid());
	}
}
