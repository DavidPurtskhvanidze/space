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


namespace modules\payment\lib\PaymentGateway;

class PaymentGatewayAdapterForORM extends \lib\ORM\Object
{
	/**
	 * @var \modules\payment\lib\PaymentGateway\IPaymentGateway
	 */
	private $paymentGateway;

	public function __construct($paymentGateway)
	{
		$this->paymentGateway = $paymentGateway;
		$this->setDetails($this->createDetails());
		$this->setSID($this->paymentGateway->getSid());
	}

	private function createDetails()
	{
		$details = new \lib\ORM\ObjectDetails();
		$details->setTableName("payment_gateways");
		$details->setDetailsInfo($this->paymentGateway->getDetailsInfo());
		$details->setOrmObjectFactory(\App()->OrmObjectFactory);
		$details->buildPropertiesWithData($this->paymentGateway->getInfo());
		return $details;
	}
}
