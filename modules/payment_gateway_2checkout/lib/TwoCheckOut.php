<?php
/**
 *
 *    Module: payment_gateway_2checkout v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: payment_gateway_2checkout-7.3.0-1
 *    Tag: tags/7.3.0-1@18549, 2015-08-24 13:37:29
 *
 *    This file is part of the 'payment_gateway_2checkout' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\payment_gateway_2checkout\lib;

class TwoCheckOut extends \modules\payment\lib\PaymentGateway\PaymentGateway
{
	protected $id = "2checkout";
	protected $caption = "2Checkout";

	private function getUrl()
	{
		return 'https://www.2checkout.com/2co/buyer/purchase';
	}

	public function buildTransactionForm($payment)
	{
		if ($payment->isValid())
		{
			$form_hidden_fields = "";
			foreach ($this->getFormFields($payment) as $name => $value) $form_hidden_fields .= "<input type='hidden' name='{$name}' value='{$value}' />\r\n";
			$gateway['hidden_fields'] = $form_hidden_fields;
			$gateway['url'] = $this->getUrl();
			$gateway['caption'] = '2Checkout';
			return $gateway;
		}
		return null;
	}

	/**
	 * @param \modules\payment\lib\Payment\Payment $payment
	 * @return array
	 */
	private function getFormFields($payment)
	{
		$form_fields = array();
		$form_fields['id_type'] = 1;
		$form_fields['x_login'] = $this->info['2co_account_id'];
		$form_fields['x_amount'] = sprintf("%.02F", $payment->getAmount());
		$form_fields['x_invoice_num'] = $payment->getSID();
		$form_fields['c_prod'] = $payment->getSID();
		$form_fields['merchant_order_id'] = $payment->getSID();
		$form_fields['c_name'] = $payment->getPropertyValue('description');
		$form_fields['c_description'] = $payment->getProductDescriptionForPaymentGateway();
		$form_fields['c_price'] = sprintf("%.02F", $payment->getAmount());
		$form_fields['c_tangible'] = 'N';
		$form_fields['demo'] = $this->inDemoMode() ? 'Y' : 'N';
		$form_fields['x_receipt_link_url'] = \App()->PageRoute->getSystemPageURL('payment', 'callback') . $this->getId() . '/';
		return $form_fields;
	}

	public function getPaymentFromCallbackData($callbackData)
	{
		$payment_sid = isset($callbackData['x_invoice_num']) && ($callbackData['x_invoice_num'] > 0) ? $callbackData['x_invoice_num'] : null;

		if (is_null($payment_sid))
		{
			$this->errors['PAYMENT_ID_IS_NOT_SET'] = 1;
			return null;
		}

		$payment = \App()->PaymentManager->getObjectBySID($payment_sid);

		if (is_null($payment))
		{
			$this->errors['NONEXISTENT_PAYMENT_ID_SPECIFIED'] = 1;
			return null;
		}

		if ($payment->getStatus() != \modules\payment\lib\Payment\Payment::STATUS_PENDING)
		{
			$this->errors['PAYMENT_IS_NOT_PENDING'] = $payment->getStatus();
			return null;
		}

		$payment->setCallbackData($callbackData);
		\App()->PaymentManager->savePayment($payment);

		if ($callbackData['x_MD5_Hash'] != $this->getMD5key($payment, $callbackData['order_number']))
		{
			$this->errors['NOT_VERIFIED'] = 1;
			return null;
		}

		if ($callbackData['x_2checked'] == 'Y')
		{
			$payment->complete();
		}
		else
		{
			$payment->fail();
		}
		\App()->PaymentManager->savePayment($payment);
		return $payment;
	}

	private function getMD5key($payment, $order_number)
	{
		$secret_word = $this->info['secret_word'];
		$vendor_number = $this->info['2co_account_id'];
		$total = sprintf("%.02F", $payment->getAmount());
		if ($this->inDemoMode()) $order_number = 1;
		$theHash = strtoupper(md5($secret_word . $vendor_number . $order_number . $total));
		return $theHash;
	}

	private function inDemoMode()
	{
		if ($this->info['demo'] > 0) return true;
		return false;
	}

	public function getDetailsInfo()
	{
		$common_details = parent::getDetailsInfo();

		$specific_details = array
		(
			array
			(
				'id' => '2co_account_id',
				'caption' => '2Checkout vendor ID',
				'type' => 'string',
				'length' => '20',
				'is_required' => true,
			),
			array
			(
				'id' => 'secret_word',
				'caption' => '2Checkout secret word',
				'type' => 'string',
				'length' => '20',
				'is_required' => true,
			),
			array
			(
				'id' => 'demo',
				'caption' => 'Demo mode <br> <small>check to enable Demo mode</small>',
				'type' => 'boolean',
				'is_required' => false,
			),
		);
		return array_merge($common_details, $specific_details);
	}
	
	public function getUserFriendlyTransactionDataFromCallBackData($callbackData)
	{
		return array
		(
			'Invoice ID' => @$callbackData['invoice_id'],
			'Sale ID' => @$callbackData['sale_id'],
		);
	}
}
