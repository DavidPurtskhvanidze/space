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


namespace modules\listing_repost\apps\AdminPanel\scripts;

class DisplayAddPublicationArticleSettingsHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $displayName = 'Display publication article repost settings in Add a New Article Page';
	protected $moduleName = 'listing_repost';
	protected $functionName = 'display_add_publication_article_settings';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();

		$templateProcessor->assign('facebookRepostStatus', \App()->UserSocialNetworkAccessDataManager->getListingRepostStatusForUser(0, 'Facebook'));
		$templateProcessor->assign('twitterRepostStatus', \App()->UserSocialNetworkAccessDataManager->getListingRepostStatusForUser(0, 'Twitter'));
		$templateProcessor->assign('doNotRepostToFacebook', isset($_REQUEST['doNotRepostToFacebook']));
		$templateProcessor->assign('doNotRepostToTwitter', isset($_REQUEST['doNotRepostToTwitter']));

		$templateProcessor->display('add_publication_article_settings.tpl');
	}
}
