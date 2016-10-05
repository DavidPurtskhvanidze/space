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


class BooleanType extends Type
{
    function __construct($property_info)
	{
		parent::__construct($property_info);
		$this->default_template = 'boolean.tpl';
	}

	function getPropertyVariablesToAssignTypeSpecific()
	{
		return array(
						'id' 	=> $this->property_info['id'],
						'value' => $this->property_info['value'],
						'caption' => $this->property_info['caption'],
					);
	}

	function getSQLValue() {
		
		return intval($this->property_info['value']);
		
	}
	
	function getKeywordValue()
	{
		$keyword = $this->property_info['value'] ? $this->property_info['caption'] : "";
		return $keyword;
	}

	public function getColumnDefinition(){ return 'BOOLEAN'; }
	
	public function getDisplayValue()
	{
        return new BooleanDisplayValue($this->property_info['value']);
	}

    public function isEmpty()
    {
        return empty($this->property_info['value']);
    }

}

class BooleanDisplayValue implements \ArrayAccess
{
	private $accessMethods;

	public function __construct($value)
	{
		$this->accessMethods = array(
            'isTrue'    => (bool) $value,
            'isFalse'   => !(bool) $value
        );
	}

	public function offsetGet($index)
	{
		if (!isset($this->accessMethods[$index]))
            throw new \Exception("Illegal offset '$index' requested for '{$this->propertyId}'");

		return $this->accessMethods[$index];
	}
	public function offsetExists($index)
	{
		return isset($this->accessMethods[$index]);
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
		return ($this->accessMethods['isTrue']) ? 'true' : 'false';
	}
}
?>
