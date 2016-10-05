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

class Payment extends \lib\ORM\Object
{
	const STATUS_COMPLETED = 'Completed';
	const STATUS_FAILED = 'Failed';
	const STATUS_PENDING = 'Pending';
	const STATUS_IN_PROGRESS = 'inProgress';

	public function __construct($payment_info = array())
	{
		$this->details = new PaymentDetails($payment_info);
		$this->setPropertyValue('status', Payment::STATUS_PENDING);
	}

	// todo: get rid of this method
	public function getProductInfo()
	{
		$info = unserialize($this->getPropertyValue('product_info'));
		$info['name'] = $this->getPropertyValue('description');
		$info['price'] = $this->getPropertyValue('amount');
		return $info;
	}

	public function getStatus()
	{
		return $this->getPropertyValue('status');
	}

	public function setStatusInProgress()
	{
		return $this->setPropertyValue('status', Payment::STATUS_IN_PROGRESS);
	}

	public function getAmount()
	{
		return $this->getPropertyValue('amount');
	}

	public function getUserSID()
	{
		return $this->getPropertyValue('user_sid');
	}

	public function getCallbackData()
	{
		return unserialize($this->getPropertyValue('callback_data'));
	}

	public function setCallbackData($callback_data)
	{
		$this->setPropertyValue('callback_data', serialize($callback_data));
	}

	public function restart()
	{
		$this->setPropertyValue('status', self::STATUS_PENDING);
	}

	public function complete()
	{
		if ($this->getStatus() == self::STATUS_COMPLETED) throw new \Exception("Complete() called for payment " . $this->getSid() . ", which is already complete");
		$this->setPropertyValue('status', self::STATUS_COMPLETED);
	}

	public function fail()
	{
		if ($this->getStatus() == self::STATUS_COMPLETED) throw new \Exception("fail() called for payment " . $this->getSid() . ", which is complete");
		if ($this->getStatus() == self::STATUS_FAILED) throw new \Exception("fail() called for payment " . $this->getSid() . ", which is already failed");
		$this->setPropertyValue('status', self::STATUS_FAILED);
	}

	public function isValid()
	{
		$errors = array();
		$properties = $this->details->getProperties();
		$product_info = $this->getProductInfo();
		if (!isset($product_info['price']) || empty($product_info['price'])) array_push($errors, array('PRODUCT_PRICE_IS_NOT_SET', 1));
		if (!isset($product_info['name']) || empty($product_info['name'])) array_push($errors, array('PRODUCT_NAME_IS_NOT_SET', 1));

		if (empty($errors))
		{
			return true;
		}
		else
		{
			$this->errors = array_merge($this->errors, $errors);
			return false;
		}
	}

	public function getInvoiceSid()
	{
		return $this->getPropertyValue('invoice_sid');
	}

	public function setPaymentGatewayId($id)
	{
		$this->setPropertyValue('payment_gateway_id', $id);
	}

	public function getPaymentGatewayId()
	{
		return $this->getPropertyValue('payment_gateway_id');
	}

	public function getProductDescriptionForPaymentGateway()
	{
		// Payment gateways have limitation for the description length. 127 is the smallest one.
		$description = sprintf("%s: %s", \App()->SystemSettings['SiteUrl'], $this->getPropertyValue('description'));
		$description = (strlen($description) > 127) ? substr($description, 0, 124) . '...' : $description;
		return $description;
	}
}
