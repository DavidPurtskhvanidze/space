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


namespace modules\membership_plan\lib\Package;

class PackageDetails extends \lib\ORM\ObjectDetails
{
	protected $tableName = '';

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
										'is_required'	=> true,
										'is_system'		=> true,
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
								);

		return $systemDetails;
	}
	function addNumberOfListingsProperty($numberOfListings)
	{
        $this->addProperty
        (
            array
            (
				'id'		=> 'number_of_listings',
				'type'		=> 'string',
				'caption'	=> '# of Listings',
				'value'		=> $numberOfListings,
			)
		);
	}
	function modifyBoolenasToListWithIntactOption()
	{
		foreach ($this->properties as $key => $property)
		{
			if ($property->getType() == 'boolean')
			{
				$propertyInfo = $property->type->property_info;

				$propertyInfo['type'] = 'list';
				$propertyInfo['list_values'] = array(
														array('id' => '', 'caption' => 'Leave intact'),
														array('id' => '1', 'caption' => 'Enable'),
														array('id' => '0', 'caption' => 'Disable'),
													);

			    $this->addProperty($propertyInfo);
			}
		}
	}
}
