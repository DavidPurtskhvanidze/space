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

class ListingDBManager extends \lib\ORM\ObjectDBManager
{
	/**
	 * @param Listing $listing
	 * @return bool
	 */
	public function saveListing($listing)
	{
		if (is_null($listing->getCategorySID()))
		{
			return false;
		}

		parent::saveObject($listing);
		if (!\App()->ListingPackageManager->isListingSIDExist($listing->getSID()))
		{
			\App()->ListingPackageManager->insertPackage($listing->getSID(), $listing->getListingPackageInfo());
		}

		return \App()->DB->query("UPDATE `classifieds_listings` SET `category_sid` = ?n, `user_sid` = ?n WHERE `sid` = ?n",
			$listing->getCategorySID(), $listing->getUserSID(), $listing->getSID());
	}

	function getListingsNumberByUserSID($user_sid)
	{
		$listing_number = \App()->DB->getSingleValue("SELECT COUNT(*) FROM `classifieds_listings` WHERE `user_sid` = ?n", $user_sid);
		
		return $listing_number;
	}

	function getAllListingSIDs()
	{
		return \App()->DB->query("SELECT `sid`, `sid` AS `id` FROM `classifieds_listings`");
	}

	function getListingInfoBySID($listing_sid)
	{
    	return parent::getObjectInfo("classifieds_listings", $listing_sid);
	}
	
	function getActiveListingsSIDByUserSID($user_sid)
	{
		$listings_info = \App()->DB->query("SELECT * FROM `classifieds_listings` WHERE `active`=1 AND `user_sid`=?n", $user_sid);
		
		$listings_sid = array();
		
		foreach ($listings_info as $listing_info)
		{
			$listings_sid[] = $listing_info['sid'];
		}
		
		return $listings_sid;
	}
	
	function getListingsSIDByUserSID($user_sid)
	{
		$listings_info = \App()->DB->query("SELECT `sid` FROM `classifieds_listings` WHERE `user_sid`=?n", $user_sid);
		$listingSids = (empty($listings_info)) ? array() : array_map(function($element){ return $element['sid'];}, $listings_info);
		
		return $listingSids;
	}
	
	function activateListingBySID($listing_sid) {
		
		return \App()->DB->query("UPDATE `classifieds_listings` SET `active` = 1 WHERE `sid` = ?n", $listing_sid);
		
	}
		
	function setListingExpirationDateBySid($listing_sid) {
		
		$package_info = \App()->DB->getSingleValue("SELECT `package_info` FROM `membership_plan_listing_packages` WHERE `listing_sid` = ?n", $listing_sid);
		$package_info = empty($package_info) ? null : unserialize($package_info);

		if (!is_null($package_info['listing_lifetime']))
		{
			\App()->DB->query("UPDATE `classifieds_listings` SET `expiration_date` = NOW() + INTERVAL ?n DAY WHERE `sid` = ?n", $package_info['listing_lifetime'], $listing_sid);
		}
		
		return true;
		
	}
	
	function deleteListingBySID($listing_sid) {
		
		parent::deleteObjectInfoFromDB('classifieds_listings', $listing_sid);
		
	}
	
	function deactivateListingBySID($listing_sid) {
		
		return \App()->DB->query("UPDATE `classifieds_listings` SET `active` = 0 WHERE `sid` = ?n", $listing_sid);
		
	}
	
	function getExpiredListingsSID() {
		
		$listings = \App()->DB->query("SELECT sid FROM `classifieds_listings` WHERE `expiration_date` < NOW() AND `active` = 1");
		
		if (empty($listings)) return array();
		
		$listings_sid = array();
		
		foreach ($listings as $listing) {
			
			$listings_sid[] = $listing['sid'];
			
		}
		
		return $listings_sid;
		
	}
	
	function getUserSIDByListingSID($listing_sid)
	{
		$user_sid = \App()->DB->getSingleValue("SELECT `user_sid` FROM `classifieds_listings` WHERE `sid` = ?n", $listing_sid);
		return $user_sid;
	}
	
	function getSIDCollectionByTypes($type_sid_collection) {
		return \App()->DB->query("SELECT `sid` FROM `classifieds_listings` WHERE `category_sid` IN ('?l')", $type_sid_collection);
	}
	
	function getListingsInfoBySidCollection($sid_collection)
	{
		$packages_info = \App()->DB->query("SELECT `listing_sid`, `package_info` FROM `membership_plan_listing_packages` WHERE `listing_sid` IN (?l)", empty($sid_collection) ? array('NULL') : $sid_collection);
		$listings_info = parent::getObjectsInfoBySidCollection('classifieds_listings', $sid_collection);
		
		foreach ($packages_info as $package_info)
		{
			$listings_info[$package_info['listing_sid']]['package'] = unserialize($package_info['package_info']);
		}
		
		return $listings_info;
	}
}
