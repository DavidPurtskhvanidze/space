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

class InvoiceManager extends \lib\ORM\ObjectManager implements \core\IService
{
	protected $tableName = 'payment_system_invoices';

	public function getObjectBySid($invoiceSid)
	{
		$info = $this->getObjectInfoBySID($this->tableName, $invoiceSid);
		if (is_null($info)) return null;
		return $this->createInvoice($info);
	}

	public function createNewInvoice($info)
	{
		$invoice = new Invoice();
		$invoice->defineDetails($info);
		$this->saveObject($invoice);
		return $invoice;
	}

	public function createInvoice($info)
	{
		$invoice = new Invoice();
		$invoice->defineDetails($info);
		if (!empty($info['sid']))
		{
			$invoice->setSID($info['sid']);
		}
		return $invoice;
	}

	/**
	 * @param Invoice $invoice
	 */
	public function markInvoiceAsPaid($invoice)
	{
		$invoice->setPropertyValue('status', Invoice::STATUS_PAID);
		$this->saveObject($invoice);
	}

	/**
	 * @param Invoice $invoice
	 * @param string $paymentMethodClassName
	 * @param int $transactionSid
	 */
	public function setPaymentDetailsForInvoice($invoice, $paymentMethodClassName, $transactionSid)
	{
		$invoice->setPropertyValue('payment_method_class_name', $paymentMethodClassName);
		$invoice->setPropertyValue('transaction_sid', $transactionSid);
		$this->saveObject($invoice);
	}

	/**
	 * @param Invoice $invoice
	 * @return bool
	 */
	public function saveObject($invoice)
	{
		$now = date("Y-m-d H:i:s");
		if (is_null($invoice->getSID()))
		{
			$invoice->setPropertyValue('creation_date', $now);
		}
		else
		{
			$invoice->setPropertyValue('last_updated', $now);
		}
		return parent::saveObject($invoice);
	}

	public function displayInvoiceDescription($params)
	{
		$invoiceSid = $params['invoice_sid'];
		$invoice = $this->getObjectBySid($invoiceSid);
		if (is_null($invoice)) return;
		$tp = \App()->getTemplateProcessor();
		$tp->assign('invoice_sid', $invoiceSid);
		$tp->assign('description', $invoice->getDescription());
		$tp->assign('product_info', $invoice->getProductInfo());
		$tp->assign('payment_method_class_name', $params['payment_method']);
		$productInfoTemplate = $invoice->getProductInfoTemplate();
		if (empty($productInfoTemplate))
		{
			$productInfoTemplate = "payment_system^default_product_info.tpl";
		}
		return $tp->fetch($productInfoTemplate);
	}
}
