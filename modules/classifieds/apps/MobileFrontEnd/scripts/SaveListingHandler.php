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


namespace modules\classifieds\apps\MobileFrontEnd\scripts;

use apps\MobileFrontEnd\ContentHandlerBase;
use lib\Http\RedirectException;

class SaveListingHandler extends ContentHandlerBase
{
	protected $displayName = 'Save Listing';
	protected $moduleName = 'classifieds';
	protected $functionName = 'save_listing';

	public function respond()
	{
		if (!\App()->UserManager->isUserLoggedIn())
		{
			echo \App()->ModuleManager->executeFunction("users", "login");
		}
		else
		{
			$savedListings = \App()->ObjectMother->createSavedListings();
			$listing_id = isset($_REQUEST['listing_id']) ? $_REQUEST['listing_id'] : null;
	
			if (!is_null($listing_id))
			{
				$savedListings->saveListing($listing_id);
                \App()->SuccessMessages->addMessage('LISTING_SAVED');
            }

			throw new RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Request['returnBackUrl']);
		}
	}
}
