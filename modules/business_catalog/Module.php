<?php
/**
 *
 *    Module: business_catalog v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: business_catalog-7.5.0-1
 *    Tag: tags/7.5.0-1@19772, 2016-06-17 13:18:58
 *
 *    This file is part of the 'business_catalog' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\business_catalog;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'business_catalog';
	protected $caption = 'Business Catalog';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'site_pages',
		'content_management',
	);

	public function getModuleTemplateProviderName()
	{
		return "Business Catalog";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Business catalog templates";
	}

	public function getModuleName()
	{
		return "business_catalog";
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
