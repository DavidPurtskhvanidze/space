<?php
/**
 *
 *    Module: main v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: main-7.5.0-1
 *    Tag: tags/7.5.0-1@19796, 2016-06-17 13:19:59
 *
 *    This file is part of the 'main' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\main;

class Module extends \core\Module
{
	protected $name = 'main';
	protected $caption = 'Main';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'site_pages',
		'miscellaneous',
		'field_types',
		'module_manager',
		'smarty_based_template_processor',
		'I18N',
		'template_manager',
	);

	public function install()
	{
		parent::install();
		\App()->DB->multilineQuery(file_get_contents($this->getDir() . "/locations.sql"));
	}
}
