<?php
/**
 *
 *    Module: payment v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: payment-7.5.0-1
 *    Tag: tags/7.5.0-1@19802, 2016-06-17 13:20:16
 *
 *    This file is part of the 'payment' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\payment\apps\AdminPanel;

class AdminDashboardPendingPaymentsStatItem extends \modules\admin_dashboard\apps\AdminPanel\AbstractStatItem implements IAdminDashboardStatItem
{
	public static function getOrder()
	{
		return 400;
	}

	public function getTrClass()
	{
		return 'pendingPayments';
	}

	public function getCaption()
	{
		return 'Pending payments';
	}

	public function getContent()
	{
		$this->templateProcessor->assign('pendingPaymentsCount', \App()->PaymentManager->getPendingPaymentsCount());
		$this->templateProcessor->assign('pendingPaymentsAmount', \App()->PaymentManager->getPendingPaymentsAmount());
		return $this->templateProcessor->fetch('payment^admin_dashboard_pending_payments_stat_item.tpl');
	}
}
