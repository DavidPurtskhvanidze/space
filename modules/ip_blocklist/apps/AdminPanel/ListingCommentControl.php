<?php
/**
 *
 *    Module: ip_blocklist v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: ip_blocklist-7.5.0-1
 *    Tag: tags/7.5.0-1@19789, 2016-06-17 13:19:41
 *
 *    This file is part of the 'ip_blocklist' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\ip_blocklist\apps\AdminPanel;

class ListingCommentControl implements \modules\listing_comments\apps\AdminPanel\IListingCommentControl
{
	private $commentSid;
	private $searchId;

	public function setCommentSid($commentSid)
	{
		$this->commentSid = $commentSid;
	}

	public function setSearchId($searchId)
	{
		$this->searchId = $searchId;
	}

	public static function getOrder()
	{
		return 100;
	}

	public function display()
	{
		$templateProcessor = \App()->getTemplateProcessor();

		$commentInfo = \App()->ListingCommentManager->getListingCommentInfoBySid($this->commentSid);
		$ip = $commentInfo['last_user_ip'];

		if (!empty($ip))
		{
			$templateProcessor->assign('userinfo_ip_address', $ip);
			$templateProcessor->assign('searchId', $this->searchId);
			$templateProcessor->assign('isIpBlocked', \App()->IpRangeManager->isIpInBlockList($ip));
			$templateProcessor->display('ip_blocklist^display_listing_comment_control.tpl');
		}
	}
}
