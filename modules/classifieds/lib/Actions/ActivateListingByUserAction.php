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


namespace modules\classifieds\lib\Actions;

class ActivateListingByUserAction
{
	/**
	 * @var \modules\classifieds\lib\Listing\Listing
	 */
	private $listing;

	public function perform()
	{
		\App()->ListingManager->userActivateListing($this->listing);
	}

	public function setListing($listing)
	{
		$this->listing = $listing;
	}
}
