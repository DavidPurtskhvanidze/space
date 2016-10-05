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

class UrlType extends Type
{
	function __construct($property_info)
	{
		parent::__construct($property_info);
		$this->default_template = 'string.tpl';
	}

	function getPropertyVariablesToAssignTypeSpecific()
	{
		return array
		(
			'id' 	=> $this->property_info['id'],
			'value'	=> $this->property_info['value'],
		);
	}

	function isValid()
	{
		$value = $this->property_info['value'];

		if (strpos($value, '/') === 0) // hack for relative URLs
		{
			return true;
		}

		if (!filter_var($value, FILTER_VALIDATE_URL))
		{
			$this->addValidationError('INVALID_URL');
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
