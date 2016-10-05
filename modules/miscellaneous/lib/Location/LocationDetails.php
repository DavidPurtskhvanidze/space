<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\lib\Location;

class LocationDetails extends \lib\ORM\ObjectDetails
{
	var $properties;
	protected $tableName = 'core_locations';
	
	public function getDetails()
	{
		return array
		(
			array
			(
				'id'			=> 'sid',
				'caption'		=> 'Id',
				'type'			=> 'integer',
				'length'		=> '6',
				'is_required'	=> false,
				'is_system'		=> true,
				'order'			=> null,
			),
			array
			(
				'id'			=> 'name',
				'caption'		=> 'Location ID',
				'type'			=> 'unique_string',
				'length'		=> '20',
				'is_required'	=> true,
				'is_system'		=> true,
			),
			array
			(
				'id'			=> 'longitude',
				'caption'		=> 'Longitude',
				'type'			=> 'float',
				'length'		=> '20',
				'is_required'	=> true,
			),
			array
			(
				'id'			=> 'latitude',
				'caption'		=> 'Latitude',
				'type'			=> 'float',
				'length'		=> '20',
				'is_required'	=> true,
			),
		);
	} 
	
    public function __clone()
    {
    	foreach(array_keys($this->properties) as $id) $this->properties[$id] = clone $this->properties[$id];
    }
    
}
