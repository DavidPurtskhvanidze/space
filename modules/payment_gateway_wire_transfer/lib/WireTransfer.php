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


namespace modules\payment_gateway_wire_transfer\lib;

class WireTransfer extends \modules\payment\lib\PaymentGateway\PaymentGateway implements \apps\FrontEnd\IHaveTemplate
{
	protected $id = "wire_transfer";
	protected $caption = "Wire Transfer";
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
			$gateway['url'] = \App()->PageRoute->getSystemPageURL('payment_gateway_wire_transfer', 'payment_page');
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
		return 'modules\payment_gateway_wire_transfer\Module';
	}

	public function getTemplateName()
	{
		return 'wire_transfer.tpl';
	}
}
