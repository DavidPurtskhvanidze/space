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


namespace modules\listing_feature_sponsored\apps\AdminPanel;

class AfterEditListingAction implements \modules\classifieds\apps\AdminPanel\IAfterEditListingAction
{
	/**
	 * Listing object
	 * @var \modules\classifieds\lib\Listing\Listing
	 */
	private $listing;
	private $featureType = 'Sponsored';
	private $rotatorTableName = 'classifieds_feature_display_rotator';
	
	public function setListing($listing)
	{
		$this->listing = $listing;
	}
	public function perform()
	{
		if (!((bool) $this->listing->getPropertyValue('feature_sponsored')))
		{
			\App()->DB->query(
				"DELETE FROM `{$this->rotatorTableName}` WHERE `feature_type` = ?s AND `listing_sid` = ?n",
				$this->featureType,
				$this->listing->getSID()
			);
		}
		else
		{
			$result = \App()->DB->query("SELECT * FROM `{$this->rotatorTableName}` WHERE `listing_sid` = ?n AND `feature_type` = ?s", $this->listing->getSID(), $this->featureType);
			if (empty($result))
			{
				$maxOrderValue = (int) \App()->DB->getSingleValue("SELECT MAX(`order`) FROM `{$this->rotatorTableName}` WHERE `feature_type` = ?s", $this->featureType);
				\App()->DB->query(
					"INSERT INTO `{$this->rotatorTableName}`(`activation_date`, `listing_sid`, `feature_type`, `order`) VALUES (NOW(), ?n, ?s, ?n)",
					$this->listing->getSID(),
					$this->featureType,
					$maxOrderValue
				);
			}
		}
	}
}
