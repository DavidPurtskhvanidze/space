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

class AdminDashboardPaymentGatewayCountStatItem extends \modules\admin_dashboard\apps\AdminPanel\AbstractStatItem implements IAdminDashboardStatItem
{
	public static function getOrder()
	{
		return 100;
	}

	public function getTrClass()
	{
		return 'paymentGateways';
	}

	public function getCaption()
	{
		return 'Payment gateways';
	}

	public function getContent()
	{
		$this->templateProcessor->assign('paymentGatewaysCount', \App()->PaymentGatewayManager->getPaymentGatewaysCount());
		return $this->templateProcessor->fetch('payment^admin_dashboard_payment_gateway_count_stat_item.tpl');
	}
}
