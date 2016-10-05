<?php
/**
 *
 *    Module: listing_feature_featured v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_feature_featured-7.5.0-1
 *    Tag: tags/7.5.0-1@19791, 2016-06-17 13:19:46
 *
 *    This file is part of the 'listing_feature_featured' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_feature_featured;

class ListingFeature implements \modules\membership_plan\IListingFeature
{
	public function getFeatureName()
	{
		return 'Featured Ad';
	}

	public function getPricePropertyId()
	{
		return 'feature_featured_price';
	}

	public function getListingPropertyId()
	{
		return 'feature_featured';
	}

	public function getListingPropertyCaption()
	{
		return 'Featured Ad';
	}

	public function getActivationControlCaption()
	{
		return 'Upgrade to Featured Ad';
	}

	public function getAllowedPropertyId()
	{
		return 'feature_featured_allowed';
	}
}
