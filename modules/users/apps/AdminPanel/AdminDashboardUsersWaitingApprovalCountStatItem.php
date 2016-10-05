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

class AdminDashboardUsersWaitingApprovalCountStatItem extends \modules\admin_dashboard\apps\AdminPanel\AbstractStatItem implements IAdminDashboardStatItem
{
	public static function getOrder()
	{
		return 400;
	}

	public function getTrClass()
	{
		return 'usersWaitingApproval';
	}

	public function getCaption()
	{
		return 'Users awaiting approval';
	}

	public function getContent()
	{
		$this->templateProcessor->assign('usersWaitingApprovalCount', \App()->UserManager->getUsersWaitingApprovalCount());
		return $this->templateProcessor->fetch('users^admin_dashboard_users_waiting_approval_count_stat_item.tpl');
	}
}
