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

class AdminDashboardUsersWithListingsCountStatItem extends \modules\admin_dashboard\apps\AdminPanel\AbstractStatItem implements \modules\users\apps\AdminPanel\IAdminDashboardStatItem
{
	public static function getOrder()
	{
		return 300;
	}

	public function getTrClass()
	{
		return 'usersWithListings';
	}

	public function getCaption()
	{
		return 'Users with listings';
	}

	public function getContent()
	{
		$this->templateProcessor->assign('usersWithListingsCount', \App()->UserManager->getUsersWithListingsCount());
		return $this->templateProcessor->fetch('classifieds^admin_dashboard_users_with_listings_count_stat_item.tpl');
	}
}
