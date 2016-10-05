<?php
/**
 *
 *    Module: listing_feature_sponsored v.7.4.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_feature_sponsored-7.4.0-1
 *    Tag: tags/7.4.0-1@19153, 2016-01-11 11:20:12
 *
 *    This file is part of the 'listing_feature_sponsored' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_feature_sponsored;

class AfterListingFeatureActivated implements \modules\classifieds\IAfterListingFeatureActivated
{
	/**
	 * Feature name
	 * @var String
	 */
	private $featureId;
	/**
	 * Listing object
	 * @var \modules\classifieds\lib\Listing\Listing
	 */
	private $listing;
	private $featureType = 'Sponsored';
	private $rotatorTableName = 'classifieds_feature_display_rotator';
	
	public function setFeatureId($featureId)
	{
		$this->featureId = $featureId;
	}
	public function setListing($listing)
	{
		$this->listing = $listing;
	}
	public function perform()
	{
		if ($this->featureId !== 'feature_sponsored')
		{
			return;
		}
		
		$listingPackage = $this->listing->getListingPackageInfo();
		$maxOrderValue = (int) \App()->DB->getSingleValue("SELECT MAX(`order`) FROM `{$this->rotatorTableName}` WHERE `feature_type` = ?s", $this->featureType);
		\App()->DB->query(
			"INSERT INTO `{$this->rotatorTableName}`(`activation_date`, `expiration_date`, `listing_sid`, `feature_type`, `order`) VALUES (NOW(), (NOW() + INTERVAL ?n DAY), ?n, ?s, ?n)",
			(int) $listingPackage['feature_sponsored_lifetime'],
			$this->listing->getSID(),
			$this->featureType,
			$maxOrderValue
		);
	}
}
