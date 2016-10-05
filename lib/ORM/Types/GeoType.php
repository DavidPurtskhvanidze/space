<?php
/**
 *
 *    iLister v.7.5.0, (c) WorksForWeb 2005 - 2016
 *
 *    Package: iLister-7.5.0
 *    Tag: modular/projects/classifieds/tags/7.5.0@19890, 2016-06-17 13:38:22
 *
 *    This file is a part of iLister software
 *    and is protected by international copyright laws
 *
 */


namespace lib\ORM\Types;



class GeoType extends Type
{
	function __construct($property_info)
	{
		parent::__construct($property_info);
		$this->default_template = 'geo.tpl';
	}

	function getPropertyVariablesToAssignTypeSpecific()
	{
		$location_info = \App()->LocationManager->getLocationInfoByName($this->property_info['value']);
		if (!is_array($location_info))
		{
			$location_info = array();
		}
		return array_merge(array(
						'id' 	=> $this->property_info['id'],
						'value'	=> $this->property_info['value'],
					), $location_info);
	}

	function isValid()
	{
		if (!\App()->LocationManager->doesLocationExist($this->property_info['value']))
		{
			$this->addValidationError('LOCATION_NOT_EXISTS');
			return false;
		}
		return true;
	}

	function getDisplayValue()
	{
		$locationInfo = \App()->LocationManager->getLocationInfoByName($this->property_info['value']);
		if (empty($locationInfo)) $locationInfo = array('name' => null, 'latitude' => null, 'longitude' => null);
		return new GeoDisplayValue($locationInfo);
	}

    function getKeywordValue()
	{
		return $this->property_info['value'];
	}
	
	function getSQLValue()
	{
		return "'". \App()->DB->real_escape_string($this->property_info['value']) ."'";
	}
	
	public function getColumnDefinition(){ return 'VARCHAR(60)';}
}


class GeoDisplayValue implements \ArrayAccess
{
	/**
		    [sid] => 37850
		    [name] => 90001
		    [longitude] => -118.249
		    [latitude] => 33.9733
	*/
	
	private $locationInfo;

	public function __construct($locationInfo)
	{
		$this->locationInfo = $locationInfo;
	}

	public function offsetGet($index)
	{
		if (!array_key_exists($index, $this->locationInfo)) throw new \Exception("Illegal offset '$index' requested for '{$this->propertyId}'");
		return $this->locationInfo[$index];
	}
	public function offsetExists($index)
	{
		return isset($this->locationInfo[$index]);
	}
	public function offsetSet($index, $value)
	{
		throw new \Exception('This object is read only');
	}
	public function offsetUnset($index)
	{
		throw new \Exception('This object is read only');
	}
	public function __toString()
	{
		return (string) $this->locationInfo['name'];
	}

}
