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


namespace modules\payment\apps\AdminPanel;

/**
 * Deactivate payment gateway validator
 *
 * Interface designed for validating deactivate payment gateway action in AdminPanel. If it returns false, payment gateway will not be deactivated.
 *
 * @category ExtensionPoint
 */
interface IDeactivatePaymentGatewayValidator
{
	/**
	 * Setter of gateway id
	 * @param string $gatewayId
	 */
	public function setGatewayId($gatewayId);
	/**
	 * Action validator
	 * @return boolean
	 */
	public function isValid();
}
