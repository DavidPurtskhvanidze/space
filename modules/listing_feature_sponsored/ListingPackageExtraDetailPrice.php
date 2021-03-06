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

class ListingPackageExtraDetailPrice implements \modules\membership_plan\IListingPackageExtraDetail
{
	public function getId()
	{
		return 'feature_sponsored_price';
	}

	public function getCaption()
	{
		return 'Price for Sponsored Listing';
	}

	public function getType()
	{
		return 'transaction_money';
	}

	public function getExtraInfo()
	{
		return array
		(
			'length'		=> '20',
			'minimum'		=> '0',
			'signs_num'		=> '2',
			'is_required'	=> false
		);
	}

	public static function getOrder()
	{
		return 410;
	}
}
