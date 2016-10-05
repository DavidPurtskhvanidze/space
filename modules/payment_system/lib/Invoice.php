<?php
/**
 *
 *    Module: payment_system v.7.4.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: payment_system-7.4.0-1
 *    Tag: tags/7.4.0-1@19076, 2015-12-14 12:49:32
 *
 *    This file is part of the 'payment_system' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\payment_system\lib;

class Invoice extends \lib\ORM\Object
{
	const STATUS_UNPAID = 'Unpaid';
	const STATUS_PAID = 'Paid';

	protected $detailsInfo = array
	(
		array
		(
			'id' => 'user_sid',
			'caption' => 'User SID',
			'type' => 'integer',
			'is_required' => true,
		),
		array
		(
			'id' => 'amount',
			'caption' => 'Amount',
			'type' => 'transaction_money',
			'signs_num' => 2,
			'is_required' => true,
		),
		array
		(
			'id' => 'product_id',
			'caption' => 'Product Id',
			'type' => 'string',
			'length' => '255',
			'is_required' => true,
		),
		array
		(
			'id' => 'product_description',
			'caption' => 'Product Description',
			'type' => 'text',
			'is_required' => false,
		),
		array
		(
			'id' => 'product_info',
			'caption' => 'Product Info',
			'type' => 'array',
			'is_required' => false,
		),
		array
		(
			'id' => 'product_info_template',
			'caption' => 'Product Info Template',
			'type' => 'string',
			'is_required' => false,
		),
		array
		(
			'id' => 'payment_method_class_name',
			'caption' => 'Payment Method Class Name',
			'type' => 'string',
			'length' => 255,
			'is_required' => false,
		),
		array
		(
			'id' => 'transaction_sid',
			'caption' => 'Transaction SID',
			'type' => 'integer',
			'is_required' => false,
		),
		array
		(
			'id' => 'status',
			'caption' => 'Status',
			'type' => 'list',
			'list_values' => array
			(
				array
				(
					'id' => self::STATUS_UNPAID,
					'caption' => 'Unpaid',
				),
				array
				(
					'id' => self::STATUS_PAID,
					'caption' => 'Paid',
				),
			),
			'value' => self::STATUS_UNPAID,
			'is_required' => true,
		),
		array
		(
			'id' => 'creation_date',
			'caption' => 'Created',
			'type' => 'date',
			'is_required' => true,
		),
		array
		(
			'id' => 'last_updated',
			'caption' => 'Updated',
			'type' => 'date',
			'is_required' => false,
		),
		array
		(
			'id' => 'payment_queued_action',
			'caption' => 'Payment Queued Action Object',
			'type' => 'object',
			'is_required' => false,
		),
		array
		(
			'id' => 'success_action',
			'caption' => 'Success Action Object',
			'type' => 'object',
			'is_required' => false,
		),
		array
		(
			'id' => 'success_page_url',
			'caption' => 'Success Page URL',
			'type' => 'string',
			'length' => 255,
			'is_required' => false,
		),
		array
		(
			'id' => 'failure_action',
			'caption' => 'Failure Action Object',
			'type' => 'object',
			'is_required' => false,
		),
	);

	protected $tableName = 'payment_system_invoices';

	/**
	 * This method will be called when payment method registers invoice
	 */
	public function onPaymentQueued()
	{
		$action = $this->getPropertyValue('payment_queued_action');
		if (!is_null($action))
		{
			$action->perform();
		}
	}

	public function performSuccessAction()
	{
		if ($this->getStatus() == self::STATUS_PAID)
		{
			throw new \modules\payment_system\lib\Exception("Invoice was already processed before");
		}
		$successAction = $this->getPropertyValue('success_action');
		$successAction->data['invoice_sid'] = $this->getSid();
		$successAction->perform();
	}

	public function performFailureAction()
	{
		$action = $this->getPropertyValue('failure_action');
		if (!is_null($action))
		{
			$action->perform();
		}
	}

	public function redirectToSuccessPage()
	{
		$url = \App()->Navigator->addParamsForUrlAndGet($this->getPropertyValue('success_page_url'), array('invoice_sid' => $this->getSID()));
		throw new \lib\Http\RedirectException($url);
	}

	public function getInvoiceDetails($info)
	{
		$details = new \lib\ORM\ObjectDetails();
		$details->setDetailsInfo($this->detailsInfo);
		$details->setTableName($this->tableName);
		$details->setOrmObjectFactory(\App()->OrmObjectFactory);
		$details->buildPropertiesWithData($info);
		return $details;
	}

	public function defineDetails($info)
	{
		$this->setDetails($this->getInvoiceDetails($info));
	}

	public function getUserSid()
	{
		return $this->getPropertyValue('user_sid');
	}

	public function getProductInfo()
	{
		return $this->getPropertyValue('product_info');
	}

	public function getProductInfoTemplate()
	{
		return $this->getPropertyValue('product_info_template');
	}

	public function getAmount()
	{
		return $this->getPropertyValue('amount');
	}

	public function getDescription()
	{
		return $this->getPropertyValue('product_description');
	}

	public function getRequiredPaymentMethodClassName()
	{
		return $this->getPropertyValue('payment_method_class_name');
	}

	public function getStatus()
	{
		return $this->getPropertyValue('status');
	}

	public function getProductId()
	{
		return $this->getPropertyValue('product_id');
	}

	public function getTransactionSid()
	{
		return $this->getPropertyValue('transaction_sid');
	}
}
