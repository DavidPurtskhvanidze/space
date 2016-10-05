<?php
/**
 *
 *    Module: listing_comments v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_comments-7.5.0-1
 *    Tag: tags/7.5.0-1@19790, 2016-06-17 13:19:43
 *
 *    This file is part of the 'listing_comments' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_comments\apps\FrontEnd\scripts;

class DisplayListingDetailsComments extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName	= 'Display Listing Details Comments';
	protected $moduleName	= 'listing_comments';
	protected $functionName	= 'display_listing_details_comments';

	public function respond()
	{
		$listing = \App()->Request['listing'];
		if (!is_null($listing))
		{
			$templateProcessor = \App()->getTemplateProcessor();
			$templateProcessor->assign('listing', $listing);
			$templateProcessor->assign('returnBackUri', \App()->Request['returnBackUri']);
			$templateProcessor->display('category_templates/display/listing_details_comments.tpl');
		}
	}
}
