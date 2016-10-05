<?php
/**
 *
 *    Module: kcfinder v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: kcfinder-7.3.0-1
 *    Tag: tags/7.3.0-1@18531, 2015-08-24 13:36:38
 *
 *    This file is part of the 'kcfinder' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\kcfinder;

class Module extends \core\Module
{
	protected $name = 'kcfinder';
	protected $caption = 'KCFinder';
	protected $version = '7.3.0-1';
	protected $dependencies = array
	(
	);
	
	public function install()
	{
		parent::install();
		\App()->FileSystem->getWritableFilesDir("kcfinder");
	}
	
}
