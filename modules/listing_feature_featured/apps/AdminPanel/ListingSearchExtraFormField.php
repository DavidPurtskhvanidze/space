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

namespace modules\listing_feature_featured\apps\AdminPanel;

class ListingSearchExtraFormField implements \modules\classifieds\apps\AdminPanel\IListingSearchExtraFormField
{
    public function getCaption()
    {
        return 'Featured Ad';
    }

    public function getID()
    {
        return 'feature_featured';
    }
}
