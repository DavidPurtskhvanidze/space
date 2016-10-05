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

class PaymentTerminal implements \core\IService
{
	/**
	 * @param Invoice $invoice
	 */
	public function initializePayment($invoice)
	{
		$requiredPaymentMethodClassName = $invoice->getRequiredPaymentMethodClassName();
		if (!empty($requiredPaymentMethodClassName))
		{
			$paymentMethod = \App()->PaymentSystemManager->getPaymentMethodByClassName($requiredPaymentMethodClassName);
		}
		else
		{
			$paymentMethod = \App()->PaymentSystemManager->getCurrentPaymentMethod();
		}
		$paymentMethod->initializePayment($invoice);
	}

	/**
	 * @param Invoice $invoice
	 */
	public function onPaymentQueued($invoice)
	{
		$invoice->onPaymentQueued();
	}

	public function onPaymentCompleted($invoiceSid, $paymentMethodClassName, $transactionSid)
	{
		$invoice = \App()->InvoiceManager->getObjectBySid($invoiceSid);
		$this->performSuccessActionAndMarkAsPaid($invoice, $paymentMethodClassName, $transactionSid);
		$invoice->redirectToSuccessPage();
	}

	public function onPaymentEndorsed($invoiceSid, $paymentMethodClassName, $transactionSid)
	{
		$invoice = \App()->InvoiceManager->getObjectBySid($invoiceSid);
		$this->performSuccessActionAndMarkAsPaid($invoice, $paymentMethodClassName, $transactionSid);
	}

	/**
	 * @param Invoice $invoice
	 */
	private function performSuccessActionAndMarkAsPaid($invoice, $paymentMethodClassName, $transactionSid)
	{
		$invoice->performSuccessAction();
		\App()->InvoiceManager->setPaymentDetailsForInvoice($invoice, $paymentMethodClassName, $transactionSid);
		// Invoice MUST BE marked as paid right after the success action is performed
		\App()->InvoiceManager->markInvoiceAsPaid($invoice);
	}

	public function onPaymentFailed($invoiceSid)
	{
		$invoice = \App()->InvoiceManager->getObjectBySid($invoiceSid);
		$invoice->performFailureAction();
	}
}
