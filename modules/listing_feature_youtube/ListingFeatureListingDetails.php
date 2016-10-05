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

use modules\membership_plan\IListingFeatureListingDetails;

class ListingFeatureListingDetails implements IListingFeatureListingDetails
{
	public function getListingDetails() {
		return [
			[
				'id' => 'feature_youtube_video_id',
				'caption' => 'YouTube Video URL or ID',
				'type' => 'string',
				'is_required' => 0,
				'maxlength' => 256,
				'input_template' => 'listing_feature_youtube^input/youtube_video_id.tpl',
            ],
        ];
	}
}
