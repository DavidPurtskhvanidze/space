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

class PaymentGatewayDetails extends \lib\ORM\ObjectDetails
{
	protected $tableName = 'payment_gateways';
	
	function getDetailsInfo()
	{
		return array
			   (
			    array
				(
					'id'		=> 'id',
					'caption'	=> 'ID',
					'type'		=> 'string',
					'length'	=> '20',
					'is_required'=> true,
					'is_system'	=> true,
				),
			    array
				(
					'id'		=> 'caption',
					'caption'	=> 'Caption',
					'type'		=> 'string',
					'length'	=> '20',
					'is_required'=> true,
					'is_system'	=> true,
				),
			    array
				(
					'id'		=> 'active',
					'caption'	=> 'Active gateway',
					'type'		=> 'boolean',
					'length'	=> '20',
					'is_required'=> true,
					'is_system'	=> true,
				),
			   );
	}
}

?>
