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

class ListingPackageExtraDetailAllowed implements \modules\membership_plan\IListingPackageExtraDetail
{
	public function getId()
	{
		return 'feature_sponsored_allowed';
	}

	public function getCaption()
	{
		return 'Sponsored Listing Feature';
	}

	public function getType()
	{
		return 'boolean';
	}

	public function getExtraInfo()
	{
		return array
		(
			'value' => true,
			'input_template' => 'field_types^input/boolean_on_off.tpl',
		);
	}

	public static function getOrder()
	{
		return 400;
	}
}
