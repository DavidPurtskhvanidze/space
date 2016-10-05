<?php
/**
 *
 *    Module: theme_bootstrap_base v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: theme_bootstrap_base-7.5.0-1
 *    Tag: tags/7.5.0-1@19841, 2016-06-17 13:22:16
 *
 *    This file is part of the 'theme_bootstrap_base' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\theme_bootstrap_base;

class Module extends \apps\FrontEnd\ThemeModule
{
    protected $name = "theme_bootstrap_base";
    protected $caption = "Theme Bootstrap Base";
    protected $version = "7.5.0-1";
	protected $dependencies = array
	(
		'image_carousel',
	);
}
