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

class PaymentManager extends \lib\ORM\ObjectManager implements \core\IService
{
    var $db_table_name 	= null;
	var $object_name	= null;

	public function init()
	{
		$this->dbManager = new \lib\ORM\ObjectDBManager();
	}

	function savePayment($payment)
	{
        $payment_sid = $payment->getSID();
		if ( empty($payment_sid) ){$payment->setPropertyValue('creation_date', date("Y-m-d H:i:s"));}
		$payment->setPropertyValue('last_updated', date("Y-m-d H:i:s"));
		parent::saveObject($payment);
	}

    function getPaymentInfoBySID($payment_sid)
    {
    	return parent::getObjectInfoBySID('payment_payments', $payment_sid);
    }

    /**
     * @param int $payment_sid
     * @return Payment
     */
	function getObjectBySID($payment_sid)
    {
    	$payment_info = $this->getPaymentInfoBySID($payment_sid);
		if (is_null($payment_info)) return null;
		/** @var $payment Payment */
    	$payment = \App()->PaymentFactory->createPayment($payment_info);
    	$payment->setSid($payment_sid);
    	return $payment;
    }

	public function deletePayment($sid)
	{
		$this->dbManager->deleteObject('payment_payments', $sid);
	}

	function getPaymentForms($payment)
	{
    	$gateways = \App()->PaymentGatewayManager->getPaymentGateways();
    	$gateways_form_info = array();
    	foreach ($gateways as $gateway)
    	{
    		$gateways_form_info[$gateway->getId()] = $gateway->buildTransactionForm($payment);
    	}
    	return $gateways_form_info;
    }

    function endorsePayment($payment_sid)
    {
    	return \App()->DB->query("UPDATE `payment_payments` SET `status`=?s WHERE `sid`=?n", Payment::STATUS_COMPLETED , $payment_sid);
    }

    function getPaymentSIDByID($id)
	{
    	return $id;
    }

	public function getAllPaymentsCount()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `payment_payments`");
	}
	public function getAllPaymentsAmount()
	{
		return \App()->DB->getSingleValue("SELECT SUM(amount) FROM `payment_payments`");
	}
	public function getEndorsedPaymentsCount()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `payment_payments` WHERE status = 'Completed'");
	}
	public function getEndorsedPaymentsAmount()
	{
		return \App()->DB->getSingleValue("SELECT SUM(amount) FROM `payment_payments` WHERE status = 'Completed'");
	}
	public function getPendingPaymentsCount()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `payment_payments` WHERE status = 'Pending'");
	}
	public function getPendingPaymentsAmount()
	{
		return \App()->DB->getSingleValue("SELECT SUM(amount) FROM `payment_payments` WHERE status = 'Pending'");
	}
	public function getPaymentsCountForLastDay()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `payment_payments` WHERE creation_date >= CURDATE() - INTERVAL 1 DAY");
	}
	public function getPaymentsCountForLastWeek()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `payment_payments` WHERE creation_date >= CURDATE() - INTERVAL 7 DAY");
	}
	public function getPaymentsCountForLastMonth()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `payment_payments` WHERE creation_date >= CURDATE() - INTERVAL 1 MONTH");
	}
	public function setDeletedUserUsername($userSid, $deletedUserUsername)
	{
		return \App()->DB->query("UPDATE `payment_payments` SET `deleted_user_username`=?s WHERE `user_sid`=?n", $deletedUserUsername , $userSid);
	}

	public function createPayment($paymentData)
	{
		return \App()->PaymentFactory->createPayment($paymentData);
	}

	/**
	 * @param \modules\payment_system\lib\Invoice $invoice
	 * @return \modules\payment\lib\Payment\Payment
	 */
	public function getPaymentForInvoice($invoice)
	{
		$paymentSid = $this->getPaymentSidByInvoiceSid($invoice->getSID());
		if (!is_null($paymentSid))
		{
			return $this->getObjectBySID($paymentSid);
		}

		$paymentInfo = array
		(
			'invoice_sid' => $invoice->getSID(),
			'user_sid' => $invoice->getUserSid(),
			'product_info' => serialize($invoice->getProductInfo()),
			'amount' => floatval_ignore_locale($invoice->getAmount()),
			'description' => $invoice->getDescription(),
			'status' => \modules\payment\lib\Payment\Payment::STATUS_PENDING,
		);
		$payment = $this->createPayment($paymentInfo);
		$this->savePayment($payment);
		return $payment;
	}

	public function getPaymentSidByInvoiceSid($invoiceSid)
	{
		return \App()->DB->getSingleValue("SELECT `sid` FROM `payment_payments` WHERE `invoice_sid` = ?n", $invoiceSid);
	}

	public function getInvoiceSidByPaymentSid($paymentSid)
	{
		return \App()->DB->getSingleValue("SELECT `invoice_sid` FROM `payment_payments` WHERE `sid` = ?n", $paymentSid);
	}
	
	public function getAllPaymentGatewayIds()
	{
		$result = array();
		$dataSet = \App()->DB->query("SELECT DISTINCT `payment_gateway_id` FROM `payment_payments`");
		foreach ($dataSet as $dataRow)
		{
			$result[] = $dataRow['payment_gateway_id'];
		}
		return $result;
	}
}
