<?php
/**
 *
 *    Module: payment_gateway_paypal_standard v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: payment_gateway_paypal_standard-7.3.0-1
 *    Tag: tags/7.3.0-1@18552, 2015-08-24 13:37:38
 *
 *    This file is part of the 'payment_gateway_paypal_standard' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\payment_gateway_paypal_standard\lib;

class PayPal extends \modules\payment\lib\PaymentGateway\PaymentGateway
{
	protected $id = "paypal_standard";
	protected $caption = "PayPal Standard";

	const PAYPAL_SANDBOX_URL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	const PAYPAL_PRODUCTION_URL = 'https://www.paypal.com/cgi-bin/webscr';

	private function getUrl()
	{
		if ($this->info['use_sandbox'])
			return self::PAYPAL_SANDBOX_URL;
		else
			return self::PAYPAL_PRODUCTION_URL;
	}

    public function buildTransactionForm($payment)
    {
		if( $payment->isValid() )
		{
			$form_fields = $this->getFormFields($payment);
			$paypal_url = $this->getUrl();
            $form_hidden_fields = "";
            foreach ($form_fields as $name => $value) $form_hidden_fields .= "<input type='hidden' name='{$name}' value='{$value}' />\r\n";
           	$gateway['hidden_fields'] 	= $form_hidden_fields;
           	$gateway['url'] 			= $paypal_url;
           	$gateway['caption']			= 'PayPal';
			return $gateway;
		}
		else
			return null;
	}

	/**
	 * @param \modules\payment\lib\Payment\Payment $payment
	 * @return array
	 */
	private function getFormFields($payment)
	{
		$form_fields = array();
		$id = $this->getId();

		// hard-coded fields
		$form_fields['cmd'] 			= '_xclick';
		$form_fields['return'] 			= \App()->PageRoute->getSystemPageURL('payment', 'callback') . "{$id}/?payment_sid=" . $payment->getSid();
		$form_fields['notify_url'] 		= \App()->PageRoute->getSystemPageURL('payment', 'callback') . "{$id}/ipn";
		$form_fields['cancel_return'] 	= \App()->PageRoute->getSystemPageURL('payment', 'callback') . "{$id}/cancel?payment_sid=" . $payment->getSid();
		$form_fields['rm'] 				= 2; // 0 - GET method, 2 - POST method for call back

		// configuration fields
		$form_fields['business'] 		= $this->info['paypal_account_email'];
		$form_fields['currency_code'] 	= $this->info['currency_code'];

		// payment-related fields
		$form_fields['item_name'] 		= $payment->getProductDescriptionForPaymentGateway();
		$form_fields['amount'] 			= $payment->getAmount();
		$form_fields['item_number'] 	= $payment->getSID();

		return $form_fields;
	}

    public function getPaymentFromCallbackData($callback_data)
    {
	    $pathParts = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
	    $x =  array_pop($pathParts);
		if ($x == 'ipn')
		{
			header("Content-Type: text/plain");
			$this->validateAndCompletePaymentInIpnMode($callback_data);
			if ( !empty($this->errors))
			{
				echo "Errors = ";
				print_r($this->errors);
			}
			else
			{
				echo "OK\nPayment completed";
			}
			exit(0);
		}
	    elseif($x == 'cancel')
	    {
		    $this->errors['CANCELED'] = true;
		    return null;
	    }
		return $this->retrieveAndDisplayPaymentInformation($callback_data);
	}

	private function retrieveAndDisplayPaymentInformation($callbackData)
	{
		$verify = new PayPalDataVerificator($this->getUrl());

		if ( !$verify->dataIsVerified()) // We must confirm data is sent from paypal
		{
			$this->errors['CALLBACK_DATA_IS_INVALID'] = true;
			return null;
		}

		$paymentSid = null;
		foreach (array('item_number', 'item_number1') as $var)
		{
			if ( isset($callbackData[$var]) &&  $callbackData[$var] > 0 )
			{
				$paymentSid = intval($callbackData[$var]);
				break;
			}
		}

		if ( is_null($paymentSid) )
		{
			$this->errors['PAYMENT_ID_IS_NOT_SET'] = true;
			return null;
		}

		/** @var $payment Payment */
		$payment = \App()->PaymentManager->getObjectBySID($paymentSid);

		if (is_null($payment))
		{
			$this->errors['NONEXISTENT_PAYMENT_ID_SPECIFIED'] = 1;
			return null;
		}

		if ($payment->getStatus() == \modules\payment\lib\Payment\Payment::STATUS_COMPLETED )
		{
			return $payment;
		}

		if ($payment->getStatus() == \modules\payment\lib\Payment\Payment::STATUS_FAILED )
		{
			$this->errors['TRANSACTION_FAILED'] = 1;
			return null;
		}

		$cd = $payment->getCallbackData();
		if ( empty($cd) )
		{
			//значить IPN еще не у�?пел отправить. и его �?тату�? должен быть в проце�?�?е
			$payment->setStatusInProgress();
		}

		if ( $payment->getStatus() == \modules\payment\lib\Payment\Payment::STATUS_PENDING && isset($cd['pending_reason']) )
		{
			$this->errors['PAYPAL_PAYMENT_IS_STILL_PENDING'] = $cd['pending_reason'];
			return null;
		}

		return $payment;
	}

	private function validateAndCompletePaymentInIpnMode($callbackData)
	{

		$verify = new PayPalDataVerificator($this->getUrl());
		if ( !$verify->dataIsVerified()) // We must confirm data is sent from paypal
		{
			$this->errors['CALLBACK_DATA_IS_INVALID'] = true;
			return false;
		}

		// У на�? был �?лучай, когд аккаунт получател�? не был верифицирован в �?и�?теме Paypal. В �?том �?лучае в callbackData не приходил параметр "business".
		// В подобных �?луча�?х надо верифицировать получател�? по параметру "receiver_email"
		$paymentReceiverEmail = isset($callbackData['business']) ? $callbackData['business'] : $callbackData['receiver_email'];
		if ($paymentReceiverEmail !=  trim($this->info['paypal_account_email'])) // We need to make sure transaction was made in our account
		{
			$this->errors['INVALID_TRANSACTION'] = true;
			return null;
		}
		
		$paymentSid = null;
		foreach (array('item_number', 'item_number1') as $var)
		{
			if ( isset($callbackData[$var]) &&  $callbackData[$var] > 0 )
			{
				$paymentSid = intval($callbackData[$var]);
				break;
			}
		}

		if ( is_null($paymentSid) )
		{
			$this->errors['PAYMENT_ID_IS_NOT_SET'] = true;
			return null;
		}

		\App()->CacheManager->updateData('paypal', 'validateAndCompletePaymentInIpnMode', $paymentSid, 60);

		/** @var $payment Payment */
		$payment = \App()->PaymentManager->getObjectBySID($paymentSid);

		if (is_null($payment))
		{
			$this->errors['NONEXISTENT_PAYMENT_ID_SPECIFIED'] = 1;
			return null;
		}

		if ( $payment->getStatus() != \modules\payment\lib\Payment\Payment::STATUS_PENDING
		  && $payment->getStatus() != \modules\payment\lib\Payment\Payment::STATUS_IN_PROGRESS)
		{
			$this->errors['PAYMENT_IS_NOT_PENDING'] = $payment->getStatus();
			return null;
		}

		$payment->setCallbackData($callbackData);

		if ( $payment->getAmount() != floatval($callbackData['mc_gross']) )
		{
			$this->errors['PAYMENT_AMOUNT_MISMATCH'] = array('actual' => $callbackData['mc_gross'], 'expected' => $payment->getAmount());
			return null;
		}

		if ( in_array($callbackData['payment_status'], array('Completed', 'Processed') ) )
		{
			$payment->complete();
		}
		elseif ( in_array($callbackData['payment_status'], array('Failed', 'Denied', 'Reversed') ) )
		{
			$payment->fail();
		}

		\App()->PaymentManager->savePayment($payment);

		$this->afterSaveActionIPN($payment);
	}

	private function afterSaveActionIPN($payment)
	{
		$payment_status = $payment->getStatus();

		if ($payment_status == \modules\payment\lib\Payment\Payment::STATUS_COMPLETED)
		{
			\App()->PaymentTerminal->onPaymentEndorsed($payment->getInvoiceSid(), __CLASS__, $payment->getSID());
		}
	}

	public function getDetailsInfo()
	{
		$common_details = parent::getDetailsInfo();
		$specific_details = array
		(
			array
			(
				'id' => 'paypal_account_email',
				'caption' => 'PayPal account email',
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
				'id' => 'use_sandbox',
				'caption' => 'PayPal Sandbox <br /> <small>check to enable PayPal Sandbox</small>',
				'type' => 'boolean',
				'is_required' => false,
			),
		);
		return array_merge($common_details, $specific_details);
	}

	public function displayAdditionalInfo()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->display('payment_gateway_paypal_standard^paypal_hint.tpl');
	}
	
	public function getUserFriendlyTransactionDataFromCallBackData($callbackData)
	{
		return array
		(
			'Transaction ID' => @$callbackData['txn_id'],
			'Customer ID' => @$callbackData['payer_id'],
		);
	}
}

