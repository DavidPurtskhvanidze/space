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


namespace modules\classifieds\lib;

class SavedListingsForUserNotLoggedIn
{
	function saveListing($listing_sid)
	{
		$saved_listings = $this->getSavedListings();
		array_push($saved_listings, $listing_sid);
		$saved_listings = array_unique($saved_listings);
		$this->save($saved_listings);
	}
	
	function getSavedListings()
	{
		$savedListingsCookie = \App()->Cookie->getCookie('SAVED_LISTINGS');
		$saved_listings = $savedListingsCookie ? explode(',', $savedListingsCookie) : array();
		$resaveListings = false;
		foreach(array_keys($saved_listings) as $key)
		{
			if (!\App()->ListingManager->doesListingExist($saved_listings[$key]))
			{
				unset($saved_listings[$key]);
				$resaveListings = true;
			}
		}
		if ($resaveListings)
			$this->save(is_array($saved_listings) ? $saved_listings : array());
		return is_array($saved_listings) ? $saved_listings : array();
	}
	
	function deleteListing($listing_sid)
	{
		$saved_listings = $this->getSavedListings();
		if (in_array($listing_sid, $saved_listings))
		{
			unset($saved_listings[array_search($listing_sid, $saved_listings)]);
			$this->save($saved_listings);
		}
	}

   	function deleteListings(array $listings)
	{
		$savedListings = $this->getSavedListings();
        foreach($listings as $listingId)
        {
            $key = array_search($listingId, $savedListings);
            if ($key !== false)
                unset($savedListings[$key]);
        }
        
    	$this->save($savedListings);
	}

	function save($saved_listings)
	{
		\App()->Cookie->setCookie('SAVED_LISTINGS', implode(',', $saved_listings), 365);
	}
}

?>
