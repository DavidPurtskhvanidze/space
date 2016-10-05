<?php
/**
 *
 *    Module: publications v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: publications-7.5.0-1
 *    Tag: tags/7.5.0-1@19806, 2016-06-17 13:20:27
 *
 *    This file is part of the 'publications' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\publications;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'publications';
	protected $caption = 'Publications';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'site_pages',
		'content_management',
	);

	public function getModuleTemplateProviderName()
	{
		return "Publication System";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Publication categories and article templates";
	}

	public function getModuleName()
	{
		return "publications";
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
