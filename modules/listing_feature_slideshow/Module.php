<?php
/**
 *
 *    Module: listing_feature_slideshow v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_feature_slideshow-7.5.0-1
 *    Tag: tags/7.5.0-1@19792, 2016-06-17 13:19:49
 *
 *    This file is part of the 'listing_feature_slideshow' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_feature_slideshow;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'listing_feature_slideshow';
	protected $caption = 'Image Slideshow';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'classifieds',
	);

	public function getModuleTemplateProviderName()
	{
		return "Image Slideshow";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Image Slideshow templates";
	}

	public function getModuleName()
	{
		return "listing_feature_slideshow";
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
