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

class AddRemoveIpEditListingPageControl implements \modules\classifieds\apps\AdminPanel\IEditListingPageControl
{
	private $listingSid;
	private $returnBackUri;

	public function display()
	{
		$listingInfo = \App()->ListingManager->getListingInfoBySID($this->listingSid);
		if (is_null($listingInfo))
		{
			return;
		}
		$lastUserIp = !empty($listingInfo['last_user_ip']) ? $listingInfo['last_user_ip'] : false;

		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign('userinfo_ip_address', $lastUserIp);
		$templateProcessor->assign('userinfo_ip_blocked', \App()->IpRangeManager->isIpInBlockList($lastUserIp));
		$templateProcessor->assign('returnBackUri', $this->returnBackUri);
		$templateProcessor->display('ip_blocklist^add_remove_ip_listing_control.tpl');
	}

	public static function getOrder()
	{
		return 100;
	}

	public function setListingSid($listingSid)
	{
		$this->listingSid = $listingSid;
	}

	public function setReturnBackUri($returnBackUri)
	{
		$this->returnBackUri = $returnBackUri;
	}
}
