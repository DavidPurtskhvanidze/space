<?php
/**
 *
 *    Module: sample_data_iLister_main v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: sample_data_iLister_main-7.3.0-1
 *    Tag: tags/7.3.0-1@18573, 2015-08-24 13:38:44
 *
 *    This file is part of the 'sample_data_iLister_main' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\sample_data_iLister_main;

class Module extends \core\SampleDataModule
{
	protected $name = 'sample_data_iLister_main';
	protected $caption = 'iLister Themes And Settings';
	protected $version = '7.3.0-1';
	protected $dependencies = array
	(
		'site_pages'
	);
}
