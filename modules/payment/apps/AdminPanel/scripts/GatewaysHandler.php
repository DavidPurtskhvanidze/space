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


namespace modules\payment\apps\AdminPanel\scripts;

class GatewaysHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\payment\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'payment';
	protected $functionName = 'gateways';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign('gateways', \App()->PaymentGatewayManager->getPaymentGateways());
		$template_processor->display('payment_gateways_list.tpl');
	}

	public function getCaption()
	{
		return "Payment Gateways";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getSystemPageURL($this->moduleName, $this->functionName);
	}

	public function getHighlightUrls()
	{
		return array();
	}

	public static function getOrder()
	{
		return 100;
	}
}
