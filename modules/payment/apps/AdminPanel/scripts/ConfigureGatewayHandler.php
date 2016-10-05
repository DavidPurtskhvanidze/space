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

class ConfigureGatewayHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'payment';
	protected $functionName = 'configure_gateway';

	public function respond()
	{
		$gatewayClassname = \App()->Request['gatewayClassname'];
		if (!class_exists($gatewayClassname))
		{
			throw new \modules\payment\lib\PaymentGateway\PaymentGatewayNotFoundException($gatewayClassname);
		}

		$gateway = new $gatewayClassname;
		$gateway->updateInfo($_REQUEST);
		$gatewayAdapterForORM = new \modules\payment\lib\PaymentGateway\PaymentGatewayAdapterForORM($gateway);
		$gatewayForm = new \lib\Forms\Form($gatewayAdapterForORM);

		if (\App()->Request['action'] == 'save' && $gatewayForm->isDataValid())
		{
			\App()->SuccessMessages->addMessage("CHANGES_SAVED");
			\App()->PaymentGatewayManager->saveGateway($gatewayAdapterForORM);
		}

		$templateProcessor = \App()->getTemplateProcessor();
		$gatewayForm->registerTags($templateProcessor);
		$gatewayForm->makeDisabled('id');
		$gatewayForm->makeDisabled('caption');

		$templateProcessor->assign('gateway', $gateway);
		$templateProcessor->assign('form_fields', $gatewayForm->getFormFieldsInfo());
		$templateProcessor->display('configure_gateway.tpl');
		$gateway->displayAdditionalInfo();
	}
}
