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


namespace modules\payment\apps\FrontEnd\scripts;

class PaymentPageHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Payment page';
	protected $moduleName = 'payment';
	protected $functionName = 'payment_page';

	public function respond()
	{
		$payment = App()->PaymentManager->getObjectBySID(\App()->Request['payment_sid']);
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign('gateways', \App()->PaymentManager->getPaymentForms($payment));
		$template_processor->assign('payment', \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($payment));
		$template_processor->display('payment_page.tpl');
	}
}
