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

class ListingFeature implements \modules\membership_plan\IListingFeature
{
	public function getFeatureName()
	{
		return 'Highlighted';
	}

	public function getPricePropertyId()
	{
		return 'feature_highlighted_price';
	}

	public function getAllowedPropertyId()
	{
		return 'feature_highlighted_allowed';
	}

	public function getListingPropertyId()
	{
		return 'feature_highlighted';
	}

	public function getListingPropertyCaption()
	{
		return 'Highlighted';
	}

	public function getActivationControlCaption()
	{
		return 'Make Highlighted';
	}
}
