<?php
/**
 *
 *    Module: recent_tweets v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: recent_tweets-7.3.0-1
 *    Tag: tags/7.3.0-1@18563, 2015-08-24 13:38:12
 *
 *    This file is part of the 'recent_tweets' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\recent_tweets;
 
class SystemSettingPage implements \modules\miscellaneous\lib\ISocialNetworkSettings
{
	public function getContent()
	{
		$twitterTimeLineManager = new \modules\recent_tweets\lib\TwitterTimelineManager();
		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign('settings', \App()->SettingsFromDB->getSettings());
		$templateProcessor->assign('twitterIsSetUp', $twitterTimeLineManager->isTwitterSetUp());
		return $templateProcessor->fetch('recent_tweets^system_setting_page.tpl');
	}

	public function getOrder()
	{
		return 200;
	}
}
