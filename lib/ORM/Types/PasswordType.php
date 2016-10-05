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


class PasswordType extends Type
{
    function __construct(&$property_info)
	{
		parent::__construct($property_info);
		$this->default_template = 'password.tpl';
	}

	function getPropertyVariablesToAssignTypeSpecific()
	{
		return array(
						'id' 	=> $this->property_info['id'],
					);
	}

	function isValid()
	{
		if ($this->property_info['value']['original'] != $this->property_info['value']['confirmed'])
		{
			$this->addValidationError('NOT_CONFIRMED');
			return false;
		}
		return true;
	}

	function getSavableValue()
	{
       	return $this->property_info['value']['original'];
	}
      
    function getDisplayValue()
	{
		return null;
	}

    function getSearchValue($template)
	{
		return null;
	}
	
	function getSQLValue()
	{
       	return "PASSWORD('" . \App()->DB->real_escape_string($this->property_info['value']['original']) . "')";
	}
    
    function isEmpty()
	{
		return empty($this->property_info['value']['original']);
	}
	
	public function getColumnDefinition(){ return 'VARCHAR(120) CHARACTER SET UTF8'; }

}

?>
