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


namespace modules\listing_feature_sponsored\lib;

class DeactivateExpiredListingsTask extends \modules\miscellaneous\lib\ScheduledTaskBase
{
	private $featureType = 'feature_sponsored';
	private $rotatorTableName = 'classifieds_feature_display_rotator';
	
	public static function getOrder()
	{
		return 500;
	}
	
	public function run()
	{
		$this->scheduler->log('Expiring sponsored feature of listings');
		
		$listingSids = \App()->DB->query("SELECT `listing_sid` FROM `{$this->rotatorTableName}` WHERE `expiration_date` < NOW()");
		$listingSids = (empty($listingSids)) ? array() : array_map(function($row){return array_pop($row);}, $listingSids);

		$this->scheduler->log(sprintf('Found %d expired sponsored feature of listings. %s', count($listingSids), join(', ', $listingSids)));

		foreach($listingSids as $listingSid)
		{
			$listing = \App()->ListingManager->getObjectBySID($listingSid);
			\App()->ListingFeaturesManager->deactivateFeatureForListing($listing, $this->featureType);
		}
	}
}
