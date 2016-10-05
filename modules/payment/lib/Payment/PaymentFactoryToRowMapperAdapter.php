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

class PaymentFactoryToRowMapperAdapter
{
	private $paymentFactory;
	private $userManager;
	
	public function __construct($paymentFactory, $userManager)
	{
		$this->paymentFactory = $paymentFactory;
		$this->userManager = $userManager;
	}
	
	public function mapRowToObject($row)
	{
		$payment = $this->paymentFactory->createPayment($row);
		$payment->addProperty(
			array
			(
				'id' => 'username',
				'type' => 'string',
				'value' => $this->userManager->getUserNameByUserSID($payment->getPropertyValue('user_sid')),
			)
		);
		$payment->addProperty(
			array
			(
				'id' => 'isCompleted',
				'type' => 'boolean',
				'value' => $payment->getPropertyValue('status') == 'Completed',
			)
		);
		$payment->addProperty(
			array
			(
				'id' => 'isInProgress',
				'type' => 'boolean',
				'value' => $payment->getPropertyValue('status') == 'inProgress',
			)
		);
		$payment->addProperty(
			array
			(
				'id' => 'payment_gateway_caption',
				'type' => 'string',
				'value' => \App()->PaymentGatewayManager->getPaymentGatewayCaptionById($payment->getPaymentGatewayId()),
			)
		);
		
		return $payment;
	}
}
