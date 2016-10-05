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


namespace modules\listing_feature_featured;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'listing_feature_featured';
	protected $caption = 'Featured Listing';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'classifieds',
	);

	public function getModuleTemplateProviderName()
	{
		return "Featured Listing";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Featured Listing templates";
	}

	public function getModuleName()
	{
		return "listing_feature_featured";
	}

	public function getId()
	{
		return __CLASS__;
	}

	public function getAppIds()
	{
		return array('FrontEnd', 'MobileFrontEnd', 'SubDomain');
	}
}
