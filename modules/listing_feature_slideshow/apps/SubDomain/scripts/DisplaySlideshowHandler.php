<?php
/**
 *
 *    Module: listing_feature_slideshow v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_feature_slideshow-7.5.0-1
 *    Tag: tags/7.5.0-1@19792, 2016-06-17 13:19:49
 *
 *    This file is part of the 'listing_feature_slideshow' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_feature_slideshow\apps\SubDomain\scripts;

class DisplaySlideshowHandler extends \apps\SubDomain\ContentHandlerBase
{
	protected $displayName	= 'Display Slideshow';
	protected $moduleName	= 'listing_feature_slideshow';
	protected $functionName	= 'display_slideshow';

	public function respond()
	{
		$handler = new \modules\listing_feature_slideshow\apps\FrontEnd\scripts\DisplaySlideshowHandler();
		$handler->respond();
	}
}
