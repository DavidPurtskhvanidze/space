<?php
/**
 *
 *    Module: payment_gateway_authnet_sim v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: payment_gateway_authnet_sim-7.3.0-1
 *    Tag: tags/7.3.0-1@18550, 2015-08-24 13:37:32
 *
 *    This file is part of the 'payment_gateway_authnet_sim' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\payment_gateway_authnet_sim\lib;

class AuthNetSIM extends \modules\payment\lib\PaymentGateway\PaymentGateway
{
	protected $id = "authnet_sim";
	protected $caption = "Authorize.Net SIM";

    private function getUrl()
    {
	    if ($this->info['authnet_use_test_account'])
			return 'https://test.authorize.net/gateway/transact.dll';
		else
			return 'https://secure.authorize.net/gateway/transact.dll';
	}

	public function buildTransactionForm($payment)
	{
		$form_hidden_fields = "";
		foreach ($this->getFormFields($payment) as $name => $value) $form_hidden_fields .= "<input type='hidden' name='{$name}' value='{$value}' />\r\n";
		$gateway['hidden_fields'] 	= $form_hidden_fields;
		$gateway['url'] 			= $this->getUrl();
		$gateway['caption']			= 'Authorize.Net';
		return $gateway;
	}

	/**
	 * @param \modules\payment\lib\Payment\Payment $payment
	 * @return array
	 */
	private function getFormFields($payment)
	{
		$form_fields = array();
        $x_fp_sequence = rand(1, 1000);
		$x_fp_timestamp = time();
		$fingerprint = $this->hmac
		(
			$this->info['authnet_api_transaction_key'],
			$this->info['authnet_api_login_id'] . "^". $x_fp_sequence . "^" . $x_fp_timestamp . "^" . $payment->getAmount() . "^" . $this->info['currency_code']
		);

		$id = $this->getId();
		// hard-coded fields
		$form_fields['x_show_form'] 		= 'PAYMENT_FORM';
		// configuration fields
		$form_fields['x_login'] 			= $this->info['authnet_api_login_id'];
		$form_fields['x_fp_hash'] 			= $fingerprint;
		$form_fields['x_fp_sequence'] 		= $x_fp_sequence;
		$form_fields['x_fp_timestamp'] 		= $x_fp_timestamp;
		$form_fields['x_currency_code'] 	= $this->info['currency_code'];
		$form_fields['x_receipt_link_url']	= \App()->PageRoute->getSystemPageURL('payment', 'callback') . "{$id}/"; // return page field (response)
		$form_fields['x_description'] 		= $payment->getProductDescriptionForPaymentGateway();
		$form_fields['x_amount'] 			= $payment->getAmount();
		$form_fields['item_number'] 		= $payment->getSID();
		return $form_fields;
	}

    private function hmac($key, $data)
    {
	   $b = 64; // byte length for md5
	   if (strlen($key) > $b) $key = pack("H*",md5($key));
	   $key  = str_pad($key, $b, chr(0x00));
	   $ipad = str_pad('', $b, chr(0x36));
	   $opad = str_pad('', $b, chr(0x5c));
	   $k_ipad = $key ^ $ipad ;
	   $k_opad = $key ^ $opad;
	   return md5($k_opad . pack("H*",md5($k_ipad . $data)));
	}

    private function isPaymentVerified(&$payment)
	{
		$callback_data 	= $payment->getCallbackData();

		$local_md5_hash = md5
		(
			$this->info['authnet_api_md5_hash_value'] .
			$this->info['authnet_api_login_id'] .
			$callback_data['x_trans_id'] .
			$callback_data['x_amount']
		);

		// checking if response from Autnorize.Net
		if ( strtoupper($callback_data['x_MD5_Hash']) != strtoupper($local_md5_hash) ) return false;

		// verifying if transaction has been approved
		if ($callback_data['x_response_code'] != 1 || $callback_data['x_response_reason_code'] != 1) return false;

		return true;
	}

    public function getPaymentFromCallbackData($callback_data)
    {
		$payment_sid = isset($callback_data['item_number']) ? $callback_data['item_number'] : null;

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

		if ( $payment->getStatus() != \modules\payment\lib\Payment\Payment::STATUS_PENDING )
		{
			$this->errors['PAYMENT_IS_NOT_PENDING'] = $payment->getStatus();
			return null;
		}

		$payment->setCallbackData($callback_data);

		if ($this->isPaymentVerified($payment))
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

	public function getDetailsInfo()
	{
		$common_details = parent::getDetailsInfo();

		$specific_details = array
		(
			array
			(
				'id' => 'authnet_api_login_id',
				'caption' => 'API Login ID',
				'type' => 'string',
				'length' => '20',
				'is_required' => true,
			),
			array
			(
				'id' => 'authnet_api_transaction_key',
				'caption' => 'Transaction Key',
				'type' => 'string',
				'length' => '20',
				'is_required' => true,
			),
			array
			(
				'id' => 'authnet_api_md5_hash_value',
				'caption' => 'MD5-Hash',
				'type' => 'string',
				'length' => '20',
				'is_required' => true,
			),
			array
			(
				'id' => 'currency_code',
				'caption' => 'Currency Code',
				'type' => 'string',
				'length' => '20',
				'is_required' => true,
			),
			array
			(
				'id' => 'authnet_use_test_account',
				'caption' => 'Authorize.Net test account <br /> <small>check to enable test account</small>',
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
			'Invoice ID' => @$callbackData['x_trans_id'],
		);
	}
}
