<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\apps\AdminPanel;

class AdminDashboardActiveListingsCountStatItem extends \modules\admin_dashboard\apps\AdminPanel\AbstractStatItem implements IAdminDashboardStatItem
{
	public static function getOrder()
	{
		return 200;
	}

	public function getTrClass()
	{
		return 'activeListings';
	}

	public function getCaption()
	{
		return 'Active listings';
	}

	public function getContent()
	{
		$this->templateProcessor->assign('activeListingsCount', \App()->ListingManager->getActiveListingsCount());
		return $this->templateProcessor->fetch('classifieds^admin_dashboard_active_listings_count_stat_item.tpl');
	}
}
