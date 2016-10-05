<?php
/**
 *
 *    Module: listing_comments v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_comments-7.5.0-1
 *    Tag: tags/7.5.0-1@19790, 2016-06-17 13:19:43
 *
 *    This file is part of the 'listing_comments' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_comments\apps\MobileFrontEnd\scripts;

class DisplayCommentControlHandler extends \apps\MobileFrontEnd\ContentHandlerBase
{
	protected $displayName	= 'Display Comment Control';
	protected $moduleName	= 'listing_comments';
	protected $functionName	= 'display_comment_control';

	public function respond()
	{
		$commentControlTemplate = 'display_comment_controll.tpl';
		$listing		= \App()->Request['listing'];
		$controll		= \App()->Request['controll'];
		$returnBackUri	= \App()->Request['returnBackUri'];
		$wrapperTemplate= \App()->Request['wrapperTemplate'];
		if (!is_null($listing))
		{
			$templateProcessor = \App()->getTemplateProcessor();
			$templateProcessor->assign('listing', $listing);
			$templateProcessor->assign('controll', $controll);
			$templateProcessor->assign('returnBackUri', $returnBackUri);
			if (!is_null($wrapperTemplate))
			{
				$templateProcessor->assign('commentControlTemplate', $commentControlTemplate);
				$templateProcessor->display($wrapperTemplate);
			}
			else
			{
				$templateProcessor->display($commentControlTemplate);
			}
		}
	}
}
