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

interface IPaymentGateway
{
	public function getId();
	public function getCaption();

	/**
	 * @param array $callback_data
	 * @return \modules\payment\lib\Payment\Payment
	 */
	public function getPaymentFromCallbackData($callback_data);
	public function getDetailsInfo();
	public function displayAdditionalInfo();
	public function getUserFriendlyTransactionDataFromCallBackData($callbackData);
}
