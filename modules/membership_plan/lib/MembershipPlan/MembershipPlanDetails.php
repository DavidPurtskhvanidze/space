<?php
/**
 *
 *    Module: membership_plan v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: membership_plan-7.5.0-1
 *    Tag: tags/7.5.0-1@19798, 2016-06-17 13:20:05
 *
 *    This file is part of the 'membership_plan' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\membership_plan\lib\MembershipPlan;

class MembershipPlanDetails extends \lib\ORM\ObjectDetails
{
	protected $tableName = 'membership_plan_plans';
	
	public static function getSystemDetailsInfo()
	{
		static $systemDetails = array
								(
									array
									(
										'id'			=> 'name',
										'caption'		=> 'Name',
										'type'			=> 'string',
										'length'		=> '255',
                                        'table_name'    => 'membership_plan_plans',
										'is_required'	=> true,
										'is_system'		=> true,
                                        'is_unique'     => true,
									),
									array
									(
										'id'			=> 'description',
										'caption'		=> 'Description',
										'type'			=> 'text',
										'is_required'	=> false,
										'is_system'		=> true,
									),
									array
									(
										'id'			=> 'price',
										'caption'		=> 'Price',
										'type'			=> 'transaction_money',
										'is_required'	=> false,
										'is_system'		=> true,
									),
									array
									(
										'id'			=> 'subscription_period',
										'caption'		=> 'Expiration Period',
										'type'			=> 'integer',
										'length'		=> '11',
										'minimum'		=> '0',
										'is_required'	=> false,
										'is_system'		=> true,
									),
									array
									(
										'id'		=> 'type',
										'caption'	=> 'Type',
										'type'		=> 'list',
										'list_values' => array(
																array(
																	'id' => 'Subscription',
																	'caption' => 'Subscription',
																),
																array(
																	'id' => 'Fee Based',
																	'caption' => 'Fee Based',
																),
															),
										'is_required'=> true,
										'is_system' => true,
									),
									array
									(
										'id'			=> 'classifieds_listing_amount',
										'caption'		=> 'Number of Listings',
										'type'			=> 'integer',
										'length'		=> '11',
										'minimum'		=> '0',
										'is_required'	=> false,
										'is_system'		=> true,
										'save_into_db'	=> false,
									),
								);

		return $systemDetails;
	}
	public function addSerializedExtraInfoProperty($serializedExtraInfo)
	{
        $this->addProperty
        (
            array
            (
				'id'		=> 'serialized_extra_info',
				'type'		=> 'array',
				'caption'	=> 'Serialized extra info',
				'value'		=> $serializedExtraInfo,
			)
		);
	}
	public function deleteSerializedExtraInfoProperty()
	{
        $this->deleteProperty('serialized_extra_info');
	}
	function addQuantityOfContractsProperty($quantity)
	{
        $this->addProperty
        (
            array
            (
				'id'		=> 'quantity_of_contracts',
				'type'		=> 'string',
				'caption'	=> 'Quantity of contracts',
				'value'		=> $quantity,
			)
		);
	}
	public function addPackagesProperty($packages)
	{
        $this->addProperty
        (
            array
            (
				'id'		=> 'packages',
				'type'		=> 'array',
				'caption'	=> 'packages',
				'value'		=> $packages,
			)
		);
	}
}
