<?php
/**
 *
 *    Module: payment_gateway_wire_transfer v.7.4.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: payment_gateway_wire_transfer-7.4.0-1
 *    Tag: tags/7.4.0-1@19075, 2015-12-14 12:49:30
 *
 *    This file is part of the 'payment_gateway_wire_transfer' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\payment_gateway_wire_transfer\apps\FrontEnd\scripts;

class PaymentPageHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Payments';
	protected $moduleName = 'payment_gateway_wire_transfer';
	protected $functionName = 'payment_page';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		if (isset($_REQUEST['payment_id']) && isset($_REQUEST['item_name']) && isset($_REQUEST['amount']))
		{
			$gateway = new \modules\payment_gateway_wire_transfer\lib\WireTransfer();
			$payment = \App()->PaymentManager->getObjectBySID($_REQUEST['payment_id']);
			$payment->setPaymentGatewayId($gateway->getId());
			\App()->PaymentManager->savePayment($payment);
			
			$template_processor->assign('payment_id', $_REQUEST['payment_id']);
			$template_processor->assign('item_name', $_REQUEST['item_name']);
			$template_processor->assign('amount', $_REQUEST['amount']);
			$template_processor->assign('user', \App()->UserManager->getCurrentUserInfo());
			$template_processor->display('wire_transfer.tpl');
		}
		else
		{
			$template_processor->assign('errors', array('PARAMETERS_MISSING'));
			$template_processor->display('miscellaneous^errors.tpl');
		}
	}
}
