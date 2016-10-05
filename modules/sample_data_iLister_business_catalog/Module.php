<?php
/**
 *
 *    Module: sample_data_iLister_business_catalog v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: sample_data_iLister_business_catalog-7.3.0-1
 *    Tag: tags/7.3.0-1@18571, 2015-08-24 13:38:37
 *
 *    This file is part of the 'sample_data_iLister_business_catalog' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\sample_data_iLister_business_catalog;

class Module extends \core\SampleDataModule
{
	protected $name = 'sample_data_iLister_business_catalog';
	protected $caption = 'iLister Business Catalog Sample Data';
	protected $version = '7.3.0-1';
	protected $dependencies = array
	(
		'business_catalog',
	);
}
