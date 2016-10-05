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

class SavedListingsForUserLoggedIn
{
	var $userSid;
	var $DB;

	public function setDB($DB)
	{
		$this->DB = $DB;
	}
	
	function setUserSid($userSid)
	{
		$this->userSid = $userSid;
	}
	
	function getUserSid()
	{
		return $this->userSid;
	}
	
	function saveListing($listing_sid)
	{
		$saved_listings = $this->getSavedListings();
		array_push($saved_listings, $listing_sid);
		$saved_listings = array_unique($saved_listings);
		$record_exists = $this->DB->getSingleValue("SELECT COUNT(*) FROM `classifieds_saved_listings` WHERE `user_sid` = ?n", $this->userSid);
		if ($record_exists)
		{
			$this->DB->query("UPDATE `classifieds_saved_listings` SET `listings` = ?s WHERE `user_sid` = ?n", implode(',', $saved_listings), $this->userSid);
		}
		else
		{
			$this->DB->query("INSERT INTO `classifieds_saved_listings` SET `listings` = ?s, `user_sid` = ?n", implode(',', $saved_listings), $this->userSid);
		}
	}
	
	function getSavedListings()
	{
		$saved_listings = $this->DB->getSingleValue("SELECT `listings` FROM `classifieds_saved_listings` WHERE `user_sid` = ?n", $this->userSid);
		$saved_listings = is_null($saved_listings) ? array() : explode(',', $saved_listings);
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
		{
			if (empty($saved_listings))
			{
				$this->DB->query("DELETE FROM `classifieds_saved_listings` WHERE `user_sid` = ?n", $this->userSid);
			}
			else
			{
				$this->DB->query(
						"UPDATE `classifieds_saved_listings` SET `listings` = ?s WHERE `user_sid` = ?n",
						implode(',', $saved_listings),
						$this->userSid
				);
			}
		}

		return $saved_listings;
	}
	
	function deleteListing($listing_sid)
	{
		$saved_listings = $this->getSavedListings();
		
		if (in_array($listing_sid, $saved_listings))
		{
			unset($saved_listings[array_search($listing_sid, $saved_listings)]);
			
			if (empty($saved_listings))
			
				$this->DB->query("DELETE FROM `classifieds_saved_listings` WHERE `user_sid` = ?n", $this->userSid);
				
			else
			
				$this->DB->query("UPDATE `classifieds_saved_listings` SET `listings` = ?s WHERE `user_sid` = ?n", implode(',', $saved_listings), $this->userSid);
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

        if (empty($savedListings))
            $this->DB->query("DELETE FROM `classifieds_saved_listings` WHERE `user_sid` = ?n", $this->userSid);
        else
            $this->DB->query("UPDATE `classifieds_saved_listings` SET `listings` = ?s WHERE `user_sid` = ?n", implode(',', $savedListings), $this->userSid);
    }
	
	function deleteAllListings()
	{
		return $this->DB->query("DELETE FROM `classifieds_saved_listings` WHERE `user_sid` = ?n", $this->userSid);
	}
}
