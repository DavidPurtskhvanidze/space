<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\lib\ListingField;

class ListingFieldDetails extends \lib\ORM\ObjectDetails
{
	protected $tableName = 'classifieds_listing_fields';
	
	public static $common_details_info = array
			   (
				array
				(
					'id'		=> 'id',
					'caption'	=> 'ID', 
					'type'		=> 'unique_string',
					'length'	=> '20',
					'maxlength'	=> '64',
					'table_name'=> 'classifieds_listing_fields',
					'is_required'=> true,
					'is_system'	=> true,
				),
				array
				(
					'id'		=> 'caption',
					'caption'	=> 'Caption', 
					'type'		=> 'string',
					'length'	=> '20',
                    'table_name'=> 'classifieds_listing_fields',
					'is_required'=> true,
					'is_system'	=> true,
				),
				array
				(
					'id'		=> 'category_sid',
					'caption'	=> 'Category Sid',
					'type'		=> 'integer',
					'is_required'=> false,
					'is_system'	=> true,
				),
				array
				(
					'id'		=> 'type',
					'caption'	=> 'Type',
					'type'		=> 'list',
					'list_values' => array(
											array('id' => 'list',	'caption' => 'List'),
											array('id' => 'multilist',	'caption' => 'Multi-List'),
											array('id' => 'string',	'caption' => 'String'),
											array('id' => 'text', 	'caption' => 'Text'),
											array('id' => 'integer','caption' => 'Integer'),
											array('id' => 'float',	'caption' => 'Float'),
											array('id' => 'decimal', 'caption' => 'Decimal'),
											array('id' => 'money',  'caption' => 'Money'),
											array('id' => 'boolean','caption' => 'Boolean'),
											array('id' => 'geo', 	'caption' => 'Known Geographical Location'),
											array('id' => 'file', 	'caption' => 'File'),
											array('id' => 'video', 	'caption' => 'Video'),
											array('id' => 'tree', 	'caption' => 'Tree (Nested List)'),
											array('id' => 'rating', 'caption' => 'Rating'),
											array('id' => 'calendar', 'caption' => 'Calendar'),
										),
					'length'	=> '',
					'is_required'=> true,
					'is_system' => true,
				),
				array
				(
					'id'		=> 'is_required',
					'caption'	=> 'Required', 
					'type'		=> 'boolean',
					'length'	=> '20',
                    'table_name'=> 'classifieds_listing_fields',
					'is_required'=> false,
					'is_system'	=> true,
				),
			   );		
	
}
