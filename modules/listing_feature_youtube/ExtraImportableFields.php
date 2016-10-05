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

use modules\user_import_export_listings\lib\IExtraImportableFields;

class ExtraImportableFields implements IExtraImportableFields
{
	private $listing;

	public function setListing($listing)
	{
		$this->listing = $listing;
	}

	public function getFields()
	{
		$video_id = $this->listing->getPropertyValue('feature_youtube_video_id');
		if (!empty($video_id)) {
			return [
				'feature_youtube_video_id'
            ];
		}
	}
}
