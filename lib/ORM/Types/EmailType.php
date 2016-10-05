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

class EmailType extends Type
{
	function __construct($property_info)
	{
		parent::__construct($property_info);
		$this->default_template = 'string.tpl';
		$this->property_info['autocomplete_service_name'] = isset($property_info['autocomplete_service_name']) ? $property_info['autocomplete_service_name'] : null;
		$this->property_info['autocomplete_method_name'] = isset($property_info['autocomplete_method_name']) ? $property_info['autocomplete_method_name'] : null;
	}

	function getPropertyVariablesToAssignTypeSpecific()
	{
		$properties['maxlength'] = $this->property_info['maxlength'];
		$properties['autocomplete_service_name'] = $this->property_info['autocomplete_service_name'];
		$properties['autocomplete_method_name'] = $this->property_info['autocomplete_method_name'];
		
		return $properties;
	}

	function isValid()
	{
		if (!filter_var($this->property_info['value'], FILTER_VALIDATE_EMAIL))
		{
			$this->addValidationError('INVALID_EMAIL_FORMAT');
			return false;
		}
		
		return true;
	}
	
	static function getFieldExtraDetails()
	{
		return array();
	}

	function getSQLValue()
	{		
		if (is_null($this->property_info['value'])) return 'NULL';
		return "'". \App()->DB->real_escape_string($this->property_info['value']) ."'";
	}

    function getKeywordValue()
	{
		return $this->property_info['value'];
	}

	public function getColumnDefinition()
	{ 
		return "VARCHAR(255) CHARACTER SET UTF8"; 
	}
	
	public function getDisplayValue()
	{
		return $this->property_info['value'];
	}
}
