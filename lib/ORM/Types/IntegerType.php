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


class IntegerType extends Type
{
	var $minimum;
	var $maximum;
	
	function __construct($property_info)
	{
		parent::__construct($property_info);
		$this->minimum = isset($property_info['minimum']) ? $property_info['minimum'] : null;
		$this->maximum = isset($property_info['maximum']) ? $property_info['maximum'] : null;
		$this->sql_type 		= 'SIGNED';
		$this->default_template = 'integer.tpl';
	}

	function getPropertyVariablesToAssignTypeSpecific()
	{
		return array(
						'id'		=> $this->property_info['id'],
						'value'		=> $this->property_info['value'],
						'minimum'	=> $this->minimum,
						'maximum'	=> $this->maximum,
					);
	}

	function isValid()
	{
		if (!\App()->I18N->isValidInteger($this->property_info['value']))
		{
			$this->addValidationError('NOT_INTEGER_VALUE');
			return false;
		}
		$value = \App()->I18N->getInput('integer', $this->property_info['value']);
		if ((!is_null($this->minimum) && is_numeric($this->minimum) && $value < $this->minimum) || (!is_null($this->maximum) && is_numeric($this->maximum) && $value > $this->maximum))
		{
			$this->addValidationError('OUT_OF_RANGE', array('maxValue' => $this->maximum, 'minValue' => $this->minimum));
			return false;
		}
		if ($value > 2147483647 || $value < -2147483648)
		{
			$this->addValidationError('OUT_OF_MYSQL_MEDIUMINT_RANGE');
			return false;
		}
		return true;
	}

	static function getFieldExtraDetails() {
		
		return array(
		
			array(
				'id'		=> 'minimum',
				'caption'	=> 'Minimum Value', 
				'type'		=> 'integer',
				'minimum'	=> '',
                'value'     => null,
				),
			array(
				'id'		=> 'maximum',
				'caption'	=> 'Maximum Value', 
				'type'		=> 'integer',
				'minimum'	=> '',
                'value'     => null,
				),
		
		);
		
	}

    function getKeywordValue()
	{
		return $this->property_info['value'];
	}

	function getSQLValue()
	{
		if (empty($this->property_info['value']) && !is_numeric($this->property_info['value'])) return null;
		return \App()->I18N->getInput('integer', $this->property_info['value']);
		
	}

	function defineRefineSearchExtraDetailsAttributes()
	{
		if (is_null($this->minimum) || empty($this->minimum))
		{
			$this->minimum = 0;
		}
		if (is_null($this->maximum) || empty($this->maximum))
		{
			$max = \App()->DB->getSingleValue("SELECT max(`" .$this->property_info['id']. "`) FROM `" .$this->property_info['table_name']. "`");
			if (!empty($max))
				$this->maximum = $max;
		}
	}
	
}
