<?php
/**
 *
 *    Module: payment_gateway_cash_payment v.7.4.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: payment_gateway_cash_payment-7.4.0-1
 *    Tag: tags/7.4.0-1@19074, 2015-12-14 12:49:27
 *
 *    This file is part of the 'payment_gateway_cash_payment' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\payment_gateway_cash_payment;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'payment_gateway_cash_payment';
	protected $caption = 'Cash Payment Gateway';
	protected $version = '7.4.0-1';
	protected $dependencies = array
	(
		'payment',
	);

	public function getModuleTemplateProviderName()
	{
		return "Cash Payment";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Cash payment gateway templates";
	}

	public function getModuleName()
	{
		return "payment_gateway_cash_payment";
	}

	public function getId()
	{
		return __CLASS__;
	}

	public function getAppIds()
	{
		return array('FrontEnd');
	}
}
