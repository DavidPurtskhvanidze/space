<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\apps\FrontEnd\scripts;

class DeleteSavedListingHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Delete Saved Listing';
	protected $moduleName = 'classifieds';
	protected $functionName = 'delete_saved_listing';
	protected $rawOutput = true;

	public function respond()
	{
		$savedListings = \App()->ObjectMother->createSavedListings();
		$savedListings->deleteListing($_REQUEST['listing_id']);
        $template_processor = \App()->getTemplateProcessor();
        $template_processor->assign("savedListingsAmount", count($savedListings->getSavedListings()));
        $template_processor->display("delete_saved_listing.tpl");
	}
}
