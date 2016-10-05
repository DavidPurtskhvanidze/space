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


namespace modules\listing_repost;
 
class SystemSettingPage implements \modules\miscellaneous\lib\ISocialNetworkSettings
{
	public function getContent()
	{
		$templateProcessor = \App()->getTemplateProcessor();

		if (\App()->UserSocialNetworkAccessDataManager->getFacebookSetupStatus())
		{
			$templateProcessor->assign("facebookIsSetUp", true);
			$templateProcessor->assign("facebookStatus", \App()->UserSocialNetworkAccessDataManager->getListingRepostStatusForUser(0, 'Facebook'));
		}
		if (\App()->UserSocialNetworkAccessDataManager->getTwitterSetupStatus())
		{
			$templateProcessor->assign("twitterIsSetUp", true);
			$templateProcessor->assign("twitterStatus", \App()->UserSocialNetworkAccessDataManager->getListingRepostStatusForUser(0, 'Twitter'));
		}

		$templateProcessor->assign('settings', \App()->SettingsFromDB->getSettings());
		return $templateProcessor->fetch('listing_repost^system_setting_page.tpl');
	}

	public function getOrder()
	{
		return 100;
	}
}
