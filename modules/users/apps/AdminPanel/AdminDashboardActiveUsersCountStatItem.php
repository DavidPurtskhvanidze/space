<?php
/**
 *
 *    Module: users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: users-7.5.0-1
 *    Tag: tags/7.5.0-1@19887, 2016-06-17 13:25:03
 *
 *    This file is part of the 'users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\users\apps\AdminPanel;

class AdminDashboardActiveUsersCountStatItem extends \modules\admin_dashboard\apps\AdminPanel\AbstractStatItem implements IAdminDashboardStatItem
{
	public static function getOrder()
	{
		return 200;
	}

	public function getTrClass()
	{
		return 'activeUsers';
	}

	public function getCaption()
	{
		return 'Active users';
	}

	public function getContent()
	{
		$this->templateProcessor->assign('activeUsersCount', \App()->UserManager->getActiveUsersCount());
		return $this->templateProcessor->fetch('users^admin_dashboard_active_users_count_stat_item.tpl');
	}
}