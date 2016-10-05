<?php
/**
 *
 *    Module: theme_ilister_tabs_blue v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: theme_ilister_tabs_blue-7.5.0-1
 *    Tag: tags/7.5.0-1@19863, 2016-06-17 13:23:32
 *
 *    This file is part of the 'theme_ilister_tabs_blue' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\theme_ilister_tabs_blue;

class Module extends \apps\FrontEnd\ThemeModule
{
	protected $name = 'theme_ilister_tabs_blue';
	protected $caption = 'Theme iLister Tabs Blue';
	protected $version = '7.5.0-1';

	public function getDependencies()
	{
		return array
		(
			'theme_ilister_base',
		);
	}
}
