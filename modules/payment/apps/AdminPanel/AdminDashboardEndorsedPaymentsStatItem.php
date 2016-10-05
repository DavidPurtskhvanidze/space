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

class AdminDashboardEndorsedPaymentsStatItem extends \modules\admin_dashboard\apps\AdminPanel\AbstractStatItem implements IAdminDashboardStatItem
{
	public static function getOrder()
	{
		return 300;
	}

	public function getTrClass()
	{
		return 'endorsedPayments';
	}

	public function getCaption()
	{
		return 'Endorsed payments';
	}

	public function getContent()
	{
		$this->templateProcessor->assign('endorsedPaymentsCount', \App()->PaymentManager->getEndorsedPaymentsCount());
		$this->templateProcessor->assign('endorsedPaymentsAmount', \App()->PaymentManager->getEndorsedPaymentsAmount());
		return $this->templateProcessor->fetch('payment^admin_dashboard_endorsed_payments_stat_item.tpl');
	}
}
