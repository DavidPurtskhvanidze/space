<?php
/**
 *
 *    Module: image_carousel v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: image_carousel-7.5.0-1
 *    Tag: tags/7.5.0-1@19785, 2016-06-17 13:19:31
 *
 *    This file is part of the 'image_carousel' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\image_carousel;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'image_carousel';
	protected $caption = 'Image Carousel';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'content_management',
	);

	public function install()
	{
		parent::install();
		\App()->FileSystem->getWritableFilesDir($this->name);
	}

	public function getModuleTemplateProviderName()
	{
		return "Image Carousel";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Image Carousel templates";
	}

	public function getModuleName()
	{
		return "image_carousel";
	}

	public function getId()
	{
		return __CLASS__;
	}

	public function getAppIds()
	{
		return array('FrontEnd', 'SubDomain');
	}
}
