<?php
/**
 *
 *    Module: listing_feature_youtube v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_feature_youtube-7.5.0-1
 *    Tag: tags/7.5.0-1@19793, 2016-06-17 13:19:51
 *
 *    This file is part of the 'listing_feature_youtube' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_feature_youtube;

use modules\membership_plan\IListingFeature;

class ListingFeature implements IListingFeature
{
	public function getFeatureName()
	{
		return 'YouTube Video';
	}

	public function getPricePropertyId()
	{
		return 'feature_youtube_price';
	}

	public function getAllowedPropertyId()
	{
		return 'feature_youtube_allowed';
	}

	public function getListingPropertyId()
	{
		return 'feature_youtube';
	}

	public function getListingPropertyCaption()
	{
		return 'YouTube Video';
	}

	public function getActivationControlCaption()
	{
		return 'Activate YouTube Video';
	}
}
