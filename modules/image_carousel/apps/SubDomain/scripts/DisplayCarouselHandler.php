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


namespace modules\image_carousel\apps\SubDomain\scripts;

class DisplayCarouselHandler extends \apps\SubDomain\ContentHandlerBase
{
	protected $displayName = 'Display Image Carousel';
	protected $moduleName = 'image_carousel';
	protected $functionName = 'display_carousel';
	protected $parameters = array('width', 'height');

	public function respond()
	{
		$handler = new \modules\image_carousel\apps\FrontEnd\scripts\DisplayCarouselHandler();
		$handler->respond();
	}
}
