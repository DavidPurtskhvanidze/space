<?php
/**
 *
 *    Module: basket v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: basket-7.5.0-1
 *    Tag: tags/7.5.0-1@19771, 2016-06-17 13:18:56
 *
 *    This file is part of the 'basket' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\basket\apps\FrontEnd\scripts;

class PaymentSuccessPageHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'My Basket';
	protected $moduleName = 'basket';
	protected $functionName = 'payment_success_page';
	/**
	 * Invoice
	 * @var \modules\payment_system\lib\Invoice
	 */
	private $invoice;

	public function respond()
	{
		$this->checkAndInitEnvironment();

		$data = array(
			'amount' => $this->invoice->getAmount(),
			'transaction_id' => $this->invoice->getTransactionSid(),
		);

		\App()->SuccessMessages->addMessage('PAYMENT_COMPLETED', $data);
		throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('user_payments'));
	}

	private function checkAndInitEnvironment()
	{
		$this->invoice = \App()->InvoiceManager->getObjectBySid(\App()->Request['invoice_sid']);
		if (is_null($this->invoice))
		{
			throw new \lib\Http\NotFoundException('Invoice not found');
		}
		if ($this->invoice->getStatus() != \modules\payment_system\lib\Invoice::STATUS_PAID)
		{
			throw new \lib\Http\ForbiddenException('Invoice is not paid');
		}
	}
}
