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

class Module extends \core\Module
{
	protected $name = 'listing_feature_highlighted';
	protected $caption = 'Listing Highlight';
	protected $version = '7.0.0-1';
	protected $dependencies = array
	(
		'classifieds',
	);
}
