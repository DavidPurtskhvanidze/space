<?php
/**
 *
 *    Module: sample_data_iLister_classifieds_categories v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: sample_data_iLister_classifieds_categories-7.5.0-1
 *    Tag: tags/7.5.0-1@19817, 2016-06-17 13:21:00
 *
 *    This file is part of the 'sample_data_iLister_classifieds_categories' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\sample_data_iLister_classifieds_categories;

class Module extends \core\SampleDataModule
{
	protected $name = 'sample_data_iLister_classifieds_categories';
	protected $caption = 'iLister Categories Sample Data';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'classifieds',
	);
}
