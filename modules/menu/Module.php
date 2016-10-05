<?php
/**
 *
 *    Module: menu v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: menu-7.5.0-1
 *    Tag: tags/7.5.0-1@19799, 2016-06-17 13:20:07
 *
 *    This file is part of the 'menu' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\menu;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'menu';
	protected $caption = 'Menu';
	protected $version = '7.5.0-1';

	public function getModuleTemplateProviderName()
	{
		return "Menu";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Site menu templates";
	}

	public function getModuleName()
	{
		return "menu";
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
