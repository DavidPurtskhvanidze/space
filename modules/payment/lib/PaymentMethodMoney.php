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


namespace modules\payment\lib;

class PaymentMethodMoney implements \modules\payment_system\lib\IPaymentMethod
{
	/**
	 * @var PaymentMethodMoneyHelper
	 */
	private $helper;

	public function setHelper($helper)
	{
		$this->helper = $helper;
	}

	public function init()
	{
		$this->setHelper(new PaymentMethodMoneyHelper());
	}

	public function initializePayment($invoice)
	{
		$payment = App()->PaymentManager->getPaymentForInvoice($invoice);
		\App()->PaymentTerminal->onPaymentQueued($invoice);
		$this->helper->redirectToPaymentPage($payment);
	}

	public function onPaymentGatewayCallback()
	{
		$gatewayId = $this->getGatewayId();
		$gateway = \App()->PaymentGatewayManager->getPaymentGatewayById($gatewayId);

		$callbackData = ($gatewayId == "paypal_standard") ? array_merge($_GET, $_POST) : $_REQUEST;

		$payment = $gateway->getPaymentFromCallbackData($callbackData);

		if (is_null($payment))
		{
			$displayTemplateAction = \App()->ObjectMother->createDisplayTemplateAction('payment^callback_payment_page.tpl',
				array('errors' => $gateway->getErrors()));
			$displayTemplateAction->perform();
		}
		else
		{
			$payment->setPaymentGatewayId($gateway->getId());
			\App()->PaymentManager->savePayment($payment);
			
			$payment_status = $payment->getStatus();
			if ($payment_status == \modules\payment\lib\Payment\Payment::STATUS_COMPLETED)
			{

				try
				{
					\App()->PaymentTerminal->onPaymentCompleted($payment->getInvoiceSid(), __CLASS__, $payment->getSID());
				}
				catch(\modules\payment_system\lib\Exception $e)
				{
					if ($gatewayId != "paypal_standard") throw $e;
					$invoice = \App()->InvoiceManager->getObjectBySid($payment->getInvoiceSid());
					$invoice->redirectToSuccessPage();
				}
			}
			elseif ($payment_status == \modules\payment\lib\Payment\Payment::STATUS_FAILED)
			{
				\App()->PaymentTerminal->onPaymentFailed($payment->getInvoiceSid());
				$displayTemplateAction = \App()->ObjectMother->createDisplayTemplateAction('payment^callback_payment_page.tpl',
					array('errors' => array('TRANSACTION_FAILED' => true)));
				$displayTemplateAction->perform();
			}
			elseif ($payment_status == \modules\payment\lib\Payment\Payment::STATUS_IN_PROGRESS)
			{
				\App()->SuccessMessages->addMessage('TRANSACTION_IN_PROCESSING', array('transactionId' => $payment->getSID()));
				$url = \App()->PageRoute->getPagePathById('user_payments');
				throw new \lib\Http\RedirectException($url);
			}
		}
	}

	private function getGatewayId()
	{
		$callback_page_uri = \App()->PageManager->getBaseUri();
		preg_match("(.*$callback_page_uri([^/]*)/?)", \App()->PageManager->getPageUri(), $mm);
		return $mm[1];
	}

	public function getCaption()
	{
		return 'Monetary';
	}

	public function areRecurringPaymentsPossible()
	{
		return false;
	}

	public function getUserBalanceManager()
	{
		throw new Exception('The payment method "Money" does not provide a User Balance Manager');
	}

	public function endorsePayment($paymentSid)
	{
		\App()->PaymentManager->endorsePayment($paymentSid);
		$invoiceSid = \App()->PaymentManager->getInvoiceSidByPaymentSid($paymentSid);
		\App()->PaymentTerminal->onPaymentEndorsed($invoiceSid, __CLASS__, $paymentSid);
	}

	public function displayPriceWithCurrency($amount, $templateProcessor)
	{
		$templateProcessor->assign('amount', $amount);
		return $templateProcessor->fetch('payment^price_with_currency.tpl');
	}

	public function convertPrice($amount)
	{
		return $amount;
	}

	public function isPricePropertyValueValid(\lib\ORM\Types\Type $type)
	{
		if (!$this->isValueValid($type->getSavableValue()))
		{
			$type->addValidationError('NOT_PAYMENT_MONEY_VALUE', array(), 'payment');
			return false;
		}
        return true;
	}
	
	public function isValueValid($value)
	{
		return (bool) preg_match("/^[0-9]{1,30}(\.[0-9]{1,2})?$/", $value);
	}

	public function formatPrice($amount)
	{
		if (null !== $amount && $this->isValueValid($amount))
		{
			$amount = number_format($amount, 2, '.', '');
		}
		return $amount;
	}

    public function getNumberOfDigitsAfterDecimal()
    {
        return 2;
    }
}
