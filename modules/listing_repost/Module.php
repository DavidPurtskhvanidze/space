<?php
/**
 *
 *    Module: listing_repost v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_repost-7.5.0-1
 *    Tag: tags/7.5.0-1@19795, 2016-06-17 13:19:57
 *
 *    This file is part of the 'listing_repost' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_repost;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'listing_repost';
	protected $caption = 'Listing Repost';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'classifieds',
		'third_party_auth_providers',
	);

	public function getModuleTemplateProviderName()
	{
		return "Listing Repost";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Listing Repost templates";
	}

	public function getModuleName()
	{
		return "listing_repost";
	}

	public function getId()
	{
		return __CLASS__;
	}

	public function getAppIds()
	{
		return array('FrontEnd');
	}
}
