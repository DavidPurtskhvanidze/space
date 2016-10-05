<?php
/**
 *
 *    Module: payment_gateway_cash_payment v.7.4.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: payment_gateway_cash_payment-7.4.0-1
 *    Tag: tags/7.4.0-1@19074, 2015-12-14 12:49:27
 *
 *    This file is part of the 'payment_gateway_cash_payment' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\payment_gateway_cash_payment\lib;

class CashPayment extends \modules\payment\lib\PaymentGateway\PaymentGateway implements \apps\FrontEnd\IHaveTemplate
{
	protected $id = "cash_gateway";
	protected $caption = "Cash Payment";
	protected $hasDataToStore = false;

	public function buildTransactionForm($payment)
	{
		if ($payment->isValid())
		{
			$form_fields = $this->getFormFields($payment);
			$form_hidden_fields = "";
			foreach ($form_fields as $name => $value)
			{
				$form_hidden_fields .= "<input type='hidden' name='{$name}' value='{$value}' />\r\n";
			}
			$gateway['hidden_fields'] = $form_hidden_fields;
			$gateway['url'] = \App()->PageRoute->getSystemPageURL('payment_gateway_cash_payment', 'payment_page');
			$gateway['caption'] = $this->getCaption();
			return $gateway;
		}
		else
			return null;
	}

	private function getFormFields($payment)
	{
		$product_info = $payment->getProductInfo();
		$form_fields = [
			'payment_id' => $payment->getSID(),
			'item_name' => $product_info['name'],
			'amount' => $product_info['price'],
		];
		return $form_fields;
	}

	public function getPaymentFromCallbackData($callback_data)
	{
		return null;
	}

	public function getModuleTemplateProviderId()
	{
		return 'modules\payment_gateway_cash_payment\Module';
	}

	public function getTemplateName()
	{
		return 'cash_gateway.tpl';
	}
}
