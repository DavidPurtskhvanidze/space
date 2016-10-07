<?php

namespace modules\custom_theme_space;
{
	class Module extends \apps\FrontEnd\ThemeModule implements \apps\FrontEnd\ICustomTheme
	{
		protected $name = 'custom_theme_space';
		protected $caption = 'space';
		protected $version = '==product_version==-1';
		protected $dependencies = array('theme_ilister_bootstrap');
	}
}