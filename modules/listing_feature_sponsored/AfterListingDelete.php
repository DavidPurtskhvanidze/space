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

class AfterListingDelete implements \modules\classifieds\IAfterListingDelete
{
	private $featureType = 'Sponsored';
	private $rotatorTableName = 'classifieds_feature_display_rotator';
	private $listingSid;
	
	public function setListingSid($listingSid)
	{
		$this->listingSid = $listingSid;
	}
	public function perform()
	{
		\App()->DB->query(
			"DELETE FROM `{$this->rotatorTableName}` WHERE `feature_type` = ?s AND `listing_sid` = ?n",
			$this->featureType,
			$this->listingSid
		);
	}
}
