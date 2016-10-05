<?php
/**
 *
 *    Module: listing_feature_sponsored v.7.4.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_feature_sponsored-7.4.0-1
 *    Tag: tags/7.4.0-1@19153, 2016-01-11 11:20:12
 *
 *    This file is part of the 'listing_feature_sponsored' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_feature_sponsored;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'listing_feature_sponsored';
	protected $caption = 'Sponsored Listing';
	protected $version = '7.4.0-1';
	protected $dependencies = array
	(
		'classifieds',
	);

	public function getModuleTemplateProviderName()
	{
		return "Sponsored Listing";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Sponsored Listing templates";
	}

	public function getModuleName()
	{
		return "listing_feature_sponsored";
	}

	public function getId()
	{
		return __CLASS__;
	}

	public function getAppIds()
	{
		return array('FrontEnd', 'MobileFrontEnd');
	}
}
