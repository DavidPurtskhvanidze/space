<?php
/**
 *
 *    Module: sample_data_static_content v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: sample_data_static_content-7.3.0-1
 *    Tag: tags/7.3.0-1@18587, 2015-08-24 13:39:48
 *
 *    This file is part of the 'sample_data_static_content' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\sample_data_static_content;

class Module extends \core\SampleDataModule
{
	protected $name = 'sample_data_static_content';
	protected $caption = 'Static Content Sample Data';
	protected $version = '7.3.0-1';
	protected $dependencies = array
	(
		'static_content',
	);
}
