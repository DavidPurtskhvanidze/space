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

class ListingFeature implements \modules\membership_plan\IListingFeature
{
	public function getFeatureName()
	{
		return 'Sponsored';
	}

	public function getPricePropertyId()
	{
		return 'feature_sponsored_price';
	}

	public function getAllowedPropertyId()
	{
		return 'feature_sponsored_allowed';
	}

	public function getListingPropertyId()
	{
		return 'feature_sponsored';
	}

	public function getListingPropertyCaption()
	{
		return 'Sponsored';
	}

	public function getActivationControlCaption()
	{
		return 'Make Sponsored';
	}
}
