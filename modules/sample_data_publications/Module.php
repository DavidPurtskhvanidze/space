<?php
/**
 *
 *    Module: sample_data_publications v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: sample_data_publications-7.5.0-1
 *    Tag: tags/7.5.0-1@19832, 2016-06-17 13:21:46
 *
 *    This file is part of the 'sample_data_publications' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\sample_data_publications;

class Module extends \core\SampleDataModule
{
	protected $name = 'sample_data_publications';
	protected $caption = 'Publications Sample Data';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'publications'
	);
}
