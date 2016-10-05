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


namespace modules\listing_feature_slideshow\apps\AdminPanel\scripts;

class DisplaySlideshowHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $displayName	= 'Display Slideshow';
	protected $moduleName	= 'listing_feature_slideshow';
	protected $functionName	= 'display_slideshow';

	public function respond()
	{
		$listing = \App()->Request['listing'];
		if (!is_null($listing) && $listing['feature_slideshow']['exists'] && $listing['feature_slideshow']['isTrue'])
		{
			$templateProcessor = \App()->getTemplateProcessor();
//            dd($listing);
			$templateProcessor->assign('listing', $listing);
			$templateProcessor->display('image_slideshow.tpl');
		}
	}
}
