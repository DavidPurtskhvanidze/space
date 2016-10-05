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

class SavedListingsHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Saved Listings';
	protected $moduleName = 'classifieds';
	protected $functionName = 'saved_listings';

	public function respond()
	{
		$savedListings = \App()->ObjectMother->createSavedListings();
		if ((isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') || (isset($_REQUEST['action_delete'])))
		{
			$listingsIds = (isset($_REQUEST['listings']) && is_array($_REQUEST['listings'])) ? $_REQUEST['listings'] : array();
			$savedListings->deleteListings(array_keys($listingsIds));
			throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI());
		}

		$saved_listings_ids = $savedListings->getSavedListings();

		if (empty($saved_listings_ids)) {
			$saved_listings_ids = array(-1);
		}
		$_REQUEST['id'] = array('in' => $saved_listings_ids);

		if (!isset($_REQUEST['restore']) && !isset($_REQUEST['action'])) {
			$_REQUEST['action'] = 'search';
		}

		$template_processor = \App()->getTemplateProcessor();
		$template_processor->display("saved_listings_main.tpl");
	}
}
