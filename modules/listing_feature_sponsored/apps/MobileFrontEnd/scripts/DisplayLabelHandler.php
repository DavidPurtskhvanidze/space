<?php
/**
 *
 *    Module: listing_feature_sponsored v.7.4.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_feature_sponsored-7.4.0-1
 *    Tag: tags/7.4.0-1@19153, 2016-01-11 11:20:12
 *
 *    This file is part of the 'listing_feature_sponsored' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_feature_sponsored\apps\MobileFrontEnd\scripts;

class DisplayLabelHandler extends \apps\MobileFrontEnd\ContentHandlerBase
{
	protected $displayName = 'Display Sponsored Label';
	protected $moduleName = 'listing_feature_sponsored';
	protected $functionName = 'display_label';
	protected $rawOutput = true;

	public function respond()
	{
		$listing = \App()->Request->getValueOrDefault('listing');

		if (!is_null($listing) && $listing['feature_sponsored']['exists'] && $listing['feature_sponsored']['isTrue'] && !$listing['feature_sponsored']['isEmpty'])
		{
			$template_processor = \App()->getTemplateProcessor();
			$template_processor->display('sponsored_label.tpl');
		}
	}
}
