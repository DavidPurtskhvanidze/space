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


namespace lib\ORM\SearchEngine\SearchCriterions;

class SearchCriterion
{
	// deps //
	protected $stringEscaper = null;
	public function setStringEscaper($escaper){$this->stringEscaper = $escaper;}

	protected $I18N = null;
	public function setI18N($i){$this->I18N = $i;}

	// data //
	protected $value     		= null;
	protected $field_value 		= null;
	protected $property_name 	= null;
	protected $property  		= null;
	protected $type 	 		= null;
	protected $radius_search_unit;

	function __construct($criterion_type)
	{
		$this->type=$criterion_type;
	}

	function getPropertyName()
	{
		return $this->property_name;
	}

	function setProperty($property)
	{
		$this->property=$property;
		$this->property_name = $property->getId();
	}

	function getProperty()
	{
		return $this->property;
	}

	function setValue($value)
	{
		$this->value=$value;
	}

	function getValue()
	{
		return array($this->type => $this->value);
	}

	function getRawValue()
	{
		return $this->value;
	}

	function setFieldValue($value)
	{
		$this->field_value = $value;
	}

	function getFieldValue()
	{
		return $this->field_value;
	}

	function getType()
	{
		return $this->type;
	}

	function getSQL()
	{
		return null;
	}

	function getSystemSQL()
	{
		return null;
	}
	
	function __toString()
	{
		return $this->property_name . ' ' . $this->type . ' ' . $this->getValue();
	}

	public function setRadiusSearchUnit($radius_search_unit)
	{
		$this->radius_search_unit = $radius_search_unit;
	}
}
