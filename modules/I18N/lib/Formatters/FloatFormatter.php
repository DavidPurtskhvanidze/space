<?php
/**
 *
 *    Module: I18N v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: I18N-7.5.0-1
 *    Tag: tags/7.5.0-1@19784, 2016-06-17 13:19:28
 *
 *    This file is part of the 'I18N' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\I18N\lib\Formatters;

class FloatFormatter
{
	var $thousands_separator;
	var $decimals;
	var $decimal_point;
	
	function getOutput($value)
	{
		if (!is_numeric($value)) return NULL;
		return number_format($value, $this->decimals, $this->decimal_point, $this->thousands_separator);
	}
	
	function getInput($value)
	{
		$value = str_replace($this->thousands_separator, '', $value);
		$value = str_replace($this->decimal_point, '.', $value);
        $value = floatval_ignore_locale($value);
		return $value;
	}
	
	function isValid($value)
	{
		if (empty($this->decimal_point) && is_numeric($value))
			return true;
		
		if (empty($this->decimal_point))
			$this->decimal_point = '.';
		
		return preg_match("/^[+-]?\d+(\\" . $this->thousands_separator . "\d{3})*(\\" . $this->decimal_point . "\d+)?$/", $value);
	}
	
	function setThousandsSeparator($separator)
	{
		$this->thousands_separator = $separator;
	}
	
	function setDecimals($decimals)
	{
		$this->decimals = $decimals;
	}
	
	function setDecimalPoint($decimal_point)
	{
		$this->decimal_point = $decimal_point;
	}
}

?>
