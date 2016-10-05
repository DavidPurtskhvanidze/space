<?php
/**
 *
 *    Module: import_listings v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: import_listings-7.5.0-1
 *    Tag: tags/7.5.0-1@19787, 2016-06-17 13:19:36
 *
 *    This file is part of the 'import_listings' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\import_listings\lib;

class ImportedListing
{
	/**
	 * @var \modules\classifieds\lib\Listing\Listing
	 */
	var $listing;
	var $listingData;
	
	function setListing($listing)
	{
		$this->listing = $listing;
	}
	function setListingData($listingData)
	{
		$this->listingData = $listingData;
	}
	function getListing()
	{
		return $this->listing;
	}
	function getListingData()
	{
		return $this->listingData;
	}
}
