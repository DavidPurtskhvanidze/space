<?php
/**
 *
 *    Module: payment_gateway_wire_transfer v.7.4.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: payment_gateway_wire_transfer-7.4.0-1
 *    Tag: tags/7.4.0-1@19075, 2015-12-14 12:49:30
 *
 *    This file is part of the 'payment_gateway_wire_transfer' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\payment_gateway_wire_transfer;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'payment_gateway_wire_transfer';
	protected $caption = 'Wire Transfer Payment Gateway';
	protected $version = '7.4.0-1';
	protected $dependencies = array
	(
		'payment',
	);

	public function getModuleTemplateProviderName()
	{
		return "Wire Transfer";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Wire transfer payment gateway templates";
	}

	public function getModuleName()
	{
		return "payment_gateway_wire_transfer";
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
