<?php
/**
 *
 *    Module: sample_data_iLister_listing_comments v.7.1.0-1, (c) WorksForWeb 2005 - 2014
 *
 *    Package: sample_data_iLister_listing_comments-7.1.0-1
 *    Tag: tags/7.1.0-1@17039, 2014-12-23 aN:07:21
 *
 *    This file is part of the 'sample_data_iLister_listing_comments' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\sample_data_iLister_listing_comments;

class Module extends \core\SampleDataModule
{
	protected $name = 'sample_data_iLister_listing_comments';
	protected $caption = 'iLister Listing Comments Sample Data';
	protected $version = '7.1.0-1';
	protected $dependencies = array
	(
		'listing_comments',
		'sample_data_iLister_classifieds_listings',
		'sample_data_iLister_users',
	);
}
