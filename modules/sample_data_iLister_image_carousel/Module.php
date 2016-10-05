<?php
/**
 *
 *    Module: sample_data_iLister_image_carousel v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: sample_data_iLister_image_carousel-7.5.0-1
 *    Tag: tags/7.5.0-1@19819, 2016-06-17 13:21:06
 *
 *    This file is part of the 'sample_data_iLister_image_carousel' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\sample_data_iLister_image_carousel;

class Module extends \core\SampleDataModule
{
	protected $name = 'sample_data_iLister_image_carousel';
	protected $caption = 'iLister Image Carousel Sample Data';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'image_carousel',
	);

	public function install()
	{
		parent::install();

		$this->copyFiles($this->getDir() . '/pictures', 'image_carousel');
	}
}
