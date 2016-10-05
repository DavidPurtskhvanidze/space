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


namespace modules\payment\lib\Payment;

class PaymentFactory implements \core\IService
{
	public function init(){}
	/**
	 * This method created payment from database row. Do not use this to create a new Payment
	 * @param  $payment_info
	 * @return Payment
	 */
	function createPayment($payment_info)
	{
		$payment = new Payment();
		$payment->setDetails($this->getPaymentDetails($payment_info));
		if (isset($payment_info['sid'])) $payment->setSid($payment_info['sid']);
		return $payment;
	}
	
	private function getPaymentDetails($info)
	{
		$details = new PaymentDetails();
		$details->setOrmObjectFactory(\App()->OrmObjectFactory);
		$details->buildPropertiesWithData($info);
		return $details;
	}
}

