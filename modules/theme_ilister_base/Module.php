<?php
/**
 *
 *    Module: theme_ilister_base v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: theme_ilister_base-7.5.0-1
 *    Tag: tags/7.5.0-1@19851, 2016-06-17 13:22:49
 *
 *    This file is part of the 'theme_ilister_base' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\theme_ilister_base;

class Module extends \apps\FrontEnd\ThemeModule
{
	protected $name = 'theme_ilister_base';
	protected $caption = 'Theme iLister Base';
	protected $version = '7.5.0-1';

    public function getDependencies()
    {
        return array
        (
            'theme_isoft_base',
        );
    }
}
