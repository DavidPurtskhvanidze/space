<?php
/**
 *
 *    Module: listing_feature_youtube v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_feature_youtube-7.5.0-1
 *    Tag: tags/7.5.0-1@19793, 2016-06-17 13:19:51
 *
 *    This file is part of the 'listing_feature_youtube' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_feature_youtube\apps\FrontEnd\scripts;

use apps\FrontEnd\ContentHandlerBase;

class ShowYoutubeVideoHandler extends ContentHandlerBase
{
	protected $displayName = 'Display Youtube';
	protected $moduleName = 'listing_feature_youtube';
	protected $functionName = 'show_youtube_video';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign('videoId', \App()->Request['videoId']);
		$templateProcessor->assign('width', \App()->Request['width']);
		$templateProcessor->assign('height', \App()->Request['height']);
		$templateProcessor->display("youtube_video.tpl");
	}
}
