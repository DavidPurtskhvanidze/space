<?php
/**
 *
 *    Module: theme_ilister_light v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: theme_ilister_light-7.5.0-1
 *    Tag: tags/7.5.0-1@19859, 2016-06-17 13:23:17
 *
 *    This file is part of the 'theme_ilister_light' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\theme_ilister_light;

class Module extends \apps\FrontEnd\ThemeModule
{
	protected $name = 'theme_ilister_light';
	protected $caption = 'Theme iLister Light';
	protected $version = '7.5.0-1';

	public function getDependencies()
	{
		return array
		(
			'theme_ilister_base',
		);
	}
}
