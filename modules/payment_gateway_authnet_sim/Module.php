<?php
/**
 *
 *    Module: payment_gateway_authnet_sim v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: payment_gateway_authnet_sim-7.3.0-1
 *    Tag: tags/7.3.0-1@18550, 2015-08-24 13:37:32
 *
 *    This file is part of the 'payment_gateway_authnet_sim' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\payment_gateway_authnet_sim;

class Module extends \core\Module
{
	protected $name = 'payment_gateway_authnet_sim';
	protected $caption = 'Authorize.Net Payment Gateway';
	protected $version = '7.3.0-1';
	protected $dependencies = array
	(
		'payment',
	);
}
