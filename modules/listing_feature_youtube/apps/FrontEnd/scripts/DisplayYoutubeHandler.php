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

class DisplayYoutubeHandler extends ContentHandlerBase
{
	protected $displayName = 'Display Youtube';
	protected $moduleName = 'listing_feature_youtube';
	protected $functionName = 'display_youtube';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$listing = \App()->Request['listing'];
		if (!is_null($listing) && $listing['feature_youtube']['exists'] && $listing['feature_youtube']['isTrue'] && !$listing['feature_youtube_video_id']['isEmpty'])
		{
            //Извлекаем  видео id
			preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#",
				(string)$listing['feature_youtube_video_id'],
				$matches);

			$video_id  = empty($matches) ? (string)$listing['feature_youtube_video_id'] : array_pop($matches);

			$templateProcessor->assign('videoId', $video_id);
			$templateProcessor->assign('width', \App()->Request['width']);
			$templateProcessor->assign('height', \App()->Request['height']);
			$templateProcessor->display("youtube_video.tpl");
		}
	}
}
