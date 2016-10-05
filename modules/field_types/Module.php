<?php
/**
 *
 *    Module: field_types v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: field_types-7.5.0-1
 *    Tag: tags/7.5.0-1@19782, 2016-06-17 13:19:23
 *
 *    This file is part of the 'field_types' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\field_types;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'field_types';
	protected $caption = 'Field Types';
	protected $version = '7.5.0-1';

	public function getModuleTemplateProviderName()
	{
		return "Field Types";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Input, display and search field type templates";
	}

	public function getModuleName()
	{
		return "field_types";
	}

	public function getId()
	{
		return __CLASS__;
	}

	public function getAppIds()
	{
		return array('FrontEnd', 'MobileFrontEnd', 'SubDomain');
	}
}
