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

class PaymentDetails extends \lib\ORM\ObjectDetails
{
	protected $tableName = 'payment_payments';
	
	protected $detailsInfo = array
	(
		array
		(
			'id'		=> 'invoice_sid',
			'caption'	=> 'Invoice Sid',
			'type'		=> 'integer',
			'is_required'=> true,
		),
		array
		(
			'id'		=> 'user_sid',
			'caption'	=> 'User SID',
			'type'		=> 'string',
			'length'	=> '20',
		    'table_name'=> 'payment_payments',
			'is_required'=> true,
			'is_system'	=> true,
		),
		array
		(
			'id'		=> 'deleted_user_username',
			'caption'	=> 'deleted_user_username',
			'type'		=> 'string',
		    'table_name'=> 'payment_payments',
			'is_required'=> false,
			'is_system'	=> true,
		),
		array
		(
			'id'		=> 'description',
			'caption'	=> 'Description',
			'type'		=> 'text',
			'length'	=> '1000',
		    'table_name'=> 'payment_payments',
			'is_required'=> true,
			'is_system'	=> true,
		),
		array
		(
			'id'		=> 'product_id',
			'caption'	=> 'Product Info', 
			'type'		=> 'string',
			'length'	=> '120',
		    'table_name'=> 'payment_payments',
			'is_required'=> true,
			'is_system'	=> true,
		),
		array
		(
			'id'		=> 'product_info',
			'caption'	=> 'Product Info',
			'type'		=> 'text',
			'length'	=> '20',
		    'table_name'=> 'payment_payments',
			'is_required'=> true,
			'is_system'	=> true,
		),
		array
		(
			'id'		=> 'amount',
			'caption'	=> 'Amount',
			'type'		=> 'string',
			'length'	=> '20',
		    'table_name'=> 'payment_payments',
			'is_required'=> true,
			'is_system'	=> true,
		),
		array
		(
			'id'		=> 'creation_date',
			'caption'	=> 'Date',
			'type'		=> 'date',
		    'table_name'=> 'payment_payments',
			'is_required'=> true,
			'is_system'	=> true,
		),
		array
		(
			'id'		=> 'last_updated',
			'caption'	=> 'Date',
			'type'		=> 'date',
		    'table_name'=> 'payment_payments',
			'is_required'=> true,
			'is_system'	=> true,
		),
		array
		(
			'id'		=> 'status',
			'caption'	=> 'status', 
			'type'		=> 'list',
			'length'	=> '20',
		    'table_name'=> 'payment_payments',
			'is_required'=> true,
			'is_system'	=> true,
		    'list_values' => array(
		                            array(
										'id' 		=> 'Pending',
										'caption' 	=> 'Pending',
									),
									array(
										'id' 		=> 'Failed',
										'caption' 	=> 'Failed',
									),
									array(
										'id'		=> 'Completed',
										'caption' 	=> 'Completed',
									),
			                        array(
										'id'		=> 'inProgress',
										'caption' 	=> 'In Progress',
									),
								),
		),
		array
		(
			'id'		=> 'callback_data',
			'caption'	=> 'callback_data', 
			'type'		=> 'text',
			'length'	=> '20',
		    'table_name'=> 'payment_payments',
			'is_required'=> true,
			'is_system'	=> true,
		),
		array(
			'id'		=> 'payment_gateway_id',
			'caption'	=> 'Payment Gateway',
			'type'		=> 'string',
			'is_required'=> false,
			'is_system'	=> true,
		),
	);
}
