<?php
/**
 *
 *    Module: membership_plan v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: membership_plan-7.5.0-1
 *    Tag: tags/7.5.0-1@19798, 2016-06-17 13:20:05
 *
 *    This file is part of the 'membership_plan' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\membership_plan;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'membership_plan';
	protected $caption = 'Membership Plan';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'site_pages',
		'payment_system',
	);

	public function getModuleTemplateProviderName()
	{
		return "Membership Plan";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Membership Plan and Listing Package templates";
	}

	public function getModuleName()
	{
		return "membership_plan";
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
