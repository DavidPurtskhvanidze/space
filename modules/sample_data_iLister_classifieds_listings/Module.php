<?php
/**
 *
 *    Module: sample_data_iLister_classifieds_listings v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: sample_data_iLister_classifieds_listings-7.5.0-1
 *    Tag: tags/7.5.0-1@19818, 2016-06-17 13:21:03
 *
 *    This file is part of the 'sample_data_iLister_classifieds_listings' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\sample_data_iLister_classifieds_listings;

class Module extends \core\SampleDataModule
{
	protected $name = 'sample_data_iLister_classifieds_listings';
	protected $caption = 'iLister Listings Sample Data';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'classifieds',
		'listing_feature_highlighted',
		'listing_feature_slideshow',
		'listing_feature_sponsored',
		'listing_feature_youtube',
		'listing_repost',
		'sample_data_iLister_classifieds_categories',
		'sample_data_iLister_users',
	);
}
