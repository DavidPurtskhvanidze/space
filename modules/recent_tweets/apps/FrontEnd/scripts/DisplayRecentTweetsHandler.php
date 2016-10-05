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


namespace modules\recent_tweets\apps\FrontEnd\scripts;

class DisplayRecentTweetsHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Recent Tweets';
	protected $moduleName = 'recent_tweets';
	protected $functionName = 'display_recent_tweets';
	protected $parameters = array('template', 'count');

	public function respond()
	{
		$template		= \App()->Request->getValueOrDefault('template', 'recent_tweets.tpl');
		$displayCount	= \App()->Request->getValueOrDefault('count', 3);
		$templateProcessor = \App()->getTemplateProcessor();
		
		$twitterTimeline = null;
		try
		{
			$twitterTimelineManager = new \modules\recent_tweets\lib\TwitterTimelineManager(true);
			$twitterTimeline = $twitterTimelineManager->getTimeline($displayCount);
		}
		catch (\modules\recent_tweets\lib\Exception $e)
		{
			$templateProcessor->assign('responseError', $e->getMessage());
		}
		$templateProcessor->assign('twitterTimeline', $twitterTimeline);
		$templateProcessor->assign('displayCount', $displayCount);
		$templateProcessor->display($template);
	}
}
