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

use modules\membership_plan\IListingPackageExtraDetail;

class ListingPackageExtraDetailAllowed implements IListingPackageExtraDetail
{
    public function getId()
    {
        return 'feature_youtube_allowed';
    }

    public function getCaption()
    {
        return 'YouTube Video Feature';
    }

    public function getType()
    {
        return 'boolean';
    }

    public function getExtraInfo()
    {
        return
            [
                'value' => true,
                'input_template' => 'field_types^input/boolean_on_off.tpl',
            ];
    }

    public static function getOrder()
    {
        return 500;
    }
}
