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

class ListingComparisonTable
{
	var $dataSource;
	
	function addListing($listingId)
	{
        $listings = $this->getListings();
        array_push($listings, $listingId);
        $listings = array_unique($listings);
		$this->setListings($listings);
	}
	
	function removeListing($listingId)
	{
		$listings = $this->getListings();
		if (in_array($listingId, $listings))
		{
			$k = array_search($listingId, $listings);
			unset($listings[$k]);
			$this->setListings($listings);
		}
	}
	
	function clear()
	{
		$this->setListings(array());
	}
	
	function getListings()
	{
		if (null === ($listings = $this->dataSource->getValue('listings')))
		{
			$listings = array();
		}
		$resaveListings = false;
		foreach(array_keys($listings) as $key)
		{
			if (!\App()->ListingManager->doesListingExist($listings[$key]))
			{
				unset($listings[$key]);
				$resaveListings = true;
			}
		}
		if ($resaveListings)
			$this->setListings(is_array($listings) ? $listings : array());
		return $listings;
	}
	
	function setListings($listings)
	{
		$this->dataSource->setValue('listings', $listings);
	}
	
	function setDatasource($dataSource)
	{
		$this->dataSource = $dataSource;
	}

	public function getListingCount()
	{
		return count($this->getListings());
	}
}

?>
