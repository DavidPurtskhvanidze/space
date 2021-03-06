<?php
/**
 *
 *    Module: sample_data_poll v.7.0.0-1, (c) WorksForWeb 2005 - 2014
 *
 *    Package: sample_data_poll-7.0.0-1
 *    Tag: tags/7.0.0-1@16347, 2014-10-03 12:05:08
 *
 *    This file is part of the 'sample_data_poll' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\sample_data_poll;

class Module extends \core\SampleDataModule
{
	protected $name = 'sample_data_poll';
	protected $caption = 'Poll Sample Data';
	protected $version = '7.0.0-1';
	protected $dependencies = array
	(
		'poll',
	);
}
