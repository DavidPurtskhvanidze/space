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


class DecimalType extends FloatType
{
	var $signs_num;
	
	public function __construct($property_info)
	{
		$this->property_info = $property_info;
		$this->signs_num = $property_info['signs_num'];
		$this->default_template = 'decimal.tpl';
	}
	public function isValid()
	{
		if (!\App()->I18N->isValidFloat($this->property_info['value']))
		{
			$this->addValidationError('NOT_FLOAT_VALUE');
			return false;
		}
		return true;
	}
	public function getSQLValue()
	{
		if (trim($this->property_info['value']) == '') return null;
		return \App()->I18N->getInput('float', $this->property_info['value']);
	}
	public function getKeywordValue()
	{
		return $this->getSQLValue();
	}
	public static function getFieldExtraDetails()
	{
		return array
		(
			array
			(
				'id' => 'signs_num',
				'caption' => 'Number Of Digits To The Right Of The Decimal Point',
				'type' => 'integer',
				'minimum' => 0,
				'maximum' => 30,
				'is_required' => true,
				'value' => 2,
			),
		);
	}
	public function getColumnDefinition()
	{
		return "DECIMAL(30, {$this->signs_num})";
	}
	public function _getDisplayValue()
	{
		return \App()->I18N->getFloat($this->property_info['value'], $this->signs_num);
	}

}
