<?php
/**
 *
 *    Module: listing_repost v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_repost-7.5.0-1
 *    Tag: tags/7.5.0-1@19795, 2016-06-17 13:19:57
 *
 *    This file is part of the 'listing_repost' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_repost\apps\FrontEnd\scripts;

class DisplayAddListingSettingsHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Display listing repost settings in Add Listing Page';
	protected $moduleName = 'listing_repost';
	protected $functionName = 'display_add_listing_settings';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$currentUserSid = \App()->UserManager->getCurrentUserSID();

		$templateProcessor->assign("twitterIsSetUp", \App()->UserSocialNetworkAccessDataManager->getTwitterSetupStatus());
		$templateProcessor->assign("twitterRepostStatus", \App()->UserSocialNetworkAccessDataManager->getListingRepostStatusForUser($currentUserSid, "Twitter"));
		$templateProcessor->assign("doNotRepostToTwitter", isset($_REQUEST['doNotRepostToTwitter']));

		$templateProcessor->display("add_listing_settings.tpl");
	}
}
