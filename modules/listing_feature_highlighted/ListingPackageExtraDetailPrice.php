<?php
/**
 *
 *    Module: listing_feature_highlighted v.7.0.0-1, (c) WorksForWeb 2005 - 2014
 *
 *    Package: listing_feature_highlighted-7.0.0-1
 *    Tag: tags/7.0.0-1@16269, 2014-10-03 12:00:56
 *
 *    This file is part of the 'listing_feature_highlighted' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_feature_highlighted;

class ListingPackageExtraDetailPrice implements \modules\membership_plan\IListingPackageExtraDetail
{
	public function getId()
	{
		return 'feature_highlighted_price';
	}

	public function getCaption()
	{
		return 'Price for Highlighted Feature';
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
		return 210;
	}
}
