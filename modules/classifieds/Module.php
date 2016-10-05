<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'classifieds';
	protected $caption = 'Classifieds';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'basket',
		'site_pages',
		'membership_plan',
		'users',
	);

	public function getModuleTemplateProviderName()
	{
		return "Classifieds engine";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Classifieds engine templates";
	}

	public function getModuleName()
	{
		return "classifieds";
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
