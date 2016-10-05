<?php
/**
 *
 *    Module: listing_comments v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_comments-7.5.0-1
 *    Tag: tags/7.5.0-1@19790, 2016-06-17 13:19:43
 *
 *    This file is part of the 'listing_comments' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_comments;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'listing_comments';
	protected $caption = 'Listing Comments';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'site_pages',
		'classifieds',
	);

	public function getModuleTemplateProviderName()
	{
		return "Listing comments";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Listing comments templates";
	}

	public function getModuleName()
	{
		return "listing_comments";
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