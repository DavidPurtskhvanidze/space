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
class ArrayType extends HiddenType
{	
	function __construct($property_info)
	{
		parent::__construct($property_info);
		$this->sql_type 		= 'TEXT';
		if(isset($property_info['list_values']))
			$this->list_values = $property_info['list_values'];
		else
            $this->list_values = array();

        $this->property_info['value'] = array();
		if (isset($property_info['value']))
			$this->setValue($property_info['value']);
		
  		$this->default_template = 'array.tpl';
	}

	function setValue($value)
	{
        if (!is_null($value))
            $this->property_info['value'] = (is_array($value)) ? $value : $this->_unserialize($value);;
	}

	function getValue()
	{
        if (!isset($this->property_info['value']))
            return array();
        else
            return $this->property_info['value'];
	}
    
	function getDisplayValue()
	{
        return (isset($this->property_info['value'])) ? $this->property_info['value'] : '';
	}

	function isValid()
    {
        if (empty($this->property_info['list_values']))
        {
            return true;
        }
		$values = $this->getValue();
        if (empty($values) && $this->property_info['is_required'])
			return false;

		$result = true;

        foreach ($values as $value)
			$result &= array_key_exists($value, $this->property_info['list_values']);

		if (!$result)
		{
			$this->addValidationError('INVALID_OPTION');
			return false;
		}
		return true;
	}

    function getSQLValue()
	{
		if (!isset($this->property_info['value']))
            return 'NULL';
        
		$value = \App()->DB->real_escape_string($this->_serialize($this->property_info['value']));
		return "'$value'";
	}
	
	function getPropertyVariablesToAssignTypeSpecific()
	{
        $prefix = $this->property_info['id'];
		return array(
            'id'	=> $this->property_info['id'],
            'value'	=> $this->getValue(),

            $prefix . '_options' => $this->property_info['list_values'],
            $prefix . '_values'  => $this->getValue(),
            'list_values' => $this->property_info['list_values'],
        );
	}

    function getKeywordValue() {
		return '';
	}

	public function getColumnDefinition()
    {
        return 'TEXT';
    }

    public function _serialize($value)
    {
        return serialize($value);
    }
    
    public function _unserialize($value)
    {
        $value = unserialize($value);
        
        return (!is_array($value) ? array() : $value);
    }

}
