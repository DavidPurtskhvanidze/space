<?php
/**
 *
 *    Module: google_map v.7.4.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: google_map-7.4.0-1
 *    Tag: tags/7.4.0-1@19060, 2015-12-14 12:48:53
 *
 *    This file is part of the 'google_map' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\google_map;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'google_map';
	protected $caption = 'Google Map';
	protected $version = '7.4.0-1';

	public function getModuleTemplateProviderName()
	{
		return "Google Map";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Google Map templates";
	}

	public function getModuleName()
	{
		return "google_map";
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
