<?php
/**
 *
 *    Module: listing_feature_slideshow v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_feature_slideshow-7.5.0-1
 *    Tag: tags/7.5.0-1@19792, 2016-06-17 13:19:49
 *
 *    This file is part of the 'listing_feature_slideshow' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_feature_slideshow;

class ListingFeature implements \modules\membership_plan\IListingFeature
{
	public function getFeatureName()
	{
		return 'Slide Show';
	}

	public function getPricePropertyId()
	{
		return 'feature_slideshow_price';
	}

	public function getAllowedPropertyId()
	{
		return 'feature_slideshow_allowed';
	}

	public function getListingPropertyId()
	{
		return 'feature_slideshow';
	}

	public function getListingPropertyCaption()
	{
		return 'Slide Show';
	}

	public function getActivationControlCaption()
	{
		return 'Activate Slideshow';
	}
}
