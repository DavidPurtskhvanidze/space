<?php
/**
 *
 *    Module: export_listings v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: export_listings-7.5.0-1
 *    Tag: tags/7.5.0-1@19779, 2016-06-17 13:19:16
 *
 *    This file is part of the 'export_listings' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\export_listings;

class Module extends \core\Module
{
	protected $name = 'export_listings';
	protected $caption = 'Export Listings';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'classifieds',
	);
}
