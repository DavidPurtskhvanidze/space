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


class FloatType extends IntegerType
{	
	function __construct($property_info)
	{
		parent::__construct($property_info);
		$this->sql_type 		= 'DECIMAL';
		$this->default_template = 'float.tpl';
	}

	function isValid()
	{
		if (!\App()->I18N->isValidFloat($this->property_info['value']))
		{
			$this->addValidationError('NOT_FLOAT_VALUE');
			return false;
		}
		$value = \App()->I18N->getInput('float', $this->property_info['value']);
		if ((!is_null($this->minimum) && is_numeric($this->minimum) && $value < $this->minimum) || (!is_null($this->maximum) && is_numeric($this->maximum) && $value > $this->maximum))
		{
			$this->addValidationError('OUT_OF_RANGE', array('maxValue' => $this->maximum, 'minValue' => $this->minimum));
			return false;
		}
		return true;
	}
	
	function getSQLValue()
	{
		return $this->_format_value_with_signs_num();
	}
	
	function getKeywordValue()
	{
		return $this->_format_value_with_signs_num();
	}

	function _format_value_with_signs_num()
	{
		if (empty($this->property_info['value']) && !is_numeric($this->property_info['value'])) return null;
		$value = \App()->I18N->getInput('float', $this->property_info['value']);
		if (isset($this->property_info['signs_num']) && is_numeric($this->property_info['signs_num']))
		{
			return sprintf("%0." . $this->property_info['signs_num'] . "F", $value);
		}
		return $value;
	}
	

	function getPropertyVariablesToAssignTypeSpecific()
	{
		return array(
						'id'		=> $this->property_info['id'],
						'value'		=> $this->property_info['value'],
						'minimum'	=> $this->minimum,
						'maximum'	=> $this->maximum,
						'signs_num'		=> $this->property_info['signs_num'],
					);
	}


	static function getFieldExtraDetails() {
		return array(
			array(
				'id'		=> 'minimum',
				'caption'	=> 'Minimum Value', 
				'type'		=> 'string',
				'value'		=> null,
				),
			array(
				'id'		=> 'maximum',
				'caption'	=> 'Maximum Value', 
				'type'		=> 'string',
				'value'		=> null,
				),
			array(
				'id'		=> 'signs_num',
				'caption'	=> 'Number Of Digits To The Right Of The Decimal Point', 
				'type'		=> 'integer',
				'minimum'	=> 0,
				'value'		=> 2,
				),
		);
	}
	
	public function getColumnDefinition(){ return 'FLOAT'; }

}

