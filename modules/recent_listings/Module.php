<?php
/**
 *
 *    Module: recent_listings v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: recent_listings-7.5.0-1
 *    Tag: tags/7.5.0-1@19807, 2016-06-17 13:20:30
 *
 *    This file is part of the 'recent_listings' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\recent_listings;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'recent_listings';
	protected $caption = 'Recent Listings';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'classifieds',
	);

	public function getModuleTemplateProviderName()
	{
		return "Recent Listings";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Recent Listings templates";
	}

	public function getModuleName()
	{
		return "recent_listings";
	}

	public function getId()
	{
		return __CLASS__;
	}

	public function getAppIds()
	{
		return array('FrontEnd', 'SubDomain');
	}
}
