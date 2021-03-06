<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'miscellaneous';
	protected $caption = 'Miscellaneous';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'site_pages',
	);

	public function getModuleTemplateProviderName()
	{
		return "Miscellaneous";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Miscellaneous routine templates";
	}

	public function getModuleName()
	{
		return "miscellaneous";
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
