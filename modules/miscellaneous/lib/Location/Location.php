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

class Location extends \lib\ORM\Object
{
	public function incorporateData($data)
	{
		$this->details->incorporateData($data);
	}
	
	function toArray($decorator = null)
	{
		$properties = parent::toArray($decorator);
		return $properties;
	}

    public function __clone()
    {
    	$this->details = clone $this->details;
    }
}
