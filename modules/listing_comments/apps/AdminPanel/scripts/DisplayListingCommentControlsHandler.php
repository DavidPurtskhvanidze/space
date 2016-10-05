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


namespace modules\listing_comments\apps\AdminPanel\scripts;

class DisplayListingCommentControlsHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'listing_comments';
	protected $functionName = 'display_listing_comment_controls';

	public function respond()
	{
		$actions = new \core\ExtensionPoint('modules\listing_comments\apps\AdminPanel\IListingCommentControl');
		foreach ($actions as $action)
		{
			$action->setCommentSid(\App()->Request['commentSid']);
			$action->setSearchId(\App()->Request['searchId']);
			$action->display();
		}
	}
}
